<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Kewirausahaan Sekolah')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @stack('styles')
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        @include('layouts.sidebar')
        
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <!-- Header -->
            @include('layouts.header')
            
            <!-- Main Content -->
            <div class="container-fluid px-4">
                <!-- Flash Messages -->
                @include('components.ui.alert')
                
                <!-- Page Header -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">@yield('page-title')</h1>
                    <div class="page-actions">
                        @yield('page-actions')
                    </div>
                </div>
                
                <!-- Page Content -->
                <main>
                    @yield('content')
                </main>
            </div>
        </div>
    </div>

    <!-- Bootstrap & jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>

<footer class="footer mt-auto py-3 bg-light border-top">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6">
                <span class="text-muted">
                    &copy; {{ date('Y') }} Sistem Kewirausahaan Sekolah. All rights reserved.
                </span>
            </div>
            <div class="col-md-6 text-end">
                <span class="text-muted">
                    v1.0.0 | 
                    <i class="fas fa-heart text-danger"></i> 
                    Dibuat dengan Laravel
                </span>
            </div>
        </div>
    </div>
</footer>
</html>