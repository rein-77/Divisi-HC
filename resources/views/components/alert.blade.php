@props(['type' => 'success', 'dismissible' => true, 'autoDismiss' => true, 'timeout' => 10000])

@php
    $classes = [
        'success' => 'bg-green-50 border-green-200 text-green-800',
        'error' => 'bg-red-50 border-red-200 text-red-800',
        'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
        'info' => 'bg-blue-50 border-blue-200 text-blue-800'
    ];
    
    $icons = [
        'success' => 'M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z',
        'error' => 'M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z',
        'warning' => 'M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z',
        'info' => 'M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z'
    ];
    
    $iconColors = [
        'success' => 'text-green-400',
        'error' => 'text-red-400',
        'warning' => 'text-yellow-400',
        'info' => 'text-blue-400'
    ];
    
    $closeButtonColors = [
        'success' => 'text-green-500 hover:text-green-600 focus:ring-green-500',
        'error' => 'text-red-500 hover:text-red-600 focus:ring-red-500',
        'warning' => 'text-yellow-500 hover:text-yellow-600 focus:ring-yellow-500',
        'info' => 'text-blue-500 hover:text-blue-600 focus:ring-blue-500'
    ];
@endphp

<div x-data="{ show: true }" 
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform scale-95"
     x-transition:enter-end="opacity-100 transform scale-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 transform scale-100"
     x-transition:leave-end="opacity-0 transform scale-95"
     @if($autoDismiss)
     x-init="setTimeout(() => show = false, {{ $timeout }})"
     @endif
     {{ $attributes->merge(['class' => 'border rounded-lg p-4 ' . ($classes[$type] ?? $classes['info'])]) }}>
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 {{ $iconColors[$type] ?? $iconColors['info'] }}" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="{{ $icons[$type] ?? $icons['info'] }}" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="ml-3 flex-1">
            {{ $slot }}
        </div>
        @if($dismissible)
        <div class="ml-auto pl-3">
            <div class="-mx-1.5 -my-1.5">
                <button type="button" 
                        @click="show = false"
                        class="inline-flex rounded-md p-1.5 {{ $closeButtonColors[$type] ?? $closeButtonColors['info'] }} hover:bg-opacity-20 focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors duration-200">
                    <span class="sr-only">Tutup</span>
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
        @endif
    </div>
</div>