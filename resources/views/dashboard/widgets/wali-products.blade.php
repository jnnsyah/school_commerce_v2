<div class="col-12">
    <div class="card shadow mb-4">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-chalkboard me-2"></i>Kelas Saya - {{ $data['class']->getFullName() ?? '' }}
            </h6>
        </div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-4">
                    <div class="h4 text-primary">{{ $data['classStudents'] ?? 0 }}</div>
                    <small class="text-muted">Siswa</small>
                </div>
                <div class="col-md-4">
                    <div class="h4 text-success">{{ $data['classProducts'] ?? 0 }}</div>
                    <small class="text-muted">Produk</small>
                </div>
                <div class="col-md-4">
                    <div class="h4 text-warning">{{ $data['pendingProducts'] ?? 0 }}</div>
                    <small class="text-muted">Pending</small>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i>Tambah Produk
                </a>
                <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-list me-1"></i>Lihat Produk
                </a>
            </div>
        </div>
    </div>
</div>