<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Person;

class PersonController extends Controller
{
    public function index()
    {
        $persons = Person::paginate(10);
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
        ]);

        // Paksa semua 'person' menjadi 'user' (role di database hanya user/admin)
        $role = 'user';

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $role,
        ]);

        Person::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'user_id' => $user->id,
        ]);

        return redirect()->route('admin.persons.index')->with('success', 'Person and user account created successfully');
    }



    public function edit(Person $person)
    {
        return view('admin.persons.edit', compact('person'));
    }

    public function update(Request $request, Person $person)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:persons,email,' . $person->id,
            'phone' => 'nullable|string',
        ]);

        $person->update($request->all());
        return redirect()->route('admin.persons.index')->with('success', 'Person updated successfully');
    }

    public function destroy(Person $person)
    {
        $person->delete();
        return back()->with('success', 'Person deleted successfully');
    }
}
