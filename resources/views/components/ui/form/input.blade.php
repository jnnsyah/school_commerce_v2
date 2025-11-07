<div class="mb-3">
    <label for="{{ $name }}" class="form-label">{{ $label }} @if(isset($required) && $required)*@endif</label>
    <input type="{{ $type ?? 'text' }}" 
           class="form-control @error($name) is-invalid @enderror" 
           id="{{ $name }}" 
           name="{{ $name }}" 
           value="{{ old($name, $value ?? '') }}"
           @if(isset($placeholder)) placeholder="{{ $placeholder }}" @endif
           @if(isset($required) && $required) required @endif
           @foreach($attributes ?? [] as $key => $value) {{ $key }}="{{ $value }}" @endforeach>
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    @if(isset($help))
    <div class="form-text">{{ $help }}</div>
    @endif
</div>