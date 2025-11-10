<!-- resources/views/admin/classes/create.blade.php -->
@extends('layouts.admin-app')

@section('title', 'Tambah Kelas - School Commerce')
@section('page-title', 'Tambah Kelas Baru')
@section('page-subtitle', 'Buat struktur kelas baru')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700">
        <!-- Form Header -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700">
            <h3 class="text-lg font-semibold text-primary dark:text-light">Informasi Kelas</h3>
            <p class="text-sm text-gray-600 dark:text-slate-400 mt-1">
                Isi detail kelas dengan lengkap
            </p>
        </div>

        <!-- Class Form -->
        <form action="/admin/classes" method="POST" class="p-6 space-y-6">
            @csrf

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
                            <option value="{{ $grade->id }}" {{ old('grade_id') == $grade->id ? 'selected' : '' }}>
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
                            <option value="{{ $major->id }}" {{ old('major_id') == $major->id ? 'selected' : '' }}>
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
                            <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>
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
                        <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
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

            <!-- Class Preview -->
            <div class="bg-gray-50 dark:bg-slate-800 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Preview Kelas</h4>
                <div id="class-preview" class="text-lg font-semibold text-primary dark:text-light">
                    Pilih tingkat, jurusan, dan rombel untuk melihat preview
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-slate-700">
                <a href="/admin/classes" 
                   class="px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-primary dark:text-light 
                          hover:bg-gray-50 dark:hover:bg-slate-800 transition font-medium">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-accent text-white rounded-lg hover:bg-accent/90 transition font-medium">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Kelas
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Update class preview dynamically
const gradeSelect = document.querySelector('select[name="grade_id"]');
const majorSelect = document.querySelector('select[name="major_id"]');
const sectionSelect = document.querySelector('select[name="section_id"]');
const preview = document.getElementById('class-preview');

function updatePreview() {
    const grade = gradeSelect.options[gradeSelect.selectedIndex]?.text || '';
    const major = majorSelect.options[majorSelect.selectedIndex]?.text || '';
    const section = sectionSelect.options[sectionSelect.selectedIndex]?.text || '';
    
    if (grade && major && section) {
        // Extract short name from major (format: "Name (Short)")
        const shortName = major.match(/\(([^)]+)\)/)?.[1] || major;
        preview.textContent = `${grade} ${shortName} ${section}`;
    } else {
        preview.textContent = 'Pilih tingkat, jurusan, dan rombel untuk melihat preview';
    }
}

gradeSelect.addEventListener('change', updatePreview);
majorSelect.addEventListener('change', updatePreview);
sectionSelect.addEventListener('change', updatePreview);

// Initial preview
updatePreview();
</script>
@endsection