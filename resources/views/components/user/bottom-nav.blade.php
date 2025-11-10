<!-- resources/views/components/user/bottom-nav.blade.php -->
<nav class="bottom-nav bg-white dark:bg-secondary border-t border-gray-200 dark:border-slate-800 fixed bottom-0 left-0 right-0 z-50">
    <div class="container mx-auto">
        <div class="flex justify-around items-center py-3">
            <!-- Products -->
            <a href="/user/products" 
               class="flex flex-col items-center space-y-1 text-primary dark:text-light hover:text-accent transition {{ request()->is('user/products*') ? 'text-accent' : '' }}">
                <i class="fas fa-store text-lg"></i>
                <span class="text-xs font-medium">Products</span>
            </a>

            <!-- Orders -->
            <a href="/user/orders" 
               class="flex flex-col items-center space-y-1 text-primary dark:text-light hover:text-accent transition {{ request()->is('user/orders*') ? 'text-accent' : '' }}">
                <i class="fas fa-clipboard-list text-lg"></i>
                <span class="text-xs font-medium">Orders</span>
            </a>

            <!-- Profile -->
            <a href="/user/profile" 
               class="flex flex-col items-center space-y-1 text-primary dark:text-light hover:text-accent transition {{ request()->is('user/profile*') || request()->is('profile*') ? 'text-accent' : '' }}">
                <i class="fas fa-user text-lg"></i>
                <span class="text-xs font-medium">Profile</span>
            </a>
        </div>
    </div>
</nav>