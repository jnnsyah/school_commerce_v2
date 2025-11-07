@php
    $statusColors = [
        'pending' => 'warning',
        'approved' => 'success', 
        'rejected' => 'danger'
    ];
    
    $color = $statusColors[$status->name] ?? 'secondary';
    $icons = [
        'pending' => 'clock',
        'approved' => 'check-circle',
        'rejected' => 'times-circle'
    ];
    $icon = $icons[$status->name] ?? 'question-circle';
@endphp

<span class="badge bg-{{ $color }}">
    <i class="fas fa-{{ $icon }} me-1"></i>
    {{ ucfirst($status->name) }}
</span>