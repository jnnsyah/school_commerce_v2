<div class="card shadow mb-4">
    @if(isset($title))
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            @if(isset($icon))<i class="{{ $icon }} me-2"></i>@endif
            {{ $title }}
        </h6>
    </div>
    @endif
    <div class="card-body">
        {{ $slot }}
    </div>
    @if(isset($footer))
    <div class="card-footer">
        {{ $footer }}
    </div>
    @endif
</div>