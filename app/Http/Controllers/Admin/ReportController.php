<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use App\Models\Product\Product;
use App\Models\User\User;
use App\Models\Academic\SchoolClass;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:report.sales')->only('sales', 'exportSales');
        $this->middleware('permission:report.products')->only('products', 'exportProducts');
        $this->middleware('permission:report.financial')->only('financial');
        $this->middleware('permission:report.students')->only('students');
    }

    public function sales(Request $request)
    {
        $dateRange = $this->getDateRange($request);
        
        $salesData = Order::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->selectRaw('
                COUNT(*) as total_orders,
                SUM(total_amount) as total_revenue,
                AVG(total_amount) as average_order_value,
                COUNT(DISTINCT user_id) as unique_customers
            ')
            ->first();

        $dailySales = Order::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as orders, SUM(total_amount) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.order_id')
            ->whereBetween('orders.created_at', [$dateRange['start'], $dateRange['end']])
            ->selectRaw('products.name, SUM(order_items.qty) as total_sold, SUM(order_items.subtotal) as revenue')
            ->groupBy('products.product_id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        return view('admin.reports.sales', compact('salesData', 'dailySales', 'topProducts', 'dateRange'));
    }

    public function products(Request $request)
    {
        $dateRange = $this->getDateRange($request);

        $productPerformance = Product::with(['category', 'class'])
            ->withCount(['orderItems as total_sold' => function($query) use ($dateRange) {
                $query->join('orders', 'order_items.order_id', '=', 'orders.order_id')
                      ->whereBetween('orders.created_at', [$dateRange['start'], $dateRange['end']]);
            }])
            ->withSum(['orderItems as total_revenue' => function($query) use ($dateRange) {
                $query->join('orders', 'order_items.order_id', '=', 'orders.order_id')
                      ->whereBetween('orders.created_at', [$dateRange['start'], $dateRange['end']]);
            }], 'subtotal')
            ->orderByDesc('total_sold')
            ->paginate(20);

        $categoryPerformance = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.product_id')
            ->join('product_categories', 'products.category_id', '=', 'product_categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.order_id')
            ->whereBetween('orders.created_at', [$dateRange['start'], $dateRange['end']])
            ->selectRaw('product_categories.name, SUM(order_items.qty) as total_sold, SUM(order_items.subtotal) as revenue')
            ->groupBy('product_categories.id', 'product_categories.name')
            ->orderByDesc('revenue')
            ->get();

        return view('admin.reports.products', compact('productPerformance', 'categoryPerformance', 'dateRange'));
    }

    public function financial(Request $request)
    {
        $dateRange = $this->getDateRange($request);

        $financialData = Order::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->selectRaw('
                status_id,
                COUNT(*) as order_count,
                SUM(total_amount) as total_amount
            ')
            ->groupBy('status_id')
            ->get();

        $monthlyRevenue = Order::whereYear('created_at', Carbon::now()->year)
            ->selectRaw('MONTH(created_at) as month, SUM(total_amount) as revenue')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $classRevenue = DB::table('orders')
            ->join('order_items', 'orders.order_id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.product_id')
            ->join('classes', 'products.class_id', '=', 'classes.class_id')
            ->whereBetween('orders.created_at', [$dateRange['start'], $dateRange['end']])
            ->selectRaw('classes.grade_id, classes.major_id, classes.section_id, SUM(order_items.subtotal) as revenue')
            ->groupBy('classes.grade_id', 'classes.major_id', 'classes.section_id')
            ->orderByDesc('revenue')
            ->get();

        return view('admin.reports.financial', compact('financialData', 'monthlyRevenue', 'classRevenue', 'dateRange'));
    }

    public function students(Request $request)
    {
        $dateRange = $this->getDateRange($request);

        $studentActivity = User::role('student')
            ->with(['student.class'])
            ->withCount(['orders as total_orders' => function($query) use ($dateRange) {
                $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
            }])
            ->withSum(['orders as total_spent' => function($query) use ($dateRange) {
                $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
            }], 'total_amount')
            ->having('total_orders', '>', 0)
            ->orderByDesc('total_spent')
            ->paginate(20);

        $classActivity = SchoolClass::with(['grade', 'major', 'section'])
            ->withCount(['students', 'products'])
            ->withSum(['products as total_sales' => function($query) use ($dateRange) {
                $query->join('order_items', 'products.product_id', '=', 'order_items.product_id')
                      ->join('orders', 'order_items.order_id', '=', 'orders.order_id')
                      ->whereBetween('orders.created_at', [$dateRange['start'], $dateRange['end']]);
            }], 'order_items.subtotal')
            ->orderByDesc('total_sales')
            ->get();

        return view('admin.reports.students', compact('studentActivity', 'classActivity', 'dateRange'));
    }

    public function exportSales(Request $request)
    {
        // Export logic for sales report
        return response()->json(['message' => 'Export feature coming soon']);
    }

    public function exportProducts(Request $request)
    {
        // Export logic for products report
        return response()->json(['message' => 'Export feature coming soon']);
    }

    private function getDateRange(Request $request)
    {
        $start = $request->get('start_date') 
            ? Carbon::parse($request->get('start_date'))
            : Carbon::now()->subMonth();

        $end = $request->get('end_date')
            ? Carbon::parse($request->get('end_date'))->endOfDay()
            : Carbon::now();

        return [
            'start' => $start,
            'end' => $end,
        ];
    }
}