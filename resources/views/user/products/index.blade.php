<!-- resources/views/user/products/index.blade.php -->
@extends('layouts.user-app')

@section('title', 'Products - School Commerce')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-primary dark:text-light mb-2">Semua Produk</h1>
        <p class="text-gray-600 dark:text-slate-400">Temukan produk terbaik dari teman sekelasmu</p>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white dark:bg-secondary rounded-lg p-4 mb-6 shadow-sm border border-gray-200 dark:border-slate-700">
        <div class="flex flex-col sm:flex-row gap-4">
            <!-- Search Input -->
            <div class="flex-1">
                <form action="{{ url()->current() }}" method="GET" class="flex gap-2">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Cari produk..." 
                           class="flex-1 px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                  focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                  bg-white dark:bg-secondary text-primary dark:text-light">
                    <button type="submit" 
                            class="bg-accent text-white px-4 py-2 rounded-lg hover:bg-accent/90 transition">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            <!-- Filter Button -->
            <button onclick="toggleFilter()"
                    class="flex items-center space-x-2 px-4 py-2 border border-gray-300 dark:border-slate-600 
                           rounded-lg text-primary dark:text-light hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                <i class="fas fa-filter"></i>
                <span>Filter</span>
            </button>
        </div>

        <!-- Active Filters -->
        @if(request()->has('search') || request()->has('category_id') || request()->has('class_id'))
        <div class="mt-4 flex flex-wrap gap-2">
            @if(request('search'))
            <span class="bg-accent/10 text-accent px-3 py-1 rounded-full text-sm flex items-center space-x-1">
                <span>Search: "{{ request('search') }}"</span>
                <a href="{{ remove_query_param('search') }}" class="hover:text-accent/70">
                    <i class="fas fa-times"></i>
                </a>
            </span>
            @endif
            <!-- Add more active filter badges here -->
            <a href="{{ url()->current() }}" class="text-sm text-accent hover:text-accent/70">
                Clear All
            </a>
        </div>
        @endif
    </div>

    <!-- Filter Sidebar (Hidden by default) -->
    @include('components.user.filter-sidebar')

    <!-- Products Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
        @forelse($products as $product)
            @include('components.user.product-card', ['product' => $product])
        @empty
            <div class="col-span-full text-center py-12">
                <i class="fas fa-store text-4xl text-gray-400 dark:text-slate-600 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 dark:text-light mb-2">Tidak ada produk ditemukan</h3>
                <p class="text-gray-500 dark:text-slate-400">Coba ubah filter pencarian Anda</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
    <div class="flex justify-center">
        {{ $products->links('components.shared.pagination') }}
    </div>
    @endif
</div>

<script>
function toggleFilter() {
    const sidebar = document.getElementById('filter-sidebar');
    sidebar.classList.toggle('hidden');
}

// Close filter when clicking outside
document.addEventListener('click', function(e) {
    const filterBtn = document.querySelector('[onclick="toggleFilter()"]');
    const sidebar = document.getElementById('filter-sidebar');
    
    if (!sidebar.contains(e.target) && !filterBtn.contains(e.target)) {
        sidebar.classList.add('hidden');
    }
});
</script>
@endsection