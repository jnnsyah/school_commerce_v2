<!-- resources/views/components/user/filter-sidebar.blade.php -->
<div id="filter-sidebar" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50">
    <div class="absolute right-0 top-0 bottom-0 w-80 bg-white dark:bg-secondary shadow-xl">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-slate-700">
            <h3 class="text-lg font-semibold text-primary dark:text-light">Filter Produk</h3>
            <button onclick="toggleFilter()" class="text-gray-500 hover:text-gray-700 dark:text-slate-400">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Filter Content -->
        <div class="p-4 space-y-6 overflow-y-auto max-h-[calc(100vh-120px)]">
            <!-- Categories Filter -->
            <div>
                <h4 class="font-medium text-primary dark:text-light mb-3">Kategori</h4>
                <div class="space-y-2">
                    @foreach($categories as $category)
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" 
                               name="category_id" 
                               value="{{ $category->id }}"
                               {{ request('category_id') == $category->id ? 'checked' : '' }}
                               class="rounded border-gray-300 text-accent focus:ring-accent">
                        <span class="text-sm text-gray-700 dark:text-slate-300">{{ $category->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Sellers/Classes Filter -->
            <div>
                <h4 class="font-medium text-primary dark:text-light mb-3">Penjual</h4>
                <div class="space-y-2">
                    @foreach($classes as $class)
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" 
                               name="class_id" 
                               value="{{ $class->class_id }}"
                               {{ request('class_id') == $class->class_id ? 'checked' : '' }}
                               class="rounded border-gray-300 text-accent focus:ring-accent">
                        <span class="text-sm text-gray-700 dark:text-slate-300">
                            {{ $class->grade->name }} {{ $class->major->short_name }} {{ $class->section->name }}
                        </span>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Price Range Filter -->
            <div>
                <h4 class="font-medium text-primary dark:text-light mb-3">Rentang Harga</h4>
                <div class="space-y-3">
                    <div class="flex space-x-3">
                        <input type="number" 
                               name="min_price" 
                               value="{{ request('min_price') }}"
                               placeholder="Min" 
                               class="flex-1 px-3 py-2 border border-gray-300 dark:border-slate-600 rounded 
                                      text-sm text-primary dark:text-light bg-white dark:bg-secondary">
                        <input type="number" 
                               name="max_price" 
                               value="{{ request('max_price') }}"
                               placeholder="Max" 
                               class="flex-1 px-3 py-2 border border-gray-300 dark:border-slate-600 rounded 
                                      text-sm text-primary dark:text-light bg-white dark:bg-secondary">
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200 dark:border-slate-700 bg-white dark:bg-secondary">
            <div class="flex space-x-3">
                <button onclick="clearFilters()"
                        class="flex-1 px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                               text-primary dark:text-light hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                    Reset
                </button>
                <button onclick="applyFilters()"
                        class="flex-1 px-4 py-2 bg-accent text-white rounded-lg hover:bg-accent/90 transition">
                    Terapkan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function applyFilters() {
    const form = document.createElement('form');
    form.method = 'GET';
    form.action = window.location.pathname;

    // Add search parameter
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput && searchInput.value) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'search';
        input.value = searchInput.value;
        form.appendChild(input);
    }

    // Add category filters
    document.querySelectorAll('input[name="category_id"]:checked').forEach(checkbox => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'category_id';
        input.value = checkbox.value;
        form.appendChild(input);
    });

    // Add class filters
    document.querySelectorAll('input[name="class_id"]:checked').forEach(checkbox => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'class_id';
        input.value = checkbox.value;
        form.appendChild(input);
    });

    // Add price filters
    const minPrice = document.querySelector('input[name="min_price"]');
    const maxPrice = document.querySelector('input[name="max_price"]');
    
    if (minPrice && minPrice.value) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'min_price';
        input.value = minPrice.value;
        form.appendChild(input);
    }
    
    if (maxPrice && maxPrice.value) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'max_price';
        input.value = maxPrice.value;
        form.appendChild(input);
    }

    document.body.appendChild(form);
    form.submit();
}

function clearFilters() {
    window.location.href = window.location.pathname;
}
</script>