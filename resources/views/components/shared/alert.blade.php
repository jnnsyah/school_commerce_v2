<!-- resources/views/components/shared/alert.blade.php -->
@if(session('status') || session('success') || session('error') || $errors->any())
<div class="container mx-auto px-4 py-3">
    @if(session('status'))
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-4">
            <div class="flex items-center">
                <i class="fas fa-info-circle text-blue-500 mr-3"></i>
                <p class="text-blue-700 dark:text-blue-300 text-sm">{{ session('status') }}</p>
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 mb-4">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                <p class="text-green-700 dark:text-green-300 text-sm">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-4">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                <p class="text-red-700 dark:text-red-300 text-sm">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-4">
            <div class="flex items-start">
                <i class="fas fa-exclamation-triangle text-red-500 mr-3 mt-0.5"></i>
                <div>
                    <p class="text-red-700 dark:text-red-300 font-medium mb-2">Terjadi kesalahan:</p>
                    <ul class="text-red-600 dark:text-red-400 text-sm list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif
</div>
@endif