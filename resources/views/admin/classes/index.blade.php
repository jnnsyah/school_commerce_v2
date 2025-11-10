<!-- resources/views/admin/classes/index.blade.php -->
@extends('layouts.admin-app')

@section('title', 'Kelola Kelas - School Commerce')
@section('page-title', 'Manajemen Kelas')
@section('page-subtitle', 'Kelola struktur kelas dan wali kelas')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-semibold text-primary dark:text-light">Data Kelas</h2>
            <p class="text-sm text-gray-600 dark:text-slate-400 mt-1">
                Total {{ $classes->total() }} kelas terdaftar
            </p>
        </div>
        
        <div class="mt-4 sm:mt-0">
            <a href="/admin/classes/create" 
               class="inline-flex items-center px-4 py-2 bg-accent text-white rounded-lg hover:bg-accent/90 transition font-medium">
                <i class="fas fa-plus mr-2"></i>
                Tambah Kelas
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-4">
        <form action="{{ url()->current() }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Grade Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Tingkat</label>
                <select name="grade_id" class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                             focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                             bg-white dark:bg-secondary text-primary dark:text-light">
                    <option value="">Semua Tingkat</option>
                    @foreach($grades as $grade)
                        <option value="{{ $grade->id }}" {{ request('grade_id') == $grade->id ? 'selected' : '' }}>
                            {{ $grade->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Major Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Jurusan</label>
                <select name="major_id" class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                             focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                             bg-white dark:bg-secondary text-primary dark:text-light">
                    <option value="">Semua Jurusan</option>
                    @foreach($majors as $major)
                        <option value="{{ $major->id }}" {{ request('major_id') == $major->id ? 'selected' : '' }}>
                            {{ $major->name }}
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

    <!-- Classes Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($classes as $class)
        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
            <!-- Class Header -->
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-white">
                        {{ $class->grade->name }} {{ $class->major->short_name }}
                    </h3>
                    <span class="bg-white/20 text-white px-2 py-1 rounded-full text-sm">
                        {{ $class->section->name }}
                    </span>
                </div>
                <p class="text-blue-100 text-sm mt-1">{{ $class->major->name }}</p>
            </div>

            <!-- Class Info -->
            <div class="p-4">
                <!-- Teacher Info -->
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/20 flex items-center justify-center">
                        <i class="fas fa-chalkboard-teacher text-green-600 dark:text-green-400"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-primary dark:text-light">
                            {{ $class->teacher->name ?? 'Belum ada wali kelas' }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-slate-400">
                            Wali Kelas
                        </p>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="grid grid-cols-2 gap-4 text-center">
                    <div>
                        <p class="text-2xl font-bold text-accent">{{ $class->students_count ?? 0 }}</p>
                        <p class="text-xs text-gray-500 dark:text-slate-400">Siswa</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-accent">{{ $class->products_count ?? 0 }}</p>
                        <p class="text-xs text-gray-500 dark:text-slate-400">Produk</p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-200 dark:border-slate-700">
                    <div class="flex space-x-2">
                        <a href="/admin/classes/{{ $class->class_id }}/edit" 
                           class="text-yellow-600 hover:text-yellow-700 transition">
                            <i class="fas fa-edit"></i>
                        </a>
                        
                        @if($class->students_count == 0 && $class->products_count == 0)
                        <form action="/admin/classes/{{ $class->class_id }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus kelas ini?')"
                                    class="text-red-600 hover:text-red-700 transition">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </div>

                    <!-- Assign Teacher -->
                    @if(!$class->teacher)
                    <button onclick="openAssignTeacherModal({{ $class->class_id }})"
                            class="text-sm bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 transition">
                        <i class="fas fa-user-plus mr-1"></i>
                        Assign
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <i class="fas fa-school text-4xl text-gray-400 dark:text-slate-600 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 dark:text-light mb-2">Tidak ada kelas</h3>
            <p class="text-gray-500 dark:text-slate-400 mb-4">Belum ada kelas yang dibuat</p>
            <a href="/admin/classes/create" 
               class="inline-flex items-center px-4 py-2 bg-accent text-white rounded-lg hover:bg-accent/90 transition">
                <i class="fas fa-plus mr-2"></i>
                Tambah Kelas Pertama
            </a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($classes->hasPages())
    <div class="mt-6">
        {{ $classes->links('components.shared.pagination') }}
    </div>
    @endif
</div>

<!-- Assign Teacher Modal -->
<div id="assign-teacher-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50" onclick="closeAssignTeacherModal()"></div>
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-secondary rounded-lg p-6 w-96">
        <h3 class="text-lg font-semibold text-primary dark:text-light mb-4">Assign Wali Kelas</h3>
        <form id="assign-teacher-form" method="POST">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Pilih Guru</label>
                <select name="teacher_id" class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                               focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                               bg-white dark:bg-secondary text-primary dark:text-light" required>
                    <option value="">Pilih Wali Kelas</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" 
                        onclick="closeAssignTeacherModal()"
                        class="px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-primary dark:text-light 
                               hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                    Batal
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-accent text-white rounded-lg hover:bg-accent/90 transition">
                    Assign
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAssignTeacherModal(classId) {
    const modal = document.getElementById('assign-teacher-modal');
    const form = document.getElementById('assign-teacher-form');
    
    form.action = `/admin/classes/${classId}/assign-teacher`;
    modal.classList.remove('hidden');
}

function closeAssignTeacherModal() {
    document.getElementById('assign-teacher-modal').classList.add('hidden');
}
</script>
@endsection