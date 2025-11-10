<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Redirect based on role
        if ($user->hasRole(['student', 'guru_biasa'])) {
            return redirect()->route('user.products.index');
        } else {
            return redirect()->route('admin.dashboard');
        }
    }
    public function adminIndex()
    {
        // Only for admin, guru_pkwu, wali_kelas
        if (!auth()->user()->hasRole(['admin', 'guru_pkwu', 'wali_kelas'])) {
            abort(403);
        }

        $user = auth()->user();
        $data = [];

        // Super Admin & Admin Dashboard
        if ($user->hasRole(['super_admin', 'admin'])) {
            $data = [
                'totalUsers' => \App\Models\User\User::count(),
                'totalClasses' => \App\Models\Academic\SchoolClass::count(),
                'totalProducts' => \App\Models\Product\Product::count(),
                'totalOrders' => \App\Models\Order\Order::count(),
                'totalRevenue' => \App\Models\Order\Order::where('status_id', 4)->sum('total_amount'), // Completed orders
                'pendingProducts' => \App\Models\Product\Product::where('status_id', \App\Models\Product\ProductStatus::PENDING)->count(),
                'lowStockCount' => \App\Models\Product\ProductVariant::where('stock_at', '<=', 5)->where('stock_at', '>', 0)->count(),
                'recentOrders' => \App\Models\Order\Order::with(['user', 'status'])->latest()->take(5)->get(),
                'recentActivities' => $this->getRecentActivities(),
                'storageUsage' => '65%', // This can be calculated from actual storage
            ];
        }

        // Guru PKWU Dashboard
        elseif ($user->hasRole('guru_pkwu')) {
            $data = [
                'pendingApprovals' => \App\Models\Product\Product::with(['class', 'category'])
                    ->where('status_id', \App\Models\Product\ProductStatus::PENDING)
                    ->count(),
                'totalProducts' => \App\Models\Product\Product::count(),
                'approvedProducts' => \App\Models\Product\Product::where('status_id', \App\Models\Product\ProductStatus::APPROVED)->count(),
                'rejectedProducts' => \App\Models\Product\Product::where('status_id', \App\Models\Product\ProductStatus::REJECTED)->count(),
                'totalOrders' => \App\Models\Order\Order::count(),
                'recentOrders' => \App\Models\Order\Order::with(['user', 'status'])->latest()->take(5)->get(),
                'recentActivities' => $this->getRecentActivities(),
            ];
        }

        // Wali Kelas Dashboard
        elseif ($user->hasRole('wali_kelas')) {
            $class = \App\Models\Academic\SchoolClass::where('teacher_id', $user->id)->first();
            
            if ($class) {
                $data = [
                    'class' => $class,
                    'classProducts' => \App\Models\Product\Product::where('class_id', $class->class_id)->count(),
                    'pendingProducts' => \App\Models\Product\Product::where('class_id', $class->class_id)
                        ->where('status_id', \App\Models\Product\ProductStatus::PENDING)
                        ->count(),
                    'approvedProducts' => \App\Models\Product\Product::where('class_id', $class->class_id)
                        ->where('status_id', \App\Models\Product\ProductStatus::APPROVED)
                        ->count(),
                    'classStudents' => $class->students()->count(),
                    'classOrders' => \App\Models\Order\Order::whereHas('items.product', function($query) use ($class) {
                        $query->where('class_id', $class->class_id);
                    })->count(),
                    'classRevenue' => \App\Models\Order\Order::whereHas('items.product', function($query) use ($class) {
                        $query->where('class_id', $class->class_id);
                    })->where('status_id', 4)->sum('total_amount'),
                    'recentOrders' => \App\Models\Order\Order::whereHas('items.product', function($query) use ($class) {
                        $query->where('class_id', $class->class_id);
                    })->with(['user', 'status'])->latest()->take(5)->get(),
                    'recentActivities' => $this->getRecentActivities(),
                ];
            }
        }

        return view('admin.dashboard.index', compact('data'));
    }

    private function getRecentActivities()
    {
        $activities = [];
        
        // Recent products
        $recentProducts = \App\Models\Product\Product::with('class')
            ->latest()
            ->take(3)
            ->get();
            
        foreach ($recentProducts as $product) {
            $activities[] = [
                'icon' => 'fa-box',
                'description' => "Produk {$product->name} dibuat oleh {$product->class->grade->name} {$product->class->major->short_name}",
                'time' => $product->created_at->diffForHumans()
            ];
        }
        
        // Recent orders
        $recentOrders = \App\Models\Order\Order::with('user')
            ->latest()
            ->take(2)
            ->get();
            
        foreach ($recentOrders as $order) {
            $activities[] = [
                'icon' => 'fa-shopping-cart',
                'description' => "Pesanan baru #{$order->invoice_number} dari {$order->user->name}",
                'time' => $order->created_at->diffForHumans()
            ];
        }
        
        return $activities;
    }
}