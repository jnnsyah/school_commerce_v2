<!-- resources/views/components/admin/user-role-badge.blade.php -->
@props(['user'])

@php
    $roleConfig = [
        'super_admin' => [
            'class' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-300',
            'icon' => 'fa-crown'
        ],
        'admin' => [
            'class' => 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300',
            'icon' => 'fa-user-shield'
        ],
        'guru_pkwu' => [
            'class' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300',
            'icon' => 'fa-user-tie'
        ],
        'wali_kelas' => [
            'class' => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300',
            'icon' => 'fa-chalkboard-teacher'
        ],
        'guru_biasa' => [
            'class' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/20 dark:text-indigo-300',
            'icon' => 'fa-user-graduate'
        ],
        'student' => [
            'class' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/20 dark:text-orange-300',
            'icon' => 'fa-user-graduate'
        ]
    ];

    $userRole = $user->getRoleName();
    $config = $roleConfig[$userRole] ?? $roleConfig['student'];
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config['class'] }}">
    <i class="fas {{ $config['icon'] }} mr-1"></i>
    {{ $userRole }}
</span>