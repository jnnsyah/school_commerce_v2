@extends('layouts.app')

@section('title', 'Daftar Produk')
@section('page-title', 'Daftar Produk')

@section('page-actions')
    @can('product.create')
    <a href="{{ route('products.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>Tambah Produk
    </a>
    @endcan
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Filters -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Kategori</label>
                        <select name="category_id" class="form-select">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            @foreach($statuses as $status)
                            <option value="{{ $status->id }}" {{ request('status') == $status->id ? 'selected' : '' }}>
                                {{ ucfirst($status->name) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                    @can('product.manage')
                    <div class="col-md-3">
                        <label class="form-label">Kelas</label>
                        <select name="class_id" class="form-select">
                            <option value="">Semua Kelas</option>
                            @foreach($classes as $class)
                            <option value="{{ $class->class_id }}" {{ request('class_id') == $class->class_id ? 'selected' : '' }}>
                                {{ $class->getFullName() }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @endcan
                    
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="row">
            @forelse($products as $product)
            <div class="col-xl-4 col-lg-6 mb-4">
                @include('products.partials.product-card', ['product' => $product])
            </div>
            @empty
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada produk</h5>
                        @can('product.create')
                        <p class="text-muted">Mulai dengan menambahkan produk pertama Anda</p>
                        <a href="{{ route('products.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Tambah Produk
                        </a>
                        @endcan
                    </div>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
        <div class="d-flex justify-content-center">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</div>
@endsection