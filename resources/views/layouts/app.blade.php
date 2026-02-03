<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">
    <div class="min-h-screen">
        @include('partials.navbar')

        <main class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 mt-0">
            @yield('content')
        </main>
    </div>

    <!-- Custom Confirmation Modal -->
    <div id="confirmModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"
                onclick="closeConfirmModal()"></div>

            <div
                class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full border border-slate-200 transform transition-all">
                <!-- Header -->
                <div class="bg-gradient-to-r from-slate-800 to-slate-700 px-6 py-4 rounded-t-2xl">
                    <h3 class="text-lg font-bold text-white flex items-center" id="modalTitle">
                        <svg class="w-6 h-6 mr-2" id="modalIcon" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24"></svg>
                        <span id="modalTitleText"></span>
                    </h3>
                </div>

                <!-- Body -->
                <div class="px-6 py-5">
                    <p class="text-slate-700 text-sm" id="modalMessage"></p>
                </div>

                <!-- Footer -->
                <div class="bg-slate-50 px-6 py-4 rounded-b-2xl flex justify-end gap-3 border-t border-slate-200">
                    <button onclick="closeConfirmModal()"
                        class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-700 font-medium rounded-lg border border-slate-300 transition-colors text-sm">
                        Batal
                    </button>
                    <button id="modalConfirmBtn" class="px-4 py-2 font-medium rounded-lg transition-colors text-sm">
                    </button>
                </div>
            </div>
        </div>
    </div>

    @stack('styles')
    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let confirmCallback = null;

        function showConfirmModal(options) {
            const modal = document.getElementById('confirmModal');
            const icon = document.getElementById('modalIcon');
            const titleText = document.getElementById('modalTitleText');
            const message = document.getElementById('modalMessage');
            const confirmBtn = document.getElementById('modalConfirmBtn');

            // Set icon based on type
            if (options.type === 'danger') {
                icon.innerHTML =
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>';
                confirmBtn.className =
                    'px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-medium rounded-lg transition-colors text-sm shadow-lg';
            } else if (options.type === 'warning') {
                icon.innerHTML =
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>';
                confirmBtn.className =
                    'px-4 py-2 bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white font-medium rounded-lg transition-colors text-sm shadow-lg';
            } else {
                icon.innerHTML =
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
                confirmBtn.className =
                    'px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-medium rounded-lg transition-colors text-sm shadow-lg';
            }

            titleText.textContent = options.title;
            message.textContent = options.message;
            confirmBtn.textContent = options.confirmText || 'Ya';
            confirmCallback = options.onConfirm;

            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeConfirmModal() {
            document.getElementById('confirmModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            confirmCallback = null;
        }

        document.getElementById('modalConfirmBtn').addEventListener('click', function() {
            if (confirmCallback) confirmCallback();
            closeConfirmModal();
        });

        // Logout confirmation
        window.confirmLogout = function() {
            showConfirmModal({
                type: 'warning',
                title: 'Konfirmasi Logout',
                message: 'Apakah Anda yakin ingin keluar dari sistem?',
                confirmText: 'Ya, Logout',
                onConfirm: function() {
                    document.getElementById('logout-form').submit();
                }
            });
        };

        // Delete confirmation
        function confirmDelete(formId, itemName) {
            showConfirmModal({
                type: 'danger',
                title: 'Konfirmasi Hapus',
                message: 'Apakah Anda yakin ingin menghapus ' + itemName + '? Tindakan ini tidak dapat dibatalkan.',
                confirmText: 'Ya, Hapus',
                onConfirm: function() {
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>
</body>

</html>
