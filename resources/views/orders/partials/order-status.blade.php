@php
    $statusColors = [
        'pending' => 'warning',
        'paid' => 'info',
        'processing' => 'primary', 
        'completed' => 'success',
        'cancelled' => 'danger'
    ];
    
    $color = $statusColors[$order->status->name] ?? 'secondary';
    $icons = [
        'pending' => 'clock',
        'paid' => 'credit-card',
        'processing' => 'cog',
        'completed' => 'check-circle',
        'cancelled' => 'times-circle'
    ];
    $icon = $icons[$order->status->name] ?? 'question-circle';
@endphp

<span class="badge bg-{{ $color }}">
    <i class="fas fa-{{ $icon }} me-1"></i>
    {{ ucfirst($order->status->name) }}
</span>