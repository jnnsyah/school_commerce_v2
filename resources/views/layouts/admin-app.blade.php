<!-- resources/views/layouts/admin-app.blade.php -->
<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Merchant Dashboard - School Commerce')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#0a192f',
                        secondary: '#112240', 
                        accent: '#00bba7',
                        light: '#ccd6f6',
                        slate: '#8892b0',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                },
            },
        }
    </script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #0a192f;
            --secondary: #112240;
            --accent: #00bba7;
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --text-primary: #0a192f;
            --text-secondary: #475569;
            --border: #e2e8f0;
        }

        .dark {
            --bg-primary: #0a192f;
            --bg-secondary: #112240;
            --text-primary: #ccd6f6;
            --text-secondary: #8892b0;
            --border: #1e293b;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            transition: all 0.3s ease;
        }

        .sidebar {
            transition: transform 0.3s ease;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.open {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50 dark:bg-primary">
    <div class="flex h-screen">
        <!-- Sidebar -->
        @include('partials.admin-sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            @include('partials.admin-header')

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Flash Messages -->
                @include('components.shared.alert')
                
                <!-- Page Content -->
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mobile Menu Button -->
    <div class="lg:hidden">
        <button id="mobile-menu-button" class="fixed bottom-4 right-4 z-50 w-12 h-12 bg-accent text-white rounded-full shadow-lg flex items-center justify-center">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('open');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const mobileBtn = document.getElementById('mobile-menu-button');
            
            if (window.innerWidth < 768 && !sidebar.contains(e.target) && !mobileBtn.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>