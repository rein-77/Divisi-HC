@props(['title'])

<div {{ $attributes->merge(['class' => 'space-y-6']) }}>
    @if(isset($title))
        <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $title }}</h3>
            {{ $slot }}
        </div>
    @else
        {{ $slot }}
    @endif
</div>