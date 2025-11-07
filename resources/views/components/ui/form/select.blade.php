<div class="mb-3">
    <label for="{{ $name }}" class="form-label">{{ $label }} @if(isset($required) && $required)*@endif</label>
    <select class="form-select @error($name) is-invalid @enderror" 
            id="{{ $name }}" 
            name="{{ $name }}"
            @if(isset($required) && $required) required @endif>
        @if(isset($placeholder))
        <option value="">{{ $placeholder }}</option>
        @endif
        @foreach($options as $value => $text)
        <option value="{{ $value }}" 
                {{ old($name, $selected ?? '') == $value ? 'selected' : '' }}>
            {{ $text }}
        </option>
        @endforeach
    </select>
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    @if(isset($help))
    <div class="form-text">{{ $help }}</div>
    @endif
</div>