<form action="{{ $action }}" method="POST">
    @csrf
    @if(isset($method))
        @method($method)
    @endif

    <div class="mb-3">
        <label class="form-label">Jenis Penyesuaian *</label>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="adjustment_type" 
                   id="add_stock" value="add" checked>
            <label class="form-check-label" for="add_stock">
                Tambah Stok
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="adjustment_type" 
                   id="subtract_stock" value="subtract">
            <label class="form-check-label" for="subtract_stock">
                Kurangi Stok
            </label>
        </div>
    </div>

    <div class="mb-3">
        <label for="quantity" class="form-label">Jumlah *</label>
        <input type="number" class="form-control" id="quantity" name="quantity" 
               min="1" max="1000" value="1" required>
    </div>

    <div class="mb-3">
        <label for="note" class="form-label">Keterangan *</label>
        <textarea class="form-control" id="note" name="note" rows="3" 
                  placeholder="Contoh: Stok awal, koreksi, pengembalian, dll." required></textarea>
    </div>

    @if(isset($product) && $product->variants->count() > 0)
    <div class="mb-3">
        <label for="variant_id" class="form-label">Pilih Variant (Opsional)</label>
        <select class="form-select" id="variant_id" name="variant_id">
            <option value="">Semua Variant</option>
            @foreach($product->variants as $variant)
            <option value="{{ $variant->id }}">
                {{ $variant->name }} (Stok: {{ $variant->stock_at }})
            </option>
            @endforeach
        </select>
    </div>
    @endif

    @if(isset($product) && $product->extras->count() > 0)
    <div class="mb-3">
        <label for="extra_id" class="form-label">Pilih Extra (Opsional)</label>
        <select class="form-select" id="extra_id" name="extra_id">
            <option value="">Semua Extra</option>
            @foreach($product->extras as $extra)
            <option value="{{ $extra->id }}">
                {{ $extra->name }} (Stok: {{ $extra->stock_cache }})
            </option>
            @endforeach
        </select>
    </div>
    @endif

    <div class="d-grid">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i>Simpan Penyesuaian Stok
        </button>
    </div>
</form>