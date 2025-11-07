@extends('layouts.app')

@section('title', 'Tambah Produk')
@section('page-title', 'Tambah Produk Baru')

@section('content')
<div class="row">
    <div class="col-12">
        @include('components.ui.card', [
            'title' => 'Form Tambah Produk',
            'icon' => 'fas fa-plus'
        ])
            @can('product.create')
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
                    @csrf
                    
                    <!-- Class Info (hidden, auto-filled for wali_kelas) -->
                    <input type="hidden" name="class_id" value="{{ $class->class_id }}">
                    
                    <div class="row">
                        <div class="col-md-8">
                            <!-- Basic Information -->
                            <div class="mb-4">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Informasi Dasar
                                </h5>
                                
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label for="name" class="form-label">Nama Produk *</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-12 mb-3">
                                        <label for="description" class="form-label">Deskripsi Produk</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                  id="description" name="description" rows="4">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Deskripsi maksimal 1000 karakter.</div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="price" class="form-label">Harga (Rp) *</label>
                                        <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                               id="price" name="price" value="{{ old('price') }}" min="0" required>
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="category_id" class="form-label">Kategori *</label>
                                        <select class="form-select @error('category_id') is-invalid @enderror" 
                                                id="category_id" name="category_id" required>
                                            <option value="">Pilih Kategori</option>
                                            @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                        </div>
                        
                        <div class="col-md-4">
                            <!-- Class Info & Images -->
                            <div class="mb-4">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-chalkboard me-2"></i>Informasi Kelas
                                </h5>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $class->getFullName() }}</h6>
                                        <p class="card-text small text-muted mb-1">
                                            Wali Kelas: {{ $class->teacher->name }}
                                        </p>
                                        <p class="card-text small text-muted">
                                            Jumlah Siswa: {{ $class->getStudentCount() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Product Images -->
                            <div class="mb-4">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-images me-2"></i>Gambar Produk
                                </h5>
                                
                                <div class="mb-3">
                                    <label for="images" class="form-label">Upload Gambar *</label>
                                    <input type="file" class="form-control @error('images') is-invalid @enderror" 
                                           id="images" name="images[]" multiple accept="image/*" required>
                                    @error('images')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @error('images.*')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        Upload minimal 1 gambar. Format: JPG, PNG, GIF. Maksimal 2MB per gambar.
                                    </div>
                                </div>
                                
                                <!-- Image Preview -->
                                <div id="imagePreview" class="row g-2 mt-2"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('products.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-1"></i>Submit untuk Approval
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            @else
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Anda tidak memiliki izin untuk membuat produk. Hanya wali kelas yang dapat membuat produk.
                </div>
            @endcan
        @endcomponent
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Image preview functionality
    $('#images').change(function() {
        $('#imagePreview').empty();
        const files = this.files;
        
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();
            
            reader.onload = function(e) {
                $('#imagePreview').append(`
                    <div class="col-6">
                        <div class="card">
                            <img src="${e.target.result}" class="card-img-top" style="height: 100px; object-fit: cover;">
                            <div class="card-body p-2">
                                <small class="text-muted">${file.name}</small>
                            </div>
                        </div>
                    </div>
                `);
            }
            
            reader.readAsDataURL(file);
        }
    });
    
    // Form validation
    $('#productForm').submit(function() {
        const price = $('#price').val();
        if (price < 0) {
            alert('Harga tidak boleh negatif');
            return false;
        }
        return true;
    });
});
</script>
@endpush