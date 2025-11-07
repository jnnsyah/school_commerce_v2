@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->invoice_number)
@section('page-title', 'Detail Pesanan #' . $order->invoice_number)

@section('page-actions')
    <a href="{{ route('orders.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
    @if($order->canBeCancelled() && ($order->user_id == auth()->id() || auth()->user()->hasRole(['super_admin', 'admin', 'guru_pkwu'])))
    <form action="{{ route('orders.cancel', $order) }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-danger" 
                onclick="return confirm('Batalkan pesanan ini?')">
            <i class="fas fa-times me-1"></i>Batalkan
        </button>
    </form>
    @endif
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Order Items -->
        <div class="card shadow mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Item Pesanan
                </h5>
            </div>
            <div class="card-body">
                @include('orders.partials.order-items')
            </div>
        </div>

        <!-- Order History -->
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i>Riwayat Status
                </h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @foreach($order->transactionLogs as $log)
                    <div class="timeline-item mb-3">
                        <div class="timeline-marker bg-{{ $log->status->name == 'completed' ? 'success' : 'primary' }}"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">{{ ucfirst($log->status->name) }}</h6>
                            <p class="text-muted small mb-1">{{ $log->note }}</p>
                            <small class="text-muted">
                                {{ $log->created_at->format('d/m/Y H:i') }} oleh {{ $log->createdBy->name }}
                            </small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Order Summary -->
        <div class="card shadow mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-receipt me-2"></i>Ringkasan Pesanan
                </h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Invoice:</strong></td>
                        <td>{{ $order->invoice_number }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>@include('orders.partials.order-status', ['order' => $order])</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal:</strong></td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Total:</strong></td>
                        <td class="h5 text-primary">Rp {{ number_format($order->total_amount) }}</td>
                    </tr>
                    @if($order->customer_notes)
                    <tr>
                        <td><strong>Catatan:</strong></td>
                        <td class="text-muted">{{ $order->customer_notes }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="card shadow mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user me-2"></i>Informasi Pembeli
                </h5>
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>{{ $order->user->name }}</strong></p>
                <p class="mb-1 small text-muted">{{ $order->user->email }}</p>
                <p class="mb-0 small text-muted">{{ $order->user->no_hp }}</p>
                
                @if($order->user->student)
                <p class="mb-0 small text-muted">
                    Kelas: {{ $order->user->student->getClassName() }}
                </p>
                @endif
            </div>
        </div>

        <!-- Status Update (for admin/guru) -->
        @can('order.manage')
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-cog me-2"></i>Update Status
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('orders.update-status', $order) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="status_id" class="form-label">Status Baru</label>
                        <select class="form-select" id="status_id" name="status_id" required>
                            @foreach($statuses as $status)
                            <option value="{{ $status->status_id }}" 
                                    {{ $order->status_id == $status->status_id ? 'selected' : '' }}>
                                {{ ucfirst($status->name) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="note" class="form-label">Catatan (Opsional)</label>
                        <textarea class="form-control" id="note" name="note" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Update Status</button>
                </form>
            </div>
        </div>
        @endcan
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline-item {
    position: relative;
}
.timeline-marker {
    position: absolute;
    left: -30px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}
.timeline-content {
    padding-bottom: 10px;
}
</style>
@endsection