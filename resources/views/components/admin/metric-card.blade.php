<!-- resources/views/components/admin/metric-card.blade.php -->
@props(['title', 'value', 'icon', 'color' => 'blue', 'link' => null, 'linkText' => 'View Details'])

@php
    $colorClasses = [
        'blue' => ['bg' => 'bg-blue-500', 'light' => 'bg-blue-100', 'dark' => 'bg-blue-900/20'],
        'green' => ['bg' => 'bg-green-500', 'light' => 'bg-green-100', 'dark' => 'bg-green-900/20'],
        'yellow' => ['bg' => 'bg-yellow-500', 'light' => 'bg-yellow-100', 'dark' => 'bg-yellow-900/20'],
        'red' => ['bg' => 'bg-red-500', 'light' => 'bg-red-100', 'dark' => 'bg-red-900/20'],
        'purple' => ['bg' => 'bg-purple-500', 'light' => 'bg-purple-100', 'dark' => 'bg-purple-900/20'],
        'orange' => ['bg' => 'bg-orange-500', 'light' => 'bg-orange-100', 'dark' => 'bg-orange-900/20'],
        'indigo' => ['bg' => 'bg-indigo-500', 'light' => 'bg-indigo-100', 'dark' => 'bg-indigo-900/20'],
        'teal' => ['bg' => 'bg-teal-500', 'light' => 'bg-teal-100', 'dark' => 'bg-teal-900/20'],
    ];

    $color = $colorClasses[$color] ?? $colorClasses['blue'];
@endphp

<div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6 hover:shadow-md transition-shadow">
    <div class="flex items-start justify-between">
        <div class="flex-1">
            <p class="text-sm font-medium text-gray-600 dark:text-slate-400 mb-1">{{ $title }}</p>
            <p class="text-2xl font-bold text-primary dark:text-light mb-2">{{ $value }}</p>
            
            @if($link)
            <a href="{{ $link }}" 
               class="inline-flex items-center text-sm text-accent hover:text-accent/80 font-medium transition">
                {{ $linkText }}
                <i class="fas fa-arrow-right ml-1 text-xs"></i>
            </a>
            @endif
        </div>
        
        <div class="w-12 h-12 {{ $color['light'] }} dark:{{ $color['dark'] }} rounded-lg flex items-center justify-center ml-4">
            <i class="fas {{ $icon }} {{ $color['bg'] }} text-white text-lg"></i>
        </div>
    </div>
</div>