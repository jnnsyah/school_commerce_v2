@extends('layouts.app')

@section('title', $product->name)
@section('page-title', $product->name)

@section('page-actions')
    <a href="{{ route('products.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
    @can('product.edit')
        @if($product->class->teacher_id == auth()->id() || auth()->user()->hasRole(['super_admin', 'admin']))
        <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">
            <i class="fas fa-edit me-1"></i>Edit
        </a>
        @endif
    @endcan
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Product Images -->
        <div class="card shadow mb-4">
            <div class="card-body">
                @if($product->images->count() > 0)
                <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach($product->images as $index => $image)
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                            <img src="{{ $image->getImageUrl() }}" class="d-block w-100" 
                                 alt="{{ $product->name }}" style="height: 400px; object-fit: cover;">
                        </div>
                        @endforeach
                    </div>
                    @if($product->images->count() > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                    @endif
                </div>
                @else
                <div class="text-center py-5 bg-light rounded">
                    <i class="fas fa-image fa-4x text-muted mb-3"></i>
                    <p class="text-muted">Tidak ada gambar produk</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Product Description -->
        <div class="card shadow mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Deskripsi Produk</h5>
            </div>
            <div class="card-body">
                @if($product->description)
                <p class="card-text">{{ $product->description }}</p>
                @else
                <p class="text-muted">Tidak ada deskripsi produk.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Product Details -->
        <div class="card shadow mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-cube me-2"></i>Detail Produk</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>@include('products.partials.status-badge', ['status' => $product->status])</td>
                    </tr>
                    <tr>
                        <td><strong>Harga:</strong></td>
                        <td class="h5 text-primary">Rp {{ number_format($product->price) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Stok Tersedia:</strong></td>
                        <td>
                            <span class="badge bg-{{ $product->getStockQuantity() > 0 ? 'success' : 'danger' }}">
                                {{ $product->getStockQuantity() }} unit
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Kategori:</strong></td>
                        <td>{{ $product->category->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Kelas:</strong></td>
                        <td>{{ $product->class->getFullName() }}</td>
                    </tr>
                    <tr>
                        <td><strong>Wali Kelas:</strong></td>
                        <td>{{ $product->class->teacher->name }}</td>
                    </tr>
                    @if($product->isApproved())
                    <tr>
                        <td><strong>Disetujui Oleh:</strong></td>
                        <td>{{ $product->approvedBy->name ?? 'System' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Disetujui:</strong></td>
                        <td>{{ $product->approved_at?->format('d/m/Y H:i') ?? '-' }}</td>
                    </tr>
                    @endif
                    @if($product->isRejected())
                    <tr>
                        <td><strong>Alasan Ditolak:</strong></td>
                        <td class="text-danger">{{ $product->rejection_reason }}</td>
                    </tr>
                    @endif
                </table>

                <!-- Action Buttons -->
                <div class="d-grid gap-2 mt-4">
                    @if($product->isApproved() && $product->getStockQuantity() > 0)
                        @cannot('product.create') <!-- Students & regular teachers can add to cart -->
                        <button class="btn btn-success btn-lg add-to-cart" 
                                data-product-id="{{ $product->product_id }}"
                                data-product-name="{{ $product->name }}">
                            <i class="fas fa-cart-plus me-2"></i>Tambahkan ke Keranjang
                        </button>
                        @endcannot
                    @elseif($product->isApproved() && $product->getStockQuantity() <= 0)
                        <button class="btn btn-secondary btn-lg" disabled>
                            <i class="fas fa-times me-2"></i>Stok Habis
                        </button>
                    @elseif($product->isPending())
                        <button class="btn btn-warning btn-lg" disabled>
                            <i class="fas fa-clock me-2"></i>Menunggu Approval
                        </button>
                    @elseif($product->isRejected())
                        <button class="btn btn-danger btn-lg" disabled>
                            <i class="fas fa-times me-2"></i>Ditolak
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sales Stats (for product owners) -->
        @if($product->class->teacher_id == auth()->id() || auth()->user()->hasRole(['super_admin', 'admin', 'guru_pkwu']))
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Statistik Penjualan</h5>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <div class="h3 text-primary">{{ $product->getTotalSold() }}</div>
                    <p class="text-muted">Total Terjual</p>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.add-to-cart').click(function() {
        const productId = $(this).data('product-id');
        const productName = $(this).data('product-name');
        
        $.ajax({
            url: '{{ route("cart.add") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: productId,
                quantity: 1
            },
            success: function(response) {
                const alert = `
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        ${productName} berhasil ditambahkan ke keranjang
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                $('.container-fluid').prepend(alert);
            },
            error: function(xhr) {
                const error = xhr.responseJSON?.message || 'Terjadi kesalahan';
                const alert = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        ${error}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                $('.container-fluid').prepend(alert);
            }
        });
    });
});
</script>
@endpush