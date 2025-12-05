@extends('layouts.app')

@section('content')
    <div class="py-6 px-4">
        <div class="max-w-7xl mx-auto">
            <!-- Alert Error -->
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-red-800 font-semibold mb-2">❌ Terjadi kesalahan:</p>
                    <ul class="text-red-700 text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Alert Success -->
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-green-800 font-semibold">✓ {{ session('success') }}</p>
                </div>
            @endif

            <!-- Alert Warning -->
            @if (session('warning'))
                <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p class="text-yellow-800 font-semibold">⚠️ {{ session('warning') }}</p>
                </div>
            @endif

            <!-- Alert Error Session -->
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-red-800 font-semibold">❌ {{ session('error') }}</p>
                </div>
            @endif

            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Jadwal - {{ $schedule->updated_at->format('d M Y') }}
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">
                        Status:
                        <span class="font-medium {{ $schedule->is_validated ? 'text-green-600' : 'text-yellow-600' }}">
                            {{ $schedule->is_validated ? 'Tervalidasi' : 'Belum divalidasi' }}
                        </span>
                    </p>
                </div>
                <a href="{{ route('admin.schedules.index') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>

            <div class="bg-white shadow rounded-lg overflow-hidden">
                <form id="scheduleForm" action="{{ route('admin.schedules.update', $schedule) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="p-6 border-b border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Date Input -->
                            <div>
                                <label for="date" class="input-label">Terakhir diubah <span
                                        class="text-red-500">*</span></label>
                                <input type="datetime-local" name="date" id="date"
                                    value="{{ old('date', $schedule->updated_at->format('Y-m-d H:i')) }}"
                                    class="input-field" required>
                                @error('date')
                                    <p class="input-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status Kehadiran
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Validasi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($persons as $person)
                                    @php
                                        $attendance = $person->attendances->first();
                                    @endphp
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $person->name }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="hidden" name="attendances[{{ $person->id }}][person_id]"
                                                value="{{ $person->id }}">
                                            <select name="attendances[{{ $person->id }}][status]" class="select-field">
                                                <option value="present"
                                                    {{ ($attendance->status ?? '') == 'present' ? 'selected' : '' }}>Hadir
                                                </option>
                                                <option value="alpa"
                                                    {{ ($attendance->status ?? '') == 'alpa' ? 'selected' : '' }}>Alpa
                                                </option>
                                            </select>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <label class="inline-flex items-center">
                                                <input type="hidden" name="attendances[{{ $person->id }}][is_validated]"
                                                    value="0">
                                                <input type="checkbox"
                                                    name="attendances[{{ $person->id }}][is_validated]" value="1"
                                                    {{ $attendance->is_validated ?? false ? 'checked' : '' }}
                                                    class="form-checkbox h-5 w-5 text-blue-600">
                                                <span class="ml-2 text-sm text-gray-700">Valid</span>
                                            </label>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 text-right">
                        <button type="submit" id="submitBtn" class="btn-primary">
                            <i class="fas fa-save mr-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .input-label {
            @apply block text-sm font-medium text-gray-700 mb-1;
        }

        .input-field {
            @apply mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm;
        }

        .input-error {
            @apply mt-1 text-sm text-red-600;
        }

        .select-field {
            @apply block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md;
        }

        .btn-primary {
            @apply inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
        }

        .btn-secondary {
            @apply inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('scheduleForm');
            const submitBtn = document.getElementById('submitBtn');
            const dateInput = document.getElementById('date');

            // Form submission handler
            submitBtn.addEventListener('click', function(e) {
                e.preventDefault();

                // Check if date is filled
                if (!dateInput.value) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Tolong masukkan tanggal terlebih dahulu',
                        confirmButtonColor: '#3B82F6',
                    });
                    return;
                }

                // Show confirmation dialog
                Swal.fire({
                    title: 'Konfirmasi Perubahan',
                    text: 'Apakah Anda yakin ingin menyimpan perubahan jadwal ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3B82F6',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Ya, Simpan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
