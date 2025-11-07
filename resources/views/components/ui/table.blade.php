<div class="table-responsive">
    <table class="table table-bordered table-hover {{ $class ?? '' }}">
        @if(isset($thead))
        <thead class="table-{{ $theadColor ?? 'primary' }}">
            {{ $thead }}
        </thead>
        @endif
        <tbody>
            {{ $slot }}
        </tbody>
        @if(isset($tfoot))
        <tfoot>
            {{ $tfoot }}
        </tfoot>
        @endif
    </table>
</div>