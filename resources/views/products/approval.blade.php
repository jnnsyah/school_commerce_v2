@extends('layouts.app')

@section('title', 'Approval Produk')
@section('page-title', 'Antrian Approval Produk')

@section('content')
<div class="row">
    <div class="col-12">
        @if($pendingProducts->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>Produk</th>
                                <th>Kelas</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Tanggal Diajukan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingProducts as $product)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($product->images->count() > 0)
                                        <img src="{{ $product->getPrimaryImage()->getImageUrl() }}" 
                                             alt="{{ $product->name }}" 
                                             class="rounded me-3" 
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                        @endif
                                        <div>
                                            <strong>{{ $product->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $product->class->getFullName() }}</td>
                                <td>{{ $product->category->name }}</td>
                                <td>Rp {{ number_format($product->price) }}</td>
                                <td>{{ $product->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('products.show', $product) }}" 
                                           class="btn btn-info" target="_blank">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-success" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#approveModal{{ $product->product_id }}">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#rejectModal{{ $product->product_id }}">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>

                                    <!-- Approve Modal -->
                                    <div class="modal fade" id="approveModal{{ $product->product_id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Setujui Produk</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah Anda yakin ingin menyetujui produk <strong>{{ $product->name }}</strong>?</p>
                                                    <p class="text-muted">Produk akan dapat dilihat dan dibeli oleh semua user.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <form action="{{ route('products.approval.approve', $product) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success">Setujui</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectModal{{ $product->product_id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Tolak Produk</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('products.approval.reject', $product) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <p>Apakah Anda yakin ingin menolak produk <strong>{{ $product->name }}</strong>?</p>
                                                        <div class="mb-3">
                                                            <label for="rejection_reason" class="form-label">Alasan Penolakan *</label>
                                                            <textarea class="form-control" id="rejection_reason" 
                                                                      name="rejection_reason" rows="3" 
                                                                      placeholder="Berikan alasan penolakan..." required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger">Tolak</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($pendingProducts->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $pendingProducts->links() }}
                </div>
                @endif
            </div>
        </div>
        @else
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                <h5 class="text-success">Tidak ada produk yang menunggu approval</h5>
                <p class="text-muted">Semua produk telah diproses.</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection