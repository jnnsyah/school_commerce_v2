<!-- resources/views/layouts/user-app.blade.php -->
<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        // Basic cart functionality as fallback
        const CartManager = {
            // Simple fallback functions
            showAddToCartModal: function(productId) {
                console.log('Add to cart clicked for product:', productId);
                // Fallback: redirect to product detail page
                window.location.href = '/user/products/' + productId;
            },
            
            toggleCart: function() {
                console.log('Toggle cart clicked');
                alert('Fitur keranjang akan tersedia sebentar lagi...');
            },
            
            loadCartData: function() {
                console.log('Loading cart data...');
            }
        };

        // Make functions globally available as fallback
        window.showAddToCartModal = CartManager.showAddToCartModal;
        window.toggleCart = CartManager.toggleCart;
        window.loadCartData = CartManager.loadCartData;
    </script>

    <!-- Load main JavaScript file -->
    <script src="{{ asset('js/user-app.js') }}"></script>

    <!-- Fallback if main JS fails to load -->
    <script>
        window.addEventListener('load', function() {
            // Check if main JS loaded properly
            if (typeof window.showAddToCartModal === 'undefined' || 
                typeof window.showAddToCartModal === 'function' && window.showAddToCartModal.toString().includes('fallback')) {
                console.warn('Main JavaScript failed to load, using fallback functions');
                
                // Override button behaviors
                document.addEventListener('click', function(e) {
                    if (e.target.closest('[onclick*="showAddToCartModal"]')) {
                        e.preventDefault();
                        const match = e.target.closest('[onclick]').getAttribute('onclick').match(/showAddToCartModal\((\d+)\)/);
                        if (match) {
                            const productId = match[1];
                            CartManager.showAddToCartModal(productId);
                        }
                    }
                    
                    if (e.target.closest('#cart-button') || e.target.closest('[onclick*="toggleCart"]')) {
                        e.preventDefault();
                        CartManager.toggleCart();
                    }
                });
            } else {
                console.log('Main JavaScript loaded successfully');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>