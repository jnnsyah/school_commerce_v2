<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="table-primary">
            <tr>
                <th>Invoice</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>
                    <strong>{{ $order->invoice_number }}</strong>
                </td>
                <td>{{ $order->user->name }}</td>
                <td>Rp {{ number_format($order->total_amount) }}</td>
                <td>
                    @include('orders.partials.order-status', ['order' => $order])
                </td>
                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('orders.show', $order) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if($order->canBeCancelled())
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
            @endforeach
        </tbody>
    </table>
</div>