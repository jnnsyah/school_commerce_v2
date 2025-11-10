<!-- resources/views/layouts/guest.blade.php -->
<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'School Commerce')</title>
    
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

        /* Mobile First Optimizations */
        @media (max-width: 640px) {
            .container-mobile {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            
            .input-mobile {
                font-size: 16px; /* Prevent zoom on iOS */
            }
        }

        /* Smooth transitions */
        * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50 dark:bg-primary">
    <!-- Main Content -->
    <main class="min-h-screen flex items-center justify-center py-8 px-4 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    <!-- Flash Messages -->
    @include('components.shared.alert')

    <script>
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                }, 5000);
            });
        });

        // Theme toggle for auth pages
        function toggleTheme() {
            const html = document.documentElement;
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                html.classList.add('light');
                localStorage.theme = 'light';
            } else {
                html.classList.remove('light');
                html.classList.add('dark');
                localStorage.theme = 'dark';
            }
        }

        // Initialize theme
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.add('light');
        }
    </script>
</body>
</html>