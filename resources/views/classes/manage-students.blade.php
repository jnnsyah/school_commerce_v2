<x-app-layout>
    <x-slot name="title">Kelola Siswa - {{ $class->full_name }}</x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">Kelola Siswa: {{ $class->full_name }}</h1>
                            <p class="text-gray-600">Pilih siswa yang akan dimasukkan ke kelas ini</p>
                        </div>
                        <div>
                            <a href="{{ route('classes.show', $class) }}" 
                              class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                                Kembali ke Detail
                            </a>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Form -->
                    <div class="bg-white shadow-md rounded-lg p-6">
                        <form action="{{ route('classes.update-students', $class) }}" method="POST">
                            @csrf @method('PUT')

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Pilih Siswa untuk Kelas {{ $class->full_name }}
                                </label>
                                
                                <!-- Search Bar -->
                                <div class="mb-4">
                                    <div class="relative">
                                        <input type="text" 
                                               id="searchStudent" 
                                               placeholder="Cari siswa berdasarkan nama atau NISN..."
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-96 overflow-y-auto p-4 border border-gray-200 rounded-lg" id="studentList">
                                    @foreach($students as $student)
                                    <div class="flex items-center student-item" data-name="{{ strtolower($student->name) }}" data-nisn="{{ strtolower($student->studentProfile->nisn ?? '') }}">
                                        <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" 
                                               id="student_{{ $student->id }}"
                                               {{ $student->studentProfile && $student->studentProfile->class_id == $class->id ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <label for="student_{{ $student->id }}" class="ml-2 text-sm text-gray-700">
                                            {{ $student->name }} 
                                            <span class="text-gray-500">({{ $student->studentProfile->nisn ?? 'N/A' }})</span>
                                            @if($student->studentProfile && $student->studentProfile->class_id == $class->id)
                                                <span class="text-green-600 text-xs ml-1">✓ di kelas ini</span>
                                            @endif
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                
                                @if($students->count() === 0)
                                <p class="text-gray-500 text-center py-4">Tidak ada siswa yang tersedia.</p>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center justify-between mt-6">
                                <a href="{{ route('classes.show', $class) }}" 
                                   class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                                    Batal
                                </a>
                                <button type="submit" 
                                        class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Current Students -->
                    <div class="mt-8 bg-white shadow-md rounded-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-semibold">Siswa Saat Ini di Kelas Ini</h3>
                                
                                <!-- Search for current students -->
                                <div class="w-64">
                                    <input type="text" 
                                           id="searchCurrentStudent" 
                                           placeholder="Cari siswa saat ini..."
                                           class="w-full px-3 py-1 border border-gray-300 rounded-lg text-sm">
                                </div>
                            </div>
                        </div>
                        
                        @if($class->students->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        NISN
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="currentStudentTable">
                                @foreach($class->students as $student)
                                <tr class="current-student-row" data-name="{{ strtolower($student->user->name) }}" data-nisn="{{ strtolower($student->nisn) }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $student->user->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $student->nisn }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $student->user->email }}</div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div class="px-6 py-8 text-center">
                            <p class="text-gray-500">Belum ada siswa di kelas ini.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Search functionality for student list
        document.getElementById('searchStudent').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const studentItems = document.querySelectorAll('.student-item');
            
            studentItems.forEach(item => {
                const name = item.getAttribute('data-name');
                const nisn = item.getAttribute('data-nisn');
                
                if (name.includes(searchTerm) || nisn.includes(searchTerm)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // Search functionality for current students table
        document.getElementById('searchCurrentStudent').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const currentStudentRows = document.querySelectorAll('.current-student-row');
            
            currentStudentRows.forEach(row => {
                const name = row.getAttribute('data-name');
                const nisn = row.getAttribute('data-nisn');
                
                if (name.includes(searchTerm) || nisn.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</x-app-layout>