<!-- resources/views/admin/users/students.blade.php -->
@extends('layouts.admin-app')

@section('title', 'Kelola Siswa - School Commerce')
@section('page-title', 'Manajemen Siswa')
@section('page-subtitle', 'Kelola data siswa dan kelas')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-semibold text-primary dark:text-light">Data Siswa</h2>
            <p class="text-sm text-gray-600 dark:text-slate-400 mt-1">
                Total {{ $students->total() }} siswa terdaftar
            </p>
        </div>
        
        <div class="mt-4 sm:mt-0">
            <a href="/admin/users/create" 
               class="inline-flex items-center px-4 py-2 bg-accent text-white rounded-lg hover:bg-accent/90 transition font-medium">
                <i class="fas fa-plus mr-2"></i>
                Tambah Siswa
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-4">
        <form action="{{ url()->current() }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Class Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Kelas</label>
                <select name="class_id" class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                             focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                             bg-white dark:bg-secondary text-primary dark:text-light">
                    <option value="">Semua Kelas</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->class_id }}" {{ request('class_id') == $class->class_id ? 'selected' : '' }}>
                            {{ $class->grade->name }} {{ $class->major->short_name }} {{ $class->section->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-end space-x-2">
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-accent text-white rounded-lg hover:bg-accent/90 transition font-medium">
                    <i class="fas fa-filter mr-2"></i>
                    Filter
                </button>
                <a href="{{ url()->current() }}" 
                   class="px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-primary dark:text-light 
                          hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                    <i class="fas fa-refresh"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Students Table -->
    <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-slate-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Siswa
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Kelas & NISN
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Kontak
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                    @forelse($students as $student)
                    @php $user = $student->user; @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                        <!-- Student Info -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-orange-100 dark:bg-orange-900/20 flex items-center justify-center">
                                        <i class="fas fa-user-graduate text-orange-600 dark:text-orange-400"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-primary dark:text-light">
                                        {{ $user->name }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-slate-400">
                                        @{{ $user->username }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Class & NISN -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-primary dark:text-light">
                                {{ $student->class->grade->name }} {{ $student->class->major->short_name }} {{ $student->class->section->name }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-slate-400">
                                NISN: {{ $student->nisn ?? '-' }}
                            </div>
                        </td>

                        <!-- Contact -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-primary dark:text-light">{{ $user->email }}</div>
                            @if($user->no_hp)
                            <div class="text-sm text-gray-500 dark:text-slate-400">{{ $user->no_hp }}</div>
                            @endif
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col space-y-1">
                                @include('components.admin.user-role-badge', ['user' => $user])
                                @if($student->is_admin_class)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300">
                                    <i class="fas fa-star mr-1"></i>
                                    Admin Kelas
                                </span>
                                @endif
                            </div>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <a href="/admin/users/{{ $user->id }}/edit" 
                                   class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300 transition">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                @if($user->id !== auth()->id())
                                <form action="/admin/users/{{ $user->id }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus siswa ini?')"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <i class="fas fa-user-graduate text-4xl text-gray-400 dark:text-slate-600 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-light mb-2">Tidak ada siswa</h3>
                            <p class="text-gray-500 dark:text-slate-400 mb-4">Belum ada siswa yang terdaftar</p>
                            <a href="/admin/users/create" 
                               class="inline-flex items-center px-4 py-2 bg-accent text-white rounded-lg hover:bg-accent/90 transition">
                                <i class="fas fa-plus mr-2"></i>
                                Tambah Siswa Pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($students->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700">
            {{ $students->links('components.shared.pagination') }}
        </div>
        @endif
    </div>
</div>
@endsection