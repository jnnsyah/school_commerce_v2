<!-- resources/views/admin/inventory/partials/stock-adjustment-modal.blade.php -->
<div id="stock-adjustment-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50" onclick="closeAdjustmentModal()"></div>
    
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-md">
        <div class="bg-white dark:bg-secondary rounded-lg shadow-lg border border-gray-200 dark:border-slate-700">
            <!-- Header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-slate-700">
                <h3 id="modal-title" class="text-lg font-semibold text-primary dark:text-light">Sesuaikan Stok</h3>
                <button onclick="closeAdjustmentModal()" class="text-gray-500 hover:text-gray-700 dark:text-slate-400">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Form -->
            <form id="stock-adjustment-form" method="POST" class="p-6 space-y-4">
                @csrf
                
                <!-- Current Stock Info -->
                <div class="bg-gray-50 dark:bg-slate-800 rounded-lg p-3">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600 dark:text-slate-400">Stok Saat Ini:</span>
                        <span id="current-stock" class="font-semibold text-primary dark:text-light">0</span>
                    </div>
                </div>

                <!-- Adjustment Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        Jenis Penyesuaian <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative flex cursor-pointer">
                            <input type="radio" name="adjustment_type" value="add" checked class="peer sr-only">
                            <div class="flex-1 p-3 border border-gray-300 dark:border-slate-600 rounded-lg text-center 
                                      peer-checked:border-accent peer-checked:bg-accent/10 transition">
                                <i class="fas fa-plus text-green-500 mb-1"></i>
                                <p class="text-sm font-medium">Tambah Stok</p>
                            </div>
                        </label>
                        <label class="relative flex cursor-pointer">
                            <input type="radio" name="adjustment_type" value="subtract" class="peer sr-only">
                            <div class="flex-1 p-3 border border-gray-300 dark:border-slate-600 rounded-lg text-center 
                                      peer-checked:border-accent peer-checked:bg-accent/10 transition">
                                <i class="fas fa-minus text-red-500 mb-1"></i>
                                <p class="text-sm font-medium">Kurangi Stok</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Quantity -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        Jumlah <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           name="quantity" 
                           required
                           min="1"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                  focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                  bg-white dark:bg-secondary text-primary dark:text-light"
                           placeholder="Masukkan jumlah">
                </div>

                <!-- Note -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        Catatan
                    </label>
                    <textarea name="note" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                     focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                     bg-white dark:bg-secondary text-primary dark:text-light"
                              placeholder="Alasan penyesuaian stok (opsional)"></textarea>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" 
                            onclick="closeAdjustmentModal()"
                            class="px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-primary dark:text-light 
                                   hover:bg-gray-50 dark:hover:bg-slate-800 transition font-medium">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-accent text-white rounded-lg hover:bg-accent/90 transition font-medium">
                        Simpan Penyesuaian
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>