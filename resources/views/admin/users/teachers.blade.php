<!-- resources/views/admin/users/teachers.blade.php -->
@extends('layouts.admin-app')

@section('title', 'Kelola Guru - School Commerce')
@section('page-title', 'Manajemen Guru')
@section('page-subtitle', 'Kelola data guru dan wali kelas')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-semibold text-primary dark:text-light">Data Guru</h2>
            <p class="text-sm text-gray-600 dark:text-slate-400 mt-1">
                Total {{ $teachers->total() }} guru terdaftar
            </p>
        </div>
        
        <div class="mt-4 sm:mt-0">
            <a href="/admin/users/create" 
               class="inline-flex items-center px-4 py-2 bg-accent text-white rounded-lg hover:bg-accent/90 transition font-medium">
                <i class="fas fa-plus mr-2"></i>
                Tambah Guru
            </a>
        </div>
    </div>

    <!-- Teachers Table -->
    <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-slate-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Guru
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Role & NIP
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Kelas yang Diampu
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Kontak
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                    @forelse($teachers as $teacher)
                    @php $user = $teacher->user; @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                        <!-- Teacher Info -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                                        <i class="fas fa-chalkboard-teacher text-blue-600 dark:text-blue-400"></i>
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

                        <!-- Role & NIP -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col space-y-1">
                                @include('components.admin.user-role-badge', ['user' => $user])
                                <div class="text-sm text-gray-500 dark:text-slate-400">
                                    NIP: {{ $teacher->nip ?? '-' }}
                                </div>
                            </div>
                        </td>

                        <!-- Classes -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->classes->count() > 0)
                                <div class="space-y-1">
                                    @foreach($user->classes as $class)
                                    <span class="inline-block px-2 py-1 text-xs bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300 rounded-full">
                                        {{ $class->grade->name }} {{ $class->major->short_name }} {{ $class->section->name }}
                                    </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-sm text-gray-500 dark:text-slate-400">-</span>
                            @endif
                        </td>

                        <!-- Contact -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-primary dark:text-light">{{ $user->email }}</div>
                            @if($user->no_hp)
                            <div class="text-sm text-gray-500 dark:text-slate-400">{{ $user->no_hp }}</div>
                            @endif
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
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus guru ini?')"
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
                            <i class="fas fa-chalkboard-teacher text-4xl text-gray-400 dark:text-slate-600 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-light mb-2">Tidak ada guru</h3>
                            <p class="text-gray-500 dark:text-slate-400 mb-4">Belum ada guru yang terdaftar</p>
                            <a href="/admin/users/create" 
                               class="inline-flex items-center px-4 py-2 bg-accent text-white rounded-lg hover:bg-accent/90 transition">
                                <i class="fas fa-plus mr-2"></i>
                                Tambah Guru Pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($teachers->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700">
            {{ $teachers->links('components.shared.pagination') }}
        </div>
        @endif
    </div>
</div>
@endsection