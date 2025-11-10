<!-- resources/views/components/admin/approval-badge.blade.php -->
@props(['status'])

@php
    $statusConfig = [
        'pending' => [
            'class' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300',
            'icon' => 'fa-clock'
        ],
        'approved' => [
            'class' => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300', 
            'icon' => 'fa-check-circle'
        ],
        'rejected' => [
            'class' => 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300',
            'icon' => 'fa-times-circle'
        ]
    ];

    $config = $statusConfig[strtolower($status->name)] ?? $statusConfig['pending'];
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config['class'] }}">
    <i class="fas {{ $config['icon'] }} mr-1"></i>
    {{ $status->name }}
</span>