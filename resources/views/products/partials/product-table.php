<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="table-primary">
            <tr>
                <th>Produk</th>
                <th>Kelas</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
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
                <td>
                    <span class="badge bg-{{ $product->getStockQuantity() > 0 ? 'success' : 'danger' }}">
                        {{ $product->getStockQuantity() }}
                    </span>
                </td>
                <td>
                    @include('products.partials.status-badge', ['status' => $product->status])
                </td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('products.show', $product) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                        @can('product.edit')
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        @endcan
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>