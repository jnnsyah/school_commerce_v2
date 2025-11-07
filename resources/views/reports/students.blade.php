@extends('layouts.app')

@section('title', 'Laporan Siswa')
@section('page-title', 'Laporan Aktivitas Siswa')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Date Filter -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" 
                               value="{{ $dateRange['start']->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="end_date" class="form-label">Tanggal Akhir</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" 
                               value="{{ $dateRange['end']->format('Y-m-d') }}">
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

        <!-- Student Stats -->
        <div class="row mb-4">
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Siswa</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $studentStats['total_students'] }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Pembeli Aktif</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $studentStats['active_buyers'] }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Rata-rata Belanja</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($studentStats['average_order_value']) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Students -->
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-trophy me-2"></i>Siswa dengan Belanja Tertinggi
                </h5>
            </div>
            <div class="card-body">
                @if($topStudents->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>Peringkat</th>
                                <th>Siswa</th>
                                <th>Kelas</th>
                                <th>Total Pesanan</th>
                                <th>Total Belanja</th>
                                <th>Rata-rata</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topStudents as $index => $student)
                            <tr>
                                <td>
                                    @if($index == 0)
                                    <span class="badge bg-warning">🥇</span>
                                    @elseif($index == 1)
                                    <span class="badge bg-secondary">🥈</span>
                                    @elseif($index == 2)
                                    <span class="badge bg-danger">🥉</span>
                                    @else
                                    <span class="badge bg-light text-dark">#{{ $index + 1 }}</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $student->name }}</strong>
                                    <br>
                                    <small class="text-muted">@ {{ $student->username }}</small>
                                </td>
                                <td>{{ $student->student->getClassName() }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $student->total_orders }} pesanan</span>
                                </td>
                                <td class="text-success">
                                    <strong>Rp {{ number_format($student->total_spent) }}</strong>
                                </td>
                                <td>Rp {{ number_format($student->total_spent / max($student->total_orders, 1)) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($topStudents->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $topStudents->links() }}
                </div>
                @endif
                @else
                <div class="text-center py-4">
                    <i class="fas fa-user-graduate fa-2x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada data aktivitas siswa</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection