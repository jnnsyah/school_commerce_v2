<x-app-layout>
    <x-slot name="title">Detail Kelas - {{ $class->full_name }}</x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">{{ $class->full_name }}</h1>
                    <p class="text-gray-600">Detail informasi kelas dan daftar siswa</p>
                </div>
                
                <div class="flex space-x-2">
                    @can('class.edit')
                    <a href="{{ route('classes.edit', $class) }}" 
                      class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition duration-200">
                        Edit Kelas
                    </a>
                    @endcan
                    
                    <a href="{{ route('classes.index') }}" 
                      class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition duration-200">
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Class Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Informasi Kelas</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tingkat:</span>
                            <span class="font-medium">{{ $class->grade->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Jurusan:</span>
                            <span class="font-medium">{{ $class->major->name }} ({{ $class->major->short_name }})</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Rombel:</span>
                            <span class="font-medium">{{ $class->section->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Wali Kelas:</span>
                            <span class="font-medium">
                                @if($class->teacher)
                                    {{ $class->teacher->name }}
                                @else
                                    <span class="text-gray-400">Belum ada wali kelas</span>
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Jumlah Siswa:</span>
                            <span class="font-medium">{{ $class->student_count }} Siswa</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                    <div class="space-y-2">
                        @can('class.edit')
                        <a href="{{ route('classes.manage-students', $class) }}" 
                          class="block w-full text-center bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 transition duration-200">
                            Kelola Siswa
                        </a>
                        @endcan
                        
                        <!-- Add more quick actions here -->
                    </div>
                </div>
            </div>

            <!-- Students List -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold">Daftar Siswa</h3>
                </div>
                
                @if($class->students->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama Siswa
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                NISN
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Admin Kelas
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($class->students as $student)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $student->user->name }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $student->user->username }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $student->nisn }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($student->is_admin_class)
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">
                                        Ya
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded-full">
                                        Tidak
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="px-6 py-8 text-center">
                    <p class="text-gray-500">Belum ada siswa di kelas ini.</p>
                    @can('class.edit')
                    <a href="{{ route('classes.manage-students', $class) }}" 
                      class="mt-2 inline-block text-indigo-600 hover:text-indigo-900">
                        Tambah siswa sekarang
                    </a>
                    @endcan
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>