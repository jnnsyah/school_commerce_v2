<!-- resources/views/layouts/user-app.blade.php -->
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

        .bottom-nav {
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        }

        .cart-modal {
            transition: transform 0.3s ease-out;
        }

        .cart-modal.hidden {
            transform: translateY(100%);
        }
    </style>
</head>
<body class="min-h-screen bg-white dark:bg-primary">
    <!-- Header -->
    @include('partials.user-header')

    <!-- Main Content -->
    <main class="min-h-screen pb-20">
        @yield('content')
    </main>

    <!-- Bottom Navigation -->
    @include('components.user.bottom-nav')

    <!-- Cart Modal -->
    @include('components.user.cart-modal')

    <!-- Scripts -->
    <script>
        // Cart functionality
        let cart = {
            items: [],
            total: 0,
            
            updateCart() {
                // Update cart badge and modal
                const cartCount = document.getElementById('cart-count');
                const cartTotal = document.getElementById('cart-total');
                
                if (cartCount) cartCount.textContent = this.items.length;
                if (cartTotal) cartTotal.textContent = this.formatPrice(this.total);
            },

            formatPrice(price) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(price);
            }
        };

        // Toggle cart modal
        function toggleCart() {
            const modal = document.getElementById('cart-modal');
            modal.classList.toggle('hidden');
        }

        // Close cart when clicking outside
        document.addEventListener('click', function(e) {
            const cartModal = document.getElementById('cart-modal');
            const cartButton = document.getElementById('cart-button');
            
            if (!cartModal.contains(e.target) && !cartButton.contains(e.target)) {
                cartModal.classList.add('hidden');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>