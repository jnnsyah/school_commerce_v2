<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User\User;
use App\Models\Academic\SchoolClass;
use App\Models\Product\Product;
use App\Models\Order\Order;
use App\Models\Product\ProductStatus;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $data = [];

        // Super Admin & Admin Dashboard
        if ($user->hasRole(['super_admin', 'admin'])) {
            $data = [
                'totalUsers' => User::count(),
                'totalClasses' => SchoolClass::count(),
                'totalProducts' => Product::count(),
                'totalOrders' => Order::count(),
                'pendingProducts' => Product::where('status_id', ProductStatus::PENDING)->count(),
                'recentOrders' => Order::with(['user', 'status'])->latest()->take(5)->get(),
            ];
        }

        // Guru PKWU Dashboard
        elseif ($user->hasRole('guru_pkwu')) {
            $data = [
                'pendingApprovals' => Product::with(['class', 'category'])
                    ->where('status_id', ProductStatus::PENDING)
                    ->count(),
                'totalProducts' => Product::count(),
                'approvedProducts' => Product::where('status_id', ProductStatus::APPROVED)->count(),
                'recentOrders' => Order::with(['user', 'status'])->latest()->take(5)->get(),
            ];
        }

        // Wali Kelas Dashboard
        elseif ($user->hasRole('wali_kelas')) {
            $class = SchoolClass::where('teacher_id', $user->id)->first();
            
            if ($class) {
                $data = [
                    'class' => $class,
                    'classProducts' => Product::where('class_id', $class->class_id)->count(),
                    'pendingProducts' => Product::where('class_id', $class->class_id)
                        ->where('status_id', ProductStatus::PENDING)
                        ->count(),
                    'classStudents' => $class->students()->count(),
                    'classOrders' => Order::whereHas('items.product', function($query) use ($class) {
                        $query->where('class_id', $class->class_id);
                    })->count(),
                ];
            }
        }

        // Student & Guru Biasa Dashboard
        else {
            $data = [
                'recentProducts' => Product::with(['class', 'category'])
                    ->where('status_id', ProductStatus::APPROVED)
                    ->latest()
                    ->take(6)
                    ->get(),
                'myOrders' => Order::where('user_id', $user->id)
                    ->with('status')
                    ->latest()
                    ->take(5)
                    ->get(),
            ];
        }

        return view('dashboard.index', compact('data'));
    }
}