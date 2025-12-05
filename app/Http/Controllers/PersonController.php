<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Person;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PersonController extends Controller
{
    public function index()
    {
        // Ambil user milik admin yang sedang login
        $admin = Auth::user();

        // Ambil semua person dari user yang dimiliki admin tersebut
        $persons = Person::whereIn('user_id', $admin->users->pluck('id'))->paginate(10);

        return view('admin.persons.index', compact('persons'));
    }

    public function create()
    {
        return view('admin.persons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:persons,email|unique:users,email',
            'phone' => 'nullable|string',
            'password' => 'required|string|min:6',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        DB::beginTransaction();

        try {
            $admin = Auth::user();

            Log::info('Admin ID: ' . $admin->id); // Cek admin login
            Log::info('Creating user with data:', $request->all()); // Cek request

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => 'user',
                'admin_id' => $admin->id, // Gunakan variabel $admin
            ]);

            Log::info('User created: ID ' . $user->id);

            Person::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'user_id' => $user->id,
            ]);

            Log::info('Person created successfully');

            DB::commit();

            return redirect()->route('admin.persons.index')->with('success', 'Akun user berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating person and user: ' . $e->getMessage());

            // Handler untuk constraint violation
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                return back()->with('error', 'Email sudah terdaftar. Silakan gunakan email yang berbeda.');
            }

            return back()->with('error', 'Gagal membuat user: ' . $e->getMessage());
        }
    }


    public function edit(Person $person)
    {
        // Validasi akses admin hanya bisa edit person miliknya
        if ($person->user->admin_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('admin.persons.edit', compact('person'));
    }

    public function update(Request $request, Person $person)
    {
        if ($person->user->admin_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:persons,email,' . $person->id,
            'phone' => 'nullable|string',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
        ]);

        $person->update($request->only(['name', 'email', 'phone']));

        return redirect()->route('admin.persons.index')->with('success', 'Akun user berhasil diperbarui');
    }

    public function destroy(Person $person)
    {
        if ($person->user->admin_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $person->attendances()->delete();

        if ($person->user) {
            \App\Models\Attendance::where('user_id', $person->user->id)->delete();
            $person->user()->delete();
        }

        $person->delete();

        return back()->with('success', 'Akun user berhasil dihapus');
    }
}
