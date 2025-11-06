<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use App\Models\Product\Product;
use App\Models\Academic\SchoolClass;
use App\Models\User\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:report.sales')->only('sales');
        $this->middleware('permission:report.products')->only('products');
        $this->middleware('permission:report.students')->only('students');
        $this->middleware('permission:report.financial')->only('financial');
    }

    public function sales(Request $request)
    {
        $dateRange = $this->getDateRange($request);
        
        $query = Order::with(['user', 'items.product'])
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status_id', $request->status);
        }

        $orders = $query->get();
        
        $salesData = [
            'total_orders' => $orders->count(),
            'completed_orders' => $orders->where('status_id', 4)->count(),
            'total_revenue' => $orders->where('status_id', 4)->sum('total_amount'),
            'average_order_value' => $orders->where('status_id', 4)->avg('total_amount') ?? 0,
        ];

        // Daily sales trend
        $dailySales = Order::where('status_id', 4)
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as revenue, COUNT(*) as orders')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top selling classes
        $topClasses = SchoolClass::withCount(['products' => function($query) use ($dateRange) {
                $query->whereHas('orderItems.order', function($q) use ($dateRange) {
                    $q->where('status_id', 4)
                      ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
                });
            }])
            ->withSum(['products as revenue' => function($query) use ($dateRange) {
                $query->whereHas('orderItems.order', function($q) use ($dateRange) {
                    $q->where('status_id', 4)
                      ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
                });
            }], 'order_items.subtotal')
            ->orderByDesc('revenue')
            ->take(10)
            ->get();

        return view('reports.sales', compact(
            'salesData', 
            'dailySales', 
            'topClasses',
            'dateRange'
        ));
    }

    public function products(Request $request)
    {
        $dateRange = $this->getDateRange($request);

        $topProducts = Product::with(['category', 'class'])
            ->withCount(['orderItems as total_sold' => function($query) use ($dateRange) {
                $query->whereHas('order', function($q) use ($dateRange) {
                    $q->where('status_id', 4)
                      ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
                });
            }])
            ->withSum(['orderItems as total_revenue' => function($query) use ($dateRange) {
                $query->whereHas('order', function($q) use ($dateRange) {
                    $q->where('status_id', 4)
                      ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
                });
            }], 'subtotal')
            ->where('status_id', 2) // Approved products only
            ->orderByDesc('total_sold')
            ->paginate(15);

        $productPerformance = [
            'total_products' => Product::where('status_id', 2)->count(),
            'active_products' => Product::where('status_id', 2)->has('orderItems')->count(),
            'total_items_sold' => $topProducts->sum('total_sold'),
            'total_product_revenue' => $topProducts->sum('total_revenue'),
        ];

        return view('reports.products', compact(
            'topProducts',
            'productPerformance',
            'dateRange'
        ));
    }

    public function students(Request $request)
    {
        $dateRange = $this->getDateRange($request);

        $topStudents = User::role('student')
            ->with(['student.class'])
            ->withCount(['orders as total_orders' => function($query) use ($dateRange) {
                $query->where('status_id', 4)
                      ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
            }])
            ->withSum(['orders as total_spent' => function($query) use ($dateRange) {
                $query->where('status_id', 4)
                      ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
            }], 'total_amount')
            ->has('orders')
            ->orderByDesc('total_spent')
            ->paginate(15);

        $studentStats = [
            'total_students' => User::role('student')->count(),
            'active_buyers' => User::role('student')->has('orders')->count(),
            'average_order_value' => $topStudents->avg('total_spent') ?? 0,
        ];

        return view('reports.students', compact(
            'topStudents',
            'studentStats',
            'dateRange'
        ));
    }

    public function financial(Request $request)
    {
        $dateRange = $this->getDateRange($request);

        $revenueByClass = SchoolClass::with(['grade', 'major'])
            ->withSum(['products as class_revenue' => function($query) use ($dateRange) {
                $query->whereHas('orderItems.order', function($q) use ($dateRange) {
                    $q->where('status_id', 4)
                      ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
                });
            }], 'order_items.subtotal')
            ->having('class_revenue', '>', 0)
            ->orderByDesc('class_revenue')
            ->get();

        $monthlyRevenue = Order::where('status_id', 4)
            ->whereBetween('created_at', [$dateRange['start']->subYear(), $dateRange['end']])
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(total_amount) as revenue')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $financialSummary = [
            'total_revenue' => $revenueByClass->sum('class_revenue'),
            'average_revenue_per_class' => $revenueByClass->avg('class_revenue') ?? 0,
            'top_performing_class' => $revenueByClass->first(),
            'total_transactions' => Order::where('status_id', 4)
                ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                ->count(),
        ];

        return view('reports.financial', compact(
            'revenueByClass',
            'monthlyRevenue',
            'financialSummary',
            'dateRange'
        ));
    }

    private function getDateRange($request)
    {
        $startDate = $request->get('start_date') 
            ? Carbon::parse($request->get('start_date'))
            : Carbon::now()->subMonth();

        $endDate = $request->get('end_date')
            ? Carbon::parse($request->get('end_date'))->endOfDay()
            : Carbon::now();

        return [
            'start' => $startDate,
            'end' => $endDate,
        ];
    }
}