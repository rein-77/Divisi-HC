<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pengaturan Akun
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>
    </div>

    <!-- Confirm Modal for Password Update -->
    <div
        x-data="{ open: false }"
        x-on:open-modal.window="if ($event.detail === 'confirm-password-update') open = true"
        x-on:close-modal.window="open = false"
        x-on:keydown.escape.window="open = false"
        x-show="open"
        x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        aria-modal="true"
        role="dialog"
        style="display: none;"
    >
        <!-- Overlay -->
        <div class="fixed inset-0 bg-gray-500/50" x-show="open" x-transition.opacity @click="open = false"></div>

        <!-- Modal Panel -->
        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md mx-4" x-show="open" x-transition.scale>
            <div class="p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <div class="ml-3 w-0 flex-1">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Konfirmasi Perubahan Kata Sandi</h3>
                        <div class="mt-2 text-sm text-gray-600">
                            Apakah Anda yakin ingin mengubah kata sandi Anda? Pastikan kata sandi baru sudah benar dan aman.
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-6 pb-6 flex justify-end space-x-3">
                <button type="button"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition"
                    @click="open = false"
                >
                    Batal
                </button>
                <button type="button"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition"
                    @click="open = false; document.getElementById('password-update-form').submit()"
                >
                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Ya, Ubah Kata Sandi
                </button>
            </div>
        </div>
    </div>
</x-app-layout>
