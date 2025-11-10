<!-- resources/views/admin/categories/partials/category-modal.blade.php - CREATE -->
<div id="category-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50" onclick="closeCategoryModal()"></div>
    
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-md">
        <div class="bg-white dark:bg-secondary rounded-lg shadow-lg border border-gray-200 dark:border-slate-700">
            <!-- Header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-slate-700">
                <h3 id="modal-title" class="text-lg font-semibold text-primary dark:text-light">Tambah Kategori</h3>
                <button onclick="closeCategoryModal()" class="text-gray-500 hover:text-gray-700 dark:text-slate-400">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Form -->
            <form id="category-form" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="_method" value="POST">

                <!-- Category Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        Nama Kategori <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                  focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                  bg-white dark:bg-secondary text-primary dark:text-light"
                           placeholder="Contoh: Makanan, Minuman, Kerajinan">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        Status
                    </label>
                    <select name="is_active" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                   focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                   bg-white dark:bg-secondary text-primary dark:text-light">
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" 
                            onclick="closeCategoryModal()"
                            class="px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-primary dark:text-light 
                                   hover:bg-gray-50 dark:hover:bg-slate-800 transition font-medium">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-accent text-white rounded-lg hover:bg-accent/90 transition font-medium">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>