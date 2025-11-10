<!-- resources/views/admin/products/create.blade.php -->
@extends('layouts.admin-app')

@section('title', 'Tambah Produk - School Commerce')
@section('page-title', 'Tambah Produk Baru')
@section('page-subtitle', 'Buat listing produk baru untuk dijual')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700">
        <!-- Form Header -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700">
            <h3 class="text-lg font-semibold text-primary dark:text-light">Informasi Produk</h3>
            <p class="text-sm text-gray-600 dark:text-slate-400 mt-1">
                Isi detail produk dengan lengkap dan akurat
            </p>
        </div>

        <!-- Product Form -->
        <form action="/admin/products" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Product Name -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        Nama Produk <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           value="{{ old('name') }}"
                           required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                  focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                  bg-white dark:bg-secondary text-primary dark:text-light"
                           placeholder="Contoh: Brownies Coklat Special">
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
                           value="{{ old('price') }}"
                           required
                           min="0"
                           step="100"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                  focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                  bg-white dark:bg-secondary text-primary dark:text-light"
                           placeholder="Contoh: 15000">
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
                            {{ $class->grade->name }} {{ $class->major->short_name }} {{ $class->section->name }}
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
                          placeholder="Jelaskan detail produk, bahan-bahan, keunggulan, dll.">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Image Upload -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                    Gambar Produk <span class="text-red-500">*</span>
                </label>
                @include('components.admin.image-upload')
                @error('images')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('images.*')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Variants Section -->
            <div id="variants-section">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-semibold text-primary dark:text-light">Varian Produk</h4>
                    <button type="button" 
                            onclick="addVariant()"
                            class="flex items-center space-x-2 px-3 py-2 text-sm bg-blue-100 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-900/30 transition">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Varian</span>
                    </button>
                </div>
                
                <div id="variants-container" class="space-y-4">
                    <!-- Variants will be added here dynamically -->
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-slate-700">
                <a href="/admin/products" 
                   class="px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-primary dark:text-light 
                          hover:bg-gray-50 dark:hover:bg-slate-800 transition font-medium">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-accent text-white rounded-lg hover:bg-accent/90 transition font-medium">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Produk
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let variantCount = 0;

function addVariant() {
    variantCount++;
    const container = document.getElementById('variants-container');
    
    const variantHTML = `
        <div class="variant-item bg-gray-50 dark:bg-slate-800 rounded-lg p-4 border border-gray-200 dark:border-slate-700">
            <div class="flex items-center justify-between mb-3">
                <h5 class="font-medium text-primary dark:text-light">Varian ${variantCount}</h5>
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
                           name="variants[${variantCount}][name]" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm"
                           placeholder="Contoh: Size, Warna" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Harga (Rp)</label>
                    <input type="number" 
                           name="variants[${variantCount}][price]" 
                           min="0"
                           step="100"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm"
                           placeholder="15000" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Stok Awal</label>
                    <input type="number" 
                           name="variants[${variantCount}][stock_at]" 
                           min="0"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm"
                           placeholder="10" required>
                </div>
            </div>
            
            <div class="mt-3">
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Opsi Varian</label>
                <div class="space-y-2" id="variant-options-${variantCount}">
                    <div class="flex space-x-2">
                        <input type="text" 
                               name="variants[${variantCount}][options][0][name]" 
                               class="flex-1 px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm"
                               placeholder="Nama opsi (Contoh: Small)" required>
                        <input type="text" 
                               name="variants[${variantCount}][options][0][value]" 
                               class="flex-1 px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm"
                               placeholder="Nilai opsi (Contoh: S)" required>
                    </div>
                </div>
                <button type="button" 
                        onclick="addVariantOption(${variantCount})"
                        class="mt-2 text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                    + Tambah Opsi
                </button>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', variantHTML);
}

function removeVariant(button) {
    button.closest('.variant-item').remove();
    updateVariantNumbers();
}

function addVariantOption(variantId) {
    const container = document.getElementById(`variant-options-${variantId}`);
    const optionCount = container.children.length;
    
    const optionHTML = `
        <div class="flex space-x-2">
            <input type="text" 
                   name="variants[${variantId}][options][${optionCount}][name]" 
                   class="flex-1 px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm"
                   placeholder="Nama opsi" required>
            <input type="text" 
                   name="variants[${variantId}][options][${optionCount}][value]" 
                   class="flex-1 px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm"
                   placeholder="Nilai opsi" required>
            <button type="button" 
                    onclick="removeVariantOption(this)"
                    class="px-2 text-red-500 hover:text-red-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', optionHTML);
}

function removeVariantOption(button) {
    button.closest('div').remove();
}

function updateVariantNumbers() {
    const variants = document.querySelectorAll('.variant-item');
    variants.forEach((variant, index) => {
        const title = variant.querySelector('h5');
        title.textContent = `Varian ${index + 1}`;
    });
    variantCount = variants.length;
}
</script>
@endsection