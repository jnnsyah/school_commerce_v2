<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="table-primary">
            <tr>
                <th>Kelas</th>
                <th>Wali Kelas</th>
                <th>Siswa</th>
                <th>Produk</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($classes as $class)
            <tr>
                <td>
                    <strong>{{ $class->getFullName() }}</strong>
                </td>
                <td>{{ $class->teacher->name }}</td>
                <td>{{ $class->getStudentCount() }}</td>
                <td>{{ $class->getActiveProductsCount() }}</td>
                <td>
                    @if($class->is_active)
                    <span class="badge bg-success">Aktif</span>
                    @else
                    <span class="badge bg-secondary">Nonaktif</span>
                    @endif
                </td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('classes.show', $class) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                        @can('class.edit')
                        <a href="{{ route('classes.edit', $class) }}" class="btn btn-warning">
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