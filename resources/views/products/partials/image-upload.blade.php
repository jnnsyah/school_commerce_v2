<div class="mb-3">
    <label for="images" class="form-label">Gambar Produk</label>
    <input type="file" class="form-control @error('images') is-invalid @enderror" 
           id="images" name="images[]" multiple accept="image/*"
           {{ $required ?? false ? 'required' : '' }}>
    @error('images')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <div class="form-text">
        Format: JPG, PNG, GIF. Maksimal 2MB per gambar.
        @if($required ?? false) Minimal 1 gambar. @endif
    </div>
</div>

<!-- Image Preview -->
<div id="image-preview" class="row mt-2"></div>

@push('scripts')
<script>
document.getElementById('images').addEventListener('change', function(e) {
    const preview = document.getElementById('image-preview');
    preview.innerHTML = '';
    
    const files = e.target.files;
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const col = document.createElement('div');
            col.className = 'col-md-3 mb-3';
            col.innerHTML = `
                <div class="card">
                    <img src="${e.target.result}" class="card-img-top" style="height: 100px; object-fit: cover;">
                    <div class="card-body p-2">
                        <small class="text-muted d-block">${file.name}</small>
                        <small class="text-muted">${(file.size / 1024 / 1024).toFixed(2)} MB</small>
                    </div>
                </div>
            `;
            preview.appendChild(col);
        }
        
        reader.readAsDataURL(file);
    }
});
</script>
@endpush