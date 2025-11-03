<x-app-layout>
    <x-slot name="title">Tambah Kelas Baru</x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Tambah Kelas Baru</h1>
                <p class="text-gray-600">Buat kelas baru dengan mengisi form di bawah</p>
            </div>

            <!-- Form -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <form action="{{ route('classes.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Grade -->
                        <div>
                            <label for="grade_id" class="block text-sm font-medium text-gray-700">
                                Tingkat Kelas *
                            </label>
                            <select name="grade_id" id="grade_id" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500">
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
                            <label for="major_id" class="block text-sm font-medium text-gray-700">
                                Jurusan *
                            </label>
                            <select name="major_id" id="major_id" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500">
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
                            <label for="section_id" class="block text-sm font-medium text-gray-700">
                                Rombel *
                            </label>
                            <select name="section_id" id="section_id" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500">
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

                        <!-- Teacher -->
                        <div>
                            <label for="teacher_id" class="block text-sm font-medium text-gray-700">
                                Wali Kelas
                            </label>
                            <select name="teacher_id" id="teacher_id"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Pilih Wali Kelas</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('teacher_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Preview -->
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Preview Kelas:</h3>
                        <p id="classPreview" class="text-lg font-semibold text-gray-800">
                            Pilih tingkat, jurusan, dan rombel untuk melihat preview
                        </p>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-between mt-8">
                        <a href="{{ route('classes.index') }}" 
                          class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition duration-200">
                            Kembali
                        </a>
                        <button type="submit" 
                                class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-200">
                            Simpan Kelas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Live preview for class name
        document.addEventListener('DOMContentLoaded', function() {
            const gradeSelect = document.getElementById('grade_id');
            const majorSelect = document.getElementById('major_id');
            const sectionSelect = document.getElementById('section_id');
            const preview = document.getElementById('classPreview');

            function updatePreview() {
                const grade = gradeSelect.options[gradeSelect.selectedIndex]?.text || '';
                const major = majorSelect.options[majorSelect.selectedIndex]?.dataset?.short || 
                            majorSelect.options[majorSelect.selectedIndex]?.text || '';
                const section = sectionSelect.options[sectionSelect.selectedIndex]?.text || '';

                if (grade && major && section) {
                    preview.textContent = `${grade} ${major} ${section}`;
                } else {
                    preview.textContent = 'Pilih tingkat, jurusan, dan rombel untuk melihat preview';
                }
            }

            // Add data-short attribute to major options
            @foreach($majors as $major)
                document.querySelector(`#major_id option[value="{{ $major->id }}"]`).dataset.short = "{{ $major->short_name }}";
            @endforeach

            gradeSelect.addEventListener('change', updatePreview);
            majorSelect.addEventListener('change', updatePreview);
            sectionSelect.addEventListener('change', updatePreview);
        });
    </script>
    @endpush
</x-app-layout>