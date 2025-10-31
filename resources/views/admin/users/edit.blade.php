<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h1 class="text-2xl font-bold text-gray-800">Edit User: {{ $user->name }}</h1>
                    </div>

                    <div class="bg-white shadow-md rounded-lg p-6">
                        <form action="{{ route('admin.users.update', $user) }}" method="POST">
                            @csrf @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                                          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                    @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                                    <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" 
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                    @error('username') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                    @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Roles</label>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                    @foreach($roles as $role)
                                    <div class="flex items-center">
                                        <input type="checkbox" name="roles[]" value="{{ $role->name }}" 
                                               id="role_{{ $role->id }}" 
                                               {{ $user->hasRole($role->name) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <label for="role_{{ $role->id }}" class="ml-2 text-sm text-gray-700">
                                            {{ $role->name }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                @error('roles') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="flex items-center justify-between mt-8">
                                <a href="{{ route('admin.users.index') }}" 
                                  class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                                    Kembali
                                </a>
                                <button type="submit" 
                                        class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                                    Update User
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>