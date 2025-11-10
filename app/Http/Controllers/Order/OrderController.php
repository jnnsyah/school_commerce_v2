<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\OrderRequest;
use App\Models\Order\Order;
use App\Models\Order\OrderStatus;
use App\Models\Order\Cart;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
        
        $this->middleware('permission:order.view')->only('index', 'show');
        $this->middleware('permission:order.create')->only('create', 'store');
        $this->middleware('permission:order.edit')->only('edit', 'update');
        $this->middleware('permission:order.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = Order::with(['user', 'status', 'items.product']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status_id', $request->status);
        }

        // For non-admin users, only show their own orders
        if (!auth()->user()->hasRole(['super_admin', 'admin', 'guru_pkwu'])) {
            $query->where('user_id', auth()->id());
        }

        $orders = $query->latest()->paginate(15);
        $statuses = OrderStatus::all();

        return view('admin.orders.index', compact('orders', 'statuses'));
    }

    public function create()
    {
        $cart = Cart::with(['items.product.images', 'items.variant', 'items.extras.extra'])
            ->where('user_id', auth()->id())
            ->first();

        if (!$cart || $cart->isEmpty()) {
            return redirect()->route('products.index')
                ->with('error', 'Your cart is empty. Add some products first.');
        }

        return view('admin.orders.create', compact('cart'));
    }

    public function store(OrderRequest $request)
    {
        try {
            $order = $this->orderService->createOrderFromCart(auth()->id(), $request->all());
            
            return redirect()->route('orders.show', $order)
                ->with('success', 'Order created successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create order: ' . $e->getMessage());
        }
    }

    public function show(Order $order)
    {
        // Check permission
        if ($order->user_id !== auth()->id() && !auth()->user()->hasRole(['super_admin', 'admin', 'guru_pkwu'])) {
            abort(403);
        }

        $statuses = OrderStatus::all();

        $order->load(['user', 'status', 'items.product.images', 'items.variant', 'items.extras.extra', 'transactionLogs']);

        return view('admin.orders.show', compact('order', 'statuses'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status_id' => 'required|exists:order_statuses,status_id',
            'note' => 'nullable|string|max:500',
        ]);

        try {
            $this->orderService->updateOrderStatus($order, $request->status_id, $request->note, auth()->id());
            
            return redirect()->back()->with('success', 'Order status updated successfully.');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update order status: ' . $e->getMessage());
        }
    }

    public function cancel(Order $order)
    {
        // Check if user can cancel this order
        if ($order->user_id !== auth()->id() && !auth()->user()->hasRole(['super_admin', 'admin', 'guru_pkwu'])) {
            abort(403);
        }

        if (!$order->canBeCancelled()) {
            return redirect()->back()->with('error', 'This order cannot be cancelled.');
        }

        try {
            $this->orderService->cancelOrder($order, auth()->id());
            
            return redirect()->back()->with('success', 'Order cancelled successfully.');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to cancel order: ' . $e->getMessage());
        }
    }
    
    /**
     * User Order History
     */
    public function userIndex(Request $request)
    {
        $orders = Order::with(['status', 'items.product.images'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('user.orders.index', compact('orders'));
    }

    /**
     * User Order Detail
     */
    public function userShow(Order $order)
    {
        // Check if order belongs to user
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load(['status', 'items.product.images', 'items.variant', 'items.extras.extra', 'transactionLogs']);

        return view('user.orders.show', compact('order'));
    }

    /**
     * Admin Order Management
     */
    public function adminIndex(Request $request)
    {
        $query = Order::with(['user', 'status', 'items.product']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status_id', $request->status);
        }

        $orders = $query->latest()->paginate(15);
        $statuses = OrderStatus::all();

        return view('admin.orders.index', compact('orders', 'statuses'));
    }

    /**
     * Admin Order Detail
     */
    public function adminShow(Order $order)
    {
        $order->load(['user', 'status', 'items.product.images', 'items.variant', 'items.extras.extra', 'transactionLogs']);
        $statuses = OrderStatus::all();

        return view('admin.orders.show', compact('order', 'statuses'));
    }
}