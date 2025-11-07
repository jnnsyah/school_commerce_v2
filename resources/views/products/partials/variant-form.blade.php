<div class="card mb-3">
    <div class="card-header">
        <h6 class="mb-0">Varian Produk</h6>
    </div>
    <div class="card-body">
        <div id="variants-container">
            <!-- Variants will be added here dynamically -->
        </div>
        <button type="button" class="btn btn-sm btn-outline-primary" id="add-variant">
            <i class="fas fa-plus me-1"></i>Tambah Varian
        </button>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const variantsContainer = document.getElementById('variants-container');
    const addVariantBtn = document.getElementById('add-variant');
    let variantCount = 0;

    addVariantBtn.addEventListener('click', function() {
        variantCount++;
        const variantHtml = `
            <div class="variant-item border p-3 mb-3 rounded">
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Nama Varian *</label>
                        <input type="text" class="form-control" name="variants[${variantCount}][name]" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Harga (Rp) *</label>
                        <input type="number" class="form-control" name="variants[${variantCount}][price]" min="0" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Stok Awal *</label>
                        <input type="number" class="form-control" name="variants[${variantCount}][stock]" min="0" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="button" class="btn btn-sm btn-danger w-100 remove-variant">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <label class="form-label">Opsi Varian</label>
                        <div class="options-container">
                            <div class="option-item row g-2 mb-2">
                                <div class="col-md-5">
                                    <input type="text" class="form-control" name="variants[${variantCount}][options][0][name]" placeholder="Nama opsi (e.g., Size)">
                                </div>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" name="variants[${variantCount}][options][0][value]" placeholder="Value (e.g., L)">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-sm btn-outline-danger w-100 remove-option">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary add-option">
                            <i class="fas fa-plus me-1"></i>Tambah Opsi
                        </button>
                    </div>
                </div>
            </div>
        `;
        variantsContainer.insertAdjacentHTML('beforeend', variantHtml);
    });

    variantsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-variant')) {
            e.target.closest('.variant-item').remove();
        }
        
        if (e.target.classList.contains('remove-option')) {
            e.target.closest('.option-item').remove();
        }
        
        if (e.target.classList.contains('add-option')) {
            const variantItem = e.target.closest('.variant-item');
            const optionsContainer = variantItem.querySelector('.options-container');
            const optionCount = optionsContainer.children.length;
            
            const optionHtml = `
                <div class="option-item row g-2 mb-2">
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="variants[${variantCount}][options][${optionCount}][name]" placeholder="Nama opsi">
                    </div>
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="variants[${variantCount}][options][${optionCount}][value]" placeholder="Value">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-sm btn-outline-danger w-100 remove-option">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
            optionsContainer.insertAdjacentHTML('beforeend', optionHtml);
        }
    });
});
</script>
@endpush