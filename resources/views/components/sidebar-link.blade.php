@props(['active' => false, 'href' => '#'])

@php
$classes = ($active ?? false)
            ? 'flex items-center space-x-3 px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg transition-colors duration-200'
            : 'flex items-center space-x-3 px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-700 rounded-lg transition-colors duration-200';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>