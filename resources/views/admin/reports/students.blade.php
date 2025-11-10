<!-- resources/views/admin/reports/students.blade.php -->
@extends('layouts.admin-app')

@section('title', 'Laporan Aktivitas Siswa - School Commerce')
@section('page-title', 'Laporan Aktivitas Siswa')
@section('page-subtitle', 'Analisis aktivitas belanja dan engagement siswa')

@section('content')
<div class="space-y-6">
    <!-- Date Range Filter -->
    <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
        <h3 class="text-lg font-semibold text-primary dark:text-light mb-4">Filter Laporan</h3>
        <form action="{{ url()->current() }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Tanggal Mulai</label>
                <input type="date" 
                       name="start_date" 
                       value="{{ request('start_date', $dateRange['start']->format('Y-m-d')) }}"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                              focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                              bg-white dark:bg-secondary text-primary dark:text-light">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Tanggal Akhir</label>
                <input type="date" 
                       name="end_date" 
                       value="{{ request('end_date', $dateRange['end']->format('Y-m-d')) }}"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                              focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                              bg-white dark:bg-secondary text-primary dark:text-light">
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-accent text-white rounded-lg hover:bg-accent/90 transition font-medium">
                    <i class="fas fa-filter mr-2"></i>
                    Terapkan
                </button>
                <a href="{{ url()->current() }}" 
                   class="px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-primary dark:text-light 
                          hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                    <i class="fas fa-refresh"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Student Activity Table -->
    <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700">
        <div class="p-6 border-b border-gray-200 dark:border-slate-700">
            <h3 class="text-lg font-semibold text-primary dark:text-light">Aktivitas Belanja Siswa</h3>
            <p class="text-sm text-gray-600 dark:text-slate-400 mt-1">
                Total {{ $studentActivity->total() }} siswa aktif ditemukan
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-slate-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Siswa
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Kelas
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Total Pesanan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Total Belanja
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Rata-rata Pesanan
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                    @forelse($studentActivity as $student)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-accent/20 flex items-center justify-center">
                                        <i class="fas fa-user text-accent"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-primary dark:text-light">
                                        {{ $student->name }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-slate-400">
                                        {{ $student->email }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-light">
                                @if($student->student && $student->student->class)
                                    {{ $student->student->class->grade->name }} {{ $student->student->class->major->short_name }}
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-light">
                                {{ $student->total_orders }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-accent">
                                Rp {{ number_format($student->total_spent, 0, ',', '.') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-light">
                                Rp {{ number_format($student->total_spent / max($student->total_orders, 1), 0, ',', '.') }}
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-slate-400">
                            <i class="fas fa-users text-3xl mb-2"></i>
                            <p>Tidak ada aktivitas siswa</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($studentActivity->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700">
            {{ $studentActivity->links('components.shared.pagination') }}
        </div>
        @endif
    </div>

    <!-- Class Activity -->
    <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
        <h3 class="text-lg font-semibold text-primary dark:text-light mb-6">Aktivitas per Kelas</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($classActivity as $class)
            <div class="border border-gray-200 dark:border-slate-600 rounded-lg p-4">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="font-semibold text-primary dark:text-light">
                        {{ $class->grade->name }} {{ $class->major->short_name }} {{ $class->section->name }}
                    </h4>
                    <span class="text-xs bg-accent text-white px-2 py-1 rounded-full">
                        #{{ $loop->iteration }}
                    </span>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-slate-400">Siswa:</span>
                        <span class="font-medium text-primary dark:text-light">{{ $class->students_count }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-slate-400">Produk:</span>
                        <span class="font-medium text-primary dark:text-light">{{ $class->products_count }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-slate-400">Total Sales:</span>
                        <span class="font-semibold text-accent">Rp {{ number_format($class->total_sales ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection