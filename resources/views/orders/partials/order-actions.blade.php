<div class="btn-group" role="group">
    @if($order->canBeCancelled())
    <form action="{{ route('orders.cancel', $order) }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-danger" 
                onclick="return confirm('Batalkan pesanan ini?')">
            <i class="fas fa-times me-1"></i>Batalkan
        </button>
    </form>
    @endif

    @can('order.manage')
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-primary dropdown-toggle" 
                data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-cog me-1"></i>Kelola
        </button>
        <ul class="dropdown-menu">
            <li>
                <a class="dropdown-item" href="#" data-bs-toggle="modal" 
                   data-bs-target="#statusModal{{ $order->order_id }}">
                    <i class="fas fa-sync me-2"></i>Ubah Status
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item text-danger" href="#" 
                   onclick="return confirm('Hapus pesanan ini?')">
                    <i class="fas fa-trash me-2"></i>Hapus
                </a>
            </li>
        </ul>
    </div>
    @endcan
</div>

<!-- Status Update Modal -->
@can('order.manage')
<div class="modal fade" id="statusModal{{ $order->order_id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Status Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('orders.update-status', $order) }}" method="POST">
                @csrf
                <div class="modal-body">
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
                        <label for="note" class="form-label">Catatan</label>
                        <textarea class="form-control" id="note" name="note" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan