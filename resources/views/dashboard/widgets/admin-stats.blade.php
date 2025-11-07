<div class="col-12">
    <div class="card shadow mb-4">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-tachometer-alt me-2"></i>Statistik Admin
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 text-center">
                    <div class="h4 text-primary">{{ $data['totalUsers'] ?? 0 }}</div>
                    <small class="text-muted">Total Users</small>
                </div>
                <div class="col-md-3 text-center">
                    <div class="h4 text-success">{{ $data['totalClasses'] ?? 0 }}</div>
                    <small class="text-muted">Total Kelas</small>
                </div>
                <div class="col-md-3 text-center">
                    <div class="h4 text-info">{{ $data['totalProducts'] ?? 0 }}</div>
                    <small class="text-muted">Total Produk</small>
                </div>
                <div class="col-md-3 text-center">
                    <div class="h4 text-warning">{{ $data['pendingProducts'] ?? 0 }}</div>
                    <small class="text-muted">Pending Approval</small>
                </div>
            </div>
        </div>
    </div>
</div>