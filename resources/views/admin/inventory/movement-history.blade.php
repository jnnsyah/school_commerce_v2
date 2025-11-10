<!-- resources/views/admin/inventory/movement-history.blade.php -->
@extends('layouts.admin-app')

@section('title', 'Riwayat Stok - School Commerce')
@section('page-title', 'Riwayat Pergerakan Stok')
@section('page-subtitle', 'Lihat history penyesuaian dan pergerakan stok')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-semibold text-primary dark:text-light">Riwayat Pergerakan Stok</h2>
            <p class="text-sm text-gray-600 dark:text-slate-400 mt-1">
                Track semua perubahan stok dalam sistem
            </p>
        </div>
        
        <!-- Filters -->
        <div class="flex space-x-3 mt-4 sm:mt-0">
            <form action="{{ route('admin.inventory.movement-history') }}" method="GET" class="flex space-x-3">
                <input type="date" 
                       name="date_from" 
                       value="{{ $filters['date_from'] ?? '' }}"
                       class="px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm
                              bg-white dark:bg-secondary text-primary dark:text-light">
                <input type="date" 
                       name="date_to" 
                       value="{{ $filters['date_to'] ?? '' }}"
                       class="px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm
                              bg-white dark:bg-secondary text-primary dark:text-light">
                <button type="submit" 
                        class="px-4 py-2 bg-accent text-white rounded-lg hover:bg-accent/90 transition text-sm">
                    Filter
                </button>
                <a href="{{ route('admin.inventory.movement-history') }}" 
                   class="px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-primary dark:text-light 
                          hover:bg-gray-50 dark:hover:bg-slate-800 transition text-sm">
                    Reset
                </a>
            </form>
        </div>
    </div>

    <!-- Movement History -->
    <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-slate-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Item
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Tipe
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Perubahan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Catatan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            User
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Tanggal
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                    <!-- Product Stock Movements -->
                    @foreach($movementHistory['products'] as $movement)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 bg-blue-100 dark:bg-blue-900/20 rounded flex items-center justify-center">
                                    <i class="fas fa-box text-blue-600 dark:text-blue-400 text-xs"></i>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-primary dark:text-light">
                                        {{ $movement->product->name }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-slate-400">
                                        Produk
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $movement->referenceType->description == 'Adjustment' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300' : 
                                   ($movement->referenceType->description == 'Order' ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300' : 
                                   'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300') }}">
                                {{ $movement->referenceType->description }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold 
                                {{ $movement->qty > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $movement->qty > 0 ? '+' : '' }}{{ $movement->qty }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-slate-400">
                            {{ $movement->note ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-slate-400">
                            {{ $movement->user->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-slate-400">
                            {{ $movement->created_at->format('d M Y H:i') }}
                        </td>
                    </tr>
                    @endforeach

                    <!-- Variant Stock Movements -->
                    @foreach($movementHistory['variants'] as $movement)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 bg-green-100 dark:bg-green-900/20 rounded flex items-center justify-center">
                                    <i class="fas fa-layer-group text-green-600 dark:text-green-400 text-xs"></i>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-primary dark:text-light">
                                        {{ $movement->variant->product->name }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-slate-400">
                                        {{ $movement->variant->name }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $movement->referenceType->description == 'Adjustment' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300' : 
                                   ($movement->referenceType->description == 'Order' ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300' : 
                                   'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300') }}">
                                {{ $movement->referenceType->description }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold 
                                {{ $movement->qty > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $movement->qty > 0 ? '+' : '' }}{{ $movement->qty }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-slate-400">
                            {{ $movement->note ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-slate-400">
                            {{ $movement->user->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-slate-400">
                            {{ $movement->created_at->format('d M Y H:i') }}
                        </td>
                    </tr>
                    @endforeach

                    <!-- Extra Stock Movements -->
                    @foreach($movementHistory['extras'] as $movement)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 bg-purple-100 dark:bg-purple-900/20 rounded flex items-center justify-center">
                                    <i class="fas fa-plus-circle text-purple-600 dark:text-purple-400 text-xs"></i>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-primary dark:text-light">
                                        {{ $movement->extra->product->name }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-slate-400">
                                        {{ $movement->extra->name }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $movement->referenceType->description == 'Adjustment' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300' : 
                                   ($movement->referenceType->description == 'Order' ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300' : 
                                   'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300') }}">
                                {{ $movement->referenceType->description }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold 
                                {{ $movement->qty > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $movement->qty > 0 ? '+' : '' }}{{ $movement->qty }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-slate-400">
                            {{ $movement->note ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-slate-400">
                            {{ $movement->user->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-slate-400">
                            {{ $movement->created_at->format('d M Y H:i') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if(empty($movementHistory['products']) && empty($movementHistory['variants']) && empty($movementHistory['extras']))
        <div class="px-6 py-12 text-center text-gray-500 dark:text-slate-400">
            <i class="fas fa-history text-3xl mb-2"></i>
            <p>Tidak ada riwayat pergerakan stok</p>
        </div>
        @endif
    </div>
</div>
@endsection