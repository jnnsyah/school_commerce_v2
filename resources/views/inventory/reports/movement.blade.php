@extends('layouts.app')

@section('title', 'Riwayat Perpindahan Stok')
@section('page-title', 'Riwayat Perpindahan Stok')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Tabs -->
        <ul class="nav nav-tabs mb-4" id="movementTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="product-tab" data-bs-toggle="tab" 
                        data-bs-target="#product" type="button" role="tab">
                    <i class="fas fa-cube me-2"></i>Produk Utama
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="variant-tab" data-bs-toggle="tab" 
                        data-bs-target="#variant" type="button" role="tab">
                    <i class="fas fa-list me-2"></i>Variant
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="extra-tab" data-bs-toggle="tab" 
                        data-bs-target="#extra" type="button" role="tab">
                    <i class="fas fa-plus-circle me-2"></i>Extra
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="movementTabsContent">
            <!-- Product Movements -->
            <div class="tab-pane fade show active" id="product" role="tabpanel">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-cube me-2"></i>Riwayat Stok Produk Utama
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Produk</th>
                                        <th>Jenis</th>
                                        <th>Qty</th>
                                        <th>Keterangan</th>
                                        <th>User</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($productMovements as $movement)
                                    <tr>
                                        <td>{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <strong>{{ $movement->product->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $movement->product->class->getFullName() }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $movement->referenceType->code == 'order' ? 'danger' : 'success' }}">
                                                {{ $movement->referenceType->description }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="{{ $movement->qty < 0 ? 'text-danger' : 'text-success' }}">
                                                {{ $movement->qty > 0 ? '+' : '' }}{{ $movement->qty }}
                                            </span>
                                        </td>
                                        <td>{{ $movement->note }}</td>
                                        <td>{{ $movement->user->name }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="fas fa-history fa-2x text-muted mb-3"></i>
                                            <p class="text-muted">Belum ada riwayat perpindahan stok</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($productMovements->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $productMovements->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Variant Movements -->
            <div class="tab-pane fade" id="variant" role="tabpanel">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>Riwayat Stok Variant
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Produk & Variant</th>
                                        <th>Jenis</th>
                                        <th>Qty</th>
                                        <th>Keterangan</th>
                                        <th>User</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($variantMovements as $movement)
                                    <tr>
                                        <td>{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <strong>{{ $movement->variant->product->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $movement->variant->name }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $movement->referenceType->code == 'order' ? 'danger' : 'success' }}">
                                                {{ $movement->referenceType->description }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="{{ $movement->qty < 0 ? 'text-danger' : 'text-success' }}">
                                                {{ $movement->qty > 0 ? '+' : '' }}{{ $movement->qty }}
                                            </span>
                                        </td>
                                        <td>{{ $movement->note }}</td>
                                        <td>{{ $movement->user->name }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="fas fa-history fa-2x text-muted mb-3"></i>
                                            <p class="text-muted">Belum ada riwayat perpindahan stok variant</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($variantMovements->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $variantMovements->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Extra Movements -->
            <div class="tab-pane fade" id="extra" role="tabpanel">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-plus-circle me-2"></i>Riwayat Stok Extra
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Produk & Extra</th>
                                        <th>Jenis</th>
                                        <th>Qty</th>
                                        <th>Keterangan</th>
                                        <th>User</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($extraMovements as $movement)
                                    <tr>
                                        <td>{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <strong>{{ $movement->extra->product->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $movement->extra->name }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $movement->referenceType->code == 'order' ? 'danger' : 'success' }}">
                                                {{ $movement->referenceType->description }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="{{ $movement->qty < 0 ? 'text-danger' : 'text-success' }}">
                                                {{ $movement->qty > 0 ? '+' : '' }}{{ $movement->qty }}
                                            </span>
                                        </td>
                                        <td>{{ $movement->note }}</td>
                                        <td>{{ $movement->user->name }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="fas fa-history fa-2x text-muted mb-3"></i>
                                            <p class="text-muted">Belum ada riwayat perpindahan stok extra</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($extraMovements->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $extraMovements->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection