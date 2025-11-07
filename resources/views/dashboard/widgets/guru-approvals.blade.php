<div class="col-12">
    <div class="card shadow mb-4">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-check-circle me-2"></i>Approval Produk
            </h6>
        </div>
        <div class="card-body">
            @if(($data['pendingApprovals'] ?? 0) > 0)
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Ada <strong>{{ $data['pendingApprovals'] }}</strong> produk menunggu approval.
            </div>
            <a href="{{ route('products.approval.index') }}" class="btn btn-warning">
                <i class="fas fa-clipboard-check me-2"></i>Proses Approval
            </a>
            @else
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                Tidak ada produk yang menunggu approval.
            </div>
            @endif
        </div>
    </div>
</div>