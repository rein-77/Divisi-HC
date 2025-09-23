@props([
    'name' => 'confirm-modal',
    'title' => 'Konfirmasi',
    'message' => 'Apakah Anda yakin?',
    'confirmText' => 'Konfirmasi',
    'cancelText' => 'Batal',
    'form' => null, // ID form yang akan disubmit saat konfirmasi
    'variant' => 'danger', // danger|primary
])

<div
    x-data="{ open: false }"
    x-on:open-modal.window="if ($event.detail === '{{ $name }}') open = true"
    x-on:close-modal.window="open = false"
    x-on:keydown.escape.window="open = false"
    x-show="open"
    x-transition.opacity
    class="fixed inset-0 z-50 flex items-center justify-center"
    aria-labelledby="modal-title-{{ $name }}"
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
                    <!-- Triangle warning icon (exclamation inside a triangle) -->
                    <svg class="h-6 w-6 {{ $variant === 'danger' ? 'text-red-600' : 'text-indigo-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.29 3.86L1.82 18a1 1 0 00.86 1.5h18.64a1 1 0 00.86-1.5L13.71 3.86a1 1 0 00-1.71 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01" />
                    </svg>
                </div>
                <div class="ml-3 w-0 flex-1">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title-{{ $name }}">{{ $title }}</h3>
                    <div class="mt-2 text-sm text-gray-600">
                        {{ $message }}
                    </div>
                </div>
            </div>
        </div>
        <div class="px-6 pb-6 flex justify-end space-x-3">
            <button type="button"
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition"
                @click="open = false"
            >
                {{ $cancelText }}
            </button>
            <button type="button"
                class="inline-flex items-center px-4 py-2 {{ $variant === 'danger' ? 'bg-red-600 hover:bg-red-700 focus:ring-red-500' : 'bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500' }} border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition"
                @click="open = false; {{ $form ? "document.getElementById('$form')?.submit()" : '' }}"
            >
                {{ $confirmText }}
            </button>
        </div>
    </div>
</div>
