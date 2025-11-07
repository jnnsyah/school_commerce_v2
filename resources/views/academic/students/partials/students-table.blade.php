<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="table-primary">
            <tr>
                <th>Nama Siswa</th>
                <th>NISN</th>
                <th>Kelas</th>
                <th>Username</th>
                <th>Status Admin</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
            <tr>
                <td>{{ $student->user->name }}</td>
                <td>{{ $student->nisn }}</td>
                <td>{{ $student->getClassName() }}</td>
                <td>@ {{ $student->user->username }}</td>
                <td>
                    @if($student->is_admin_class)
                    <span class="badge bg-success">Admin Kelas</span>
                    @else
                    <span class="badge bg-secondary">Siswa Biasa</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>