<!-- resources/views/admin/categories/index.blade.php - CREATE -->
@extends('layouts.admin-app')

@section('title', 'Kelola Kategori - School Commerce')
@section('page-title', 'Kelola Kategori Produk')
@section('page-subtitle', 'Tambah dan kelola kategori untuk produk')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-semibold text-primary dark:text-light">Daftar Kategori</h2>
            <p class="text-sm text-gray-600 dark:text-slate-400 mt-1">
                Kelola kategori untuk mengorganisir produk
            </p>
        </div>
        
        <button onclick="openCategoryModal()"
                class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-accent text-white rounded-lg hover:bg-accent/90 transition font-medium">
            <i class="fas fa-plus mr-2"></i>
            Tambah Kategori
        </button>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($categories as $category)
        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-primary dark:text-light">{{ $category->name }}</h3>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                    {{ $category->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-300' }}">
                    {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>

            <div class="space-y-2 text-sm text-gray-600 dark:text-slate-400">
                <div class="flex justify-between">
                    <span>Total Produk:</span>
                    <span class="font-medium text-primary dark:text-light">
                        {{ $category->products_count ?? 0 }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span>Dibuat:</span>
                    <span>{{ $category->created_at->format('d M Y') }}</span>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-2 mt-4 pt-4 border-t border-gray-200 dark:border-slate-700">
                <button onclick="editCategory({{ $category->id }}, '{{ $category->name }}', {{ $category->is_active ? 'true' : 'false' }})"
                        class="px-3 py-1 text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300 text-sm">
                    <i class="fas fa-edit"></i>
                </button>
                
                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')"
                            class="px-3 py-1 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-sm">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>

                <form action="{{ route('admin.categories.toggle-status', $category) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                            class="px-3 py-1 {{ $category->is_active ? 'text-orange-600 hover:text-orange-800' : 'text-green-600 hover:text-green-800' }} text-sm">
                        <i class="fas {{ $category->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <i class="fas fa-tags text-4xl text-gray-400 dark:text-slate-600 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 dark:text-light mb-2">Belum ada kategori</h3>
            <p class="text-gray-500 dark:text-slate-400 mb-4">Mulai dengan membuat kategori pertama</p>
            <button onclick="openCategoryModal()"
                    class="inline-flex items-center px-4 py-2 bg-accent text-white rounded-lg hover:bg-accent/90 transition">
                <i class="fas fa-plus mr-2"></i>
                Tambah Kategori
            </button>
        </div>
        @endforelse
    </div>
</div>

<!-- Category Modal -->
@include('admin.categories.partials.category-modal')

<script>
function openCategoryModal(categoryId = null, categoryName = '', isActive = true) {
    const modal = document.getElementById('category-modal');
    const form = document.getElementById('category-form');
    const title = document.getElementById('modal-title');
    
    if (categoryId) {
        // Edit mode
        title.textContent = 'Edit Kategori';
        form.action = `/admin/categories/${categoryId}`;
        form.querySelector('input[name="_method"]').value = 'PUT';
        form.querySelector('input[name="name"]').value = categoryName;
        form.querySelector('select[name="is_active"]').value = isActive ? '1' : '0';
    } else {
        // Create mode
        title.textContent = 'Tambah Kategori';
        form.action = '/admin/categories';
        form.querySelector('input[name="_method"]').value = 'POST';
        form.querySelector('input[name="name"]').value = '';
        form.querySelector('select[name="is_active"]').value = '1';
    }
    
    modal.classList.remove('hidden');
}

function closeCategoryModal() {
    document.getElementById('category-modal').classList.add('hidden');
}

function editCategory(id, name, isActive) {
    openCategoryModal(id, name, isActive);
}

// Close modal when clicking outside
document.getElementById('category-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCategoryModal();
    }
});
</script>
@endsection