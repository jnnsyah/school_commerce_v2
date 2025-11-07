@extends('layouts.app')

@section('title', 'Daftar Pesanan')
@section('page-title', 'Daftar Pesanan')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Filters -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Status Pesanan</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            @foreach($statuses as $status)
                            <option value="{{ $status->status_id }}" {{ request('status') == $status->status_id ? 'selected' : '' }}>
                                {{ ucfirst($status->name) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>Invoice</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td>
                                    <strong>{{ $order->invoice_number }}</strong>
                                    @if(auth()->user()->hasRole(['super_admin', 'admin', 'guru_pkwu']))
                                    <br>
                                    <small class="text-muted">Oleh: {{ $order->user->name }}</small>
                                    @endif
                                </td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td>Rp {{ number_format($order->total_amount) }}</td>
                                <td>
                                    @include('orders.partials.order-status', ['order' => $order])
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('orders.show', $order) }}" class="btn btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($order->canBeCancelled() && ($order->user_id == auth()->id() || auth()->user()->hasRole(['super_admin', 'admin', 'guru_pkwu'])))
                                        <form action="{{ route('orders.cancel', $order) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-danger" 
                                                    onclick="return confirm('Batalkan pesanan ini?')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="fas fa-receipt fa-2x text-muted mb-3"></i>
                                    <p class="text-muted">Belum ada pesanan</p>
                                    <a href="{{ route('products.index') }}" class="btn btn-primary">
                                        <i class="fas fa-shopping-bag me-1"></i>Mulai Belanja
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($orders->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $orders->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection