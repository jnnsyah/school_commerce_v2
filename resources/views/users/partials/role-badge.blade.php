@php
    $roleColors = [
        'super_admin' => 'danger',
        'admin' => 'primary',
        'guru_pkwu' => 'info',
        'wali_kelas' => 'success', 
        'guru_biasa' => 'warning',
        'student' => 'secondary'
    ];
    
    $color = $roleColors[$user->getRoleName()] ?? 'dark';
@endphp

<span class="badge bg-{{ $color }}">
    {{ ucfirst(str_replace('_', ' ', $user->getRoleName())) }}
</span>