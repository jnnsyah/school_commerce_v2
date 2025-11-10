<!-- resources/views/components/user/order-tracking.blade.php -->
@props(['order'])

@php
    $steps = [
        1 => ['name' => 'Menunggu Pembayaran', 'icon' => 'fa-clock'],
        2 => ['name' => 'Dibayar', 'icon' => 'fa-check-circle'],
        3 => ['name' => 'Diproses', 'icon' => 'fa-cogs'],
        4 => ['name' => 'Selesai', 'icon' => 'fa-flag-checkered'],
    ];
    
    $currentStep = $order->status_id;
@endphp

<div class="flex items-center space-x-2">
    @foreach($steps as $stepId => $step)
        <div class="flex items-center">
            <!-- Step Icon -->
            <div class="w-8 h-8 rounded-full flex items-center justify-center 
                @if($stepId < $currentStep) bg-green-500 text-white
                @elseif($stepId == $currentStep) bg-accent text-white
                @else bg-gray-200 dark:bg-slate-600 text-gray-500 dark:text-slate-400 @endif">
                <i class="fas {{ $step['icon'] }} text-xs"></i>
            </div>
            
            <!-- Step Line (except last) -->
            @if($stepId < count($steps))
                <div class="w-6 h-0.5 
                    @if($stepId < $currentStep) bg-green-500
                    @else bg-gray-200 dark:bg-slate-600 @endif">
                </div>
            @endif
        </div>
    @endforeach
</div>