<x-app-layout>
    <x-slot name="title">Edit Kelas - {{ $class->full_name }}</x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Edit Kelas: {{ $class->full_name }}</h1>
            </div>

            <!-- Form -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <form action="{{ route('classes.update', $class) }}" method="POST">
                    @csrf @method('PUT')

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
                            <label for="major_id" class="block text-sm font-medium text-gray-700">
                                Jurusan *
                            </label>
                            <select name="major_id" id="major_id" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500">
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
                            <label for="section_id" class="block text-sm font-medium text-gray-700">
                                Rombel *
                            </label>
                            <select name="section_id" id="section_id" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500">
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

                        <!-- Teacher -->
                        <div>
                            <label for="teacher_id" class="block text-sm font-medium text-gray-700">
                                Wali Kelas
                            </label>
                            <select name="teacher_id" id="teacher_id"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Pilih Wali Kelas</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('teacher_id', $class->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('teacher_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-between mt-8">
                        <a href="{{ route('classes.index') }}" 
                          class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition duration-200">
                            Kembali
                        </a>
                        <button type="submit" 
                                class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-200">
                            Update Kelas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>