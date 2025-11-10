<!-- resources/views/admin/products/edit.blade.php -->
@extends('layouts.admin-app')

@section('title', 'Edit Produk - School Commerce')
@section('page-title', 'Edit Produk')
@section('page-subtitle', 'Update informasi produk')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700">
        <!-- Form Header -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-primary dark:text-light">Edit Produk</h3>
                    <p class="text-sm text-gray-600 dark:text-slate-400 mt-1">
                        Update detail produk {{ $product->name }}
                    </p>
                </div>
                <div class="flex items-center space-x-2">
                    @include('components.admin.approval-badge', ['status' => $product->status])
                </div>
            </div>
        </div>

        <!-- Product Form -->
        <form action="/admin/products/{{ $product->product_id }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Product Name -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        Nama Produk <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           value="{{ old('name', $product->name) }}"
                           required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                  focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                  bg-white dark:bg-secondary text-primary dark:text-light">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select name="category_id" 
                            required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                bg-white dark:bg-secondary text-primary dark:text-light">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Price -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        Harga (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           name="price" 
                           value="{{ old('price', $product->price) }}"
                           required
                           min="0"
                           step="100"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                  focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                  bg-white dark:bg-secondary text-primary dark:text-light">
                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Class Info (Readonly) -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        Kelas Penjual
                    </label>
                    <div class="px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-800">
                        <p class="text-sm text-gray-700 dark:text-slate-300">
                            {{ $product->class->grade->name }} {{ $product->class->major->short_name }} {{ $product->class->section->name }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                    Deskripsi Produk <span class="text-red-500">*</span>
                </label>
                <textarea name="description" 
                          rows="4"
                          required
                          class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                 focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                 bg-white dark:bg-secondary text-primary dark:text-light"
                          placeholder="Jelaskan detail produk, bahan-bahan, keunggulan, dll.">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Current Images -->
            @if($product->images->count() > 0)
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                    Gambar Saat Ini
                </label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($product->images as $image)
                    <div class="relative group">
                        <img src="{{ asset('storage/' . $image->file_path) }}" 
                             alt="Product Image" 
                             class="w-full h-24 object-cover rounded-lg">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition rounded-lg flex items-center justify-center">
                            <button type="button" 
                                    onclick="removeExistingImage({{ $image->id }})"
                                    class="text-white opacity-0 group-hover:opacity-100 transition">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- New Image Upload -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                    Tambah Gambar Baru
                </label>
                @include('components.admin.image-upload')
            </div>

            <!-- Existing Variants -->
            @if($product->variants->count() > 0)
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-semibold text-primary dark:text-light">Varian Saat Ini</h4>
                </div>
                
                <div class="space-y-4">
                    @foreach($product->variants as $variant)
                    <div class="bg-gray-50 dark:bg-slate-800 rounded-lg p-4 border border-gray-200 dark:border-slate-700">
                        <div class="flex items-center justify-between mb-3">
                            <h5 class="font-medium text-primary dark:text-light">{{ $variant->name }}</h5>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-1 bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-300 text-xs rounded-full">
                                    Stok: {{ $variant->stock_at }}
                                </span>
                                <button type="button" 
                                        onclick="removeVariant({{ $variant->id }})"
                                        class="text-red-500 hover:text-red-700 transition">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Harga (Rp)</label>
                                <input type="number" 
                                       name="existing_variants[{{ $variant->id }}][price]" 
                                       value="{{ $variant->price }}"
                                       min="0"
                                       step="100"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Stok</label>
                                <input type="number" 
                                       name="existing_variants[{{ $variant->id }}][stock_at]" 
                                       value="{{ $variant->stock_at }}"
                                       min="0"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Status</label>
                                <select name="existing_variants[{{ $variant->id }}][status_id]"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm">
                                    <option value="1" {{ $variant->status_id == 1 ? 'selected' : '' }}>Aktif</option>
                                    <option value="2" {{ $variant->status_id == 2 ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- New Variants Section -->
            <div id="variants-section">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-semibold text-primary dark:text-light">Tambah Varian Baru</h4>
                    <button type="button" 
                            onclick="addVariant()"
                            class="flex items-center space-x-2 px-3 py-2 text-sm bg-blue-100 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-900/30 transition">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Varian</span>
                    </button>
                </div>
                
                <div id="variants-container" class="space-y-4">
                    <!-- New variants will be added here dynamically -->
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-slate-700">
                <a href="/admin/products" 
                   class="px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-primary dark:text-light 
                          hover:bg-gray-50 dark:hover:bg-slate-800 transition font-medium">
                    Batal
                </a>
                <div class="flex space-x-3">
                    <button type="submit" 
                            name="action"
                            value="update"
                            class="px-6 py-2 bg-accent text-white rounded-lg hover:bg-accent/90 transition font-medium">
                        <i class="fas fa-save mr-2"></i>
                        Update Produk
                    </button>
                    
                    @if($product->isApproved() && auth()->user()->can('product.approve'))
                    <button type="submit" 
                            name="action"
                            value="update_and_approve"
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                        <i class="fas fa-check-circle mr-2"></i>
                        Update & Setujui
                    </button>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Variant management scripts (same as create form)
let variantCount = 0;

function addVariant() {
    variantCount++;
    const container = document.getElementById('variants-container');
    
    const variantHTML = `
        <div class="variant-item bg-gray-50 dark:bg-slate-800 rounded-lg p-4 border border-gray-200 dark:border-slate-700">
            <div class="flex items-center justify-between mb-3">
                <h5 class="font-medium text-primary dark:text-light">Varian Baru ${variantCount}</h5>
                <button type="button" 
                        onclick="removeVariant(this)"
                        class="text-red-500 hover:text-red-700 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Nama Varian</label>
                    <input type="text" 
                           name="new_variants[${variantCount}][name]" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm"
                           placeholder="Contoh: Size, Warna" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Harga (Rp)</label>
                    <input type="number" 
                           name="new_variants[${variantCount}][price]" 
                           min="0"
                           step="100"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm"
                           placeholder="15000" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Stok Awal</label>
                    <input type="number" 
                           name="new_variants[${variantCount}][stock_at]" 
                           min="0"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm"
                           placeholder="10" required>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', variantHTML);
}

function removeVariant(button) {
    button.closest('.variant-item').remove();
}

function removeExistingImage(imageId) {
    if (confirm('Apakah Anda yakin ingin menghapus gambar ini?')) {
        // AJAX request to remove image
        fetch(`/admin/products/images/${imageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal menghapus gambar');
        });
    }
}
</script>
@endsection