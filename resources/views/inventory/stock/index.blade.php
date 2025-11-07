@extends('layouts.app')

@section('title', 'Manajemen Stok')
@section('page-title', 'Manajemen Stok Produk')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Products Table -->
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-boxes me-2"></i>Daftar Produk & Stok
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>Produk</th>
                                <th>Kelas</th>
                                <th>Kategori</th>
                                <th>Stok Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($product->images->count() > 0)
                                        <img src="{{ $product->getPrimaryImage()->getImageUrl() }}" 
                                             alt="{{ $product->name }}" 
                                             class="rounded me-3" 
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" 
                                             style="width: 50px; height: 50px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                        @endif
                                        <div>
                                            <strong>{{ $product->name }}</strong>
                                            <br>
                                            <small class="text-muted">Rp {{ number_format($product->price) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $product->class->getFullName() }}</td>
                                <td>{{ $product->category->name }}</td>
                                <td>
                                    <span class="badge bg-{{ $product->getStockQuantity() > 10 ? 'success' : ($product->getStockQuantity() > 0 ? 'warning' : 'danger') }}">
                                        {{ $product->getStockQuantity() }} unit
                                    </span>
                                </td>
                                <td>
                                    @include('products.partials.status-badge', ['status' => $product->status])
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('products.show', $product) }}" class="btn btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-warning" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#adjustStockModal{{ $product->product_id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>

                                    <!-- Adjust Stock Modal -->
                                    <div class="modal fade" id="adjustStockModal{{ $product->product_id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Adjust Stok - {{ $product->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('inventory.stock.adjust', $product) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <!-- Current Stock -->
                                                        <div class="alert alert-info">
                                                            <strong>Stok Saat Ini:</strong> {{ $product->getStockQuantity() }} unit
                                                        </div>

                                                        <!-- Adjustment Type -->
                                                        <div class="mb-3">
                                                            <label class="form-label">Jenis Penyesuaian *</label>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" 
                                                                       name="adjustment_type" id="add{{ $product->product_id }}" 
                                                                       value="add" checked>
                                                                <label class="form-check-label" for="add{{ $product->product_id }}">
                                                                    Tambah Stok
                                                                </label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" 
                                                                       name="adjustment_type" id="subtract{{ $product->product_id }}" 
                                                                       value="subtract">
                                                                <label class="form-check-label" for="subtract{{ $product->product_id }}">
                                                                    Kurangi Stok
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <!-- Quantity -->
                                                        <div class="mb-3">
                                                            <label for="quantity{{ $product->product_id }}" class="form-label">Jumlah *</label>
                                                            <input type="number" class="form-control" 
                                                                   id="quantity{{ $product->product_id }}" name="quantity" 
                                                                   min="1" max="1000" value="1" required>
                                                        </div>

                                                        <!-- Note -->
                                                        <div class="mb-3">
                                                            <label for="note{{ $product->product_id }}" class="form-label">Keterangan *</label>
                                                            <textarea class="form-control" 
                                                                      id="note{{ $product->product_id }}" name="note" 
                                                                      rows="3" placeholder="Contoh: Stok awal, koreksi, dll." required></textarea>
                                                        </div>

                                                        <!-- Variants (if any) -->
                                                        @if($product->variants->count() > 0)
                                                        <div class="mb-3">
                                                            <label class="form-label">Adjust untuk Variant Tertentu (Opsional)</label>
                                                            <select class="form-select" name="variant_id">
                                                                <option value="">Semua Variant</option>
                                                                @foreach($product->variants as $variant)
                                                                <option value="{{ $variant->id }}">
                                                                    {{ $variant->name }} (Stok: {{ $variant->stock_at }})
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        @endif

                                                        <!-- Extras (if any) -->
                                                        @if($product->extras->count() > 0)
                                                        <div class="mb-3">
                                                            <label class="form-label">Adjust untuk Extra Tertentu (Opsional)</label>
                                                            <select class="form-select" name="extra_id">
                                                                <option value="">Semua Extra</option>
                                                                @foreach($product->extras as $extra)
                                                                <option value="{{ $extra->id }}">
                                                                    {{ $extra->name }} (Stok: {{ $extra->stock_cache }})
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Simpan Penyesuaian</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-box-open fa-2x text-muted mb-3"></i>
                                    <p class="text-muted">Belum ada produk</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $products->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection