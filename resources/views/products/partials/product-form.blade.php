<div class="row">
    <div class="col-md-8">
        <div class="mb-3">
            <label for="name" class="form-label">Nama Produk *</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                   id="name" name="name" value="{{ old('name', $product->name ?? '') }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi Produk</label>
            <textarea class="form-control @error('description') is-invalid @enderror" 
                      id="description" name="description" rows="4">{{ old('description', $product->description ?? '') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="mb-3">
            <label for="price" class="form-label">Harga (Rp) *</label>
            <input type="number" class="form-control @error('price') is-invalid @enderror" 
                   id="price" name="price" value="{{ old('price', $product->price ?? '') }}" min="0" required>
            @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label">Kategori *</label>
            <select class="form-select @error('category_id') is-invalid @enderror" 
                    id="category_id" name="category_id" required>
                <option value="">Pilih Kategori</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" 
                        {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
                @endforeach
            </select>
            @error('category_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

@if(!isset($product) || $product->isPending())
<div class="mb-3">
    <label for="images" class="form-label">Gambar Produk *</label>
    <input type="file" class="form-control @error('images') is-invalid @enderror" 
           id="images" name="images[]" multiple accept="image/*" 
           {{ !isset($product) ? 'required' : '' }}>
    @error('images')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    @error('images.*')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
@endif