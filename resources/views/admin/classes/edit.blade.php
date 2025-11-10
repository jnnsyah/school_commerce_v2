<!-- resources/views/admin/classes/edit.blade.php -->
@extends('layouts.admin-app')

@section('title', 'Edit Kelas - School Commerce')
@section('page-title', 'Edit Kelas')
@section('page-subtitle', 'Update informasi kelas')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700">
        <!-- Form Header -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700">
            <h3 class="text-lg font-semibold text-primary dark:text-light">Edit Kelas</h3>
            <p class="text-sm text-gray-600 dark:text-slate-400 mt-1">
                Update informasi {{ $class->grade->name }} {{ $class->major->short_name }} {{ $class->section->name }}
            </p>
        </div>

        <!-- Class Form -->
        <form action="/admin/classes/{{ $class->class_id }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Grade -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        Tingkat <span class="text-red-500">*</span>
                    </label>
                    <select name="grade_id" 
                            required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                   focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                   bg-white dark:bg-secondary text-primary dark:text-light">
                        <option value="">Pilih Tingkat</option>
                        @foreach($grades as $grade)
                            <option value="{{ $grade->id }}" {{ old('grade_id', $class->grade_id) == $grade->id ? 'selected' : '' }}>
                                {{ $grade->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('grade_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Major -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        Jurusan <span class="text-red-500">*</span>
                    </label>
                    <select name="major_id" 
                            required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                   focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                   bg-white dark:bg-secondary text-primary dark:text-light">
                        <option value="">Pilih Jurusan</option>
                        @foreach($majors as $major)
                            <option value="{{ $major->id }}" {{ old('major_id', $class->major_id) == $major->id ? 'selected' : '' }}>
                                {{ $major->name }} ({{ $major->short_name }})
                            </option>
                        @endforeach
                    </select>
                    @error('major_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Section -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        Rombel <span class="text-red-500">*</span>
                    </label>
                    <select name="section_id" 
                            required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                   focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                   bg-white dark:bg-secondary text-primary dark:text-light">
                        <option value="">Pilih Rombel</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{ old('section_id', $class->section_id) == $section->id ? 'selected' : '' }}>
                                {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('section_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Teacher Assignment -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                    Wali Kelas
                </label>
                <select name="teacher_id" 
                        class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                               focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                               bg-white dark:bg-secondary text-primary dark:text-light">
                    <option value="">Pilih Wali Kelas</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ old('teacher_id', $class->teacher_id) == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->name }} - {{ $teacher->teacher->nip ?? '-' }}
                        </option>
                    @endforeach
                </select>
                <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">
                    Hanya guru dengan role "wali_kelas" yang dapat dipilih
                </p>
                @error('teacher_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Class Stats -->
            <div class="bg-gray-50 dark:bg-slate-800 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-700 dark:text-slate-300 mb-3">Statistik Kelas</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-accent">{{ $class->students_count ?? 0 }}</div>
                        <div class="text-xs text-gray-500 dark:text-slate-400">Siswa</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $class->products_count ?? 0 }}</div>
                        <div class="text-xs text-gray-500 dark:text-slate-400">Produk</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $class->orders_count ?? 0 }}</div>
                        <div class="text-xs text-gray-500 dark:text-slate-400">Pesanan</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600">Rp {{ number_format($class->revenue ?? 0, 0, ',', '.') }}</div>
                        <div class="text-xs text-gray-500 dark:text-slate-400">Pendapatan</div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-slate-700">
                <a href="/admin/classes" 
                   class="px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-primary dark:text-light 
                          hover:bg-gray-50 dark:hover:bg-slate-800 transition font-medium">
                    Batal
                </a>
                <div class="flex space-x-3">
                    <a href="/admin/classes" 
                       class="px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-primary dark:text-light 
                              hover:bg-gray-50 dark:hover:bg-slate-800 transition font-medium">
                        Kembali
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-accent text-white rounded-lg hover:bg-accent/90 transition font-medium">
                        <i class="fas fa-save mr-2"></i>
                        Update Kelas
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Update class preview dynamically
const gradeSelect = document.querySelector('select[name="grade_id"]');
const majorSelect = document.querySelector('select[name="major_id"]');
const sectionSelect = document.querySelector('select[name="section_id"]');

function updatePreview() {
    const grade = gradeSelect.options[gradeSelect.selectedIndex]?.text || '';
    const major = majorSelect.options[majorSelect.selectedIndex]?.text || '';
    const section = sectionSelect.options[sectionSelect.selectedIndex]?.text || '';
    
    if (grade && major && section) {
        const shortName = major.match(/\(([^)]+)\)/)?.[1] || major;
        document.title = `Edit ${grade} ${shortName} ${section} - School Commerce`;
    }
}

gradeSelect.addEventListener('change', updatePreview);
majorSelect.addEventListener('change', updatePreview);
sectionSelect.addEventListener('change', updatePreview);
</script>
@endsection