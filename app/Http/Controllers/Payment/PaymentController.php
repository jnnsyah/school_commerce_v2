<?php
namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use App\Services\PaymentService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $paymentService;
    protected $orderService;

    public function __construct(PaymentService $paymentService, OrderService $orderService)
    {
        $this->paymentService = $paymentService;
        $this->orderService = $orderService;
    }

    public function create(Order $order)
    {
        // Check if order belongs to user
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Check if order is already paid
        if ($order->isPaid()) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Order sudah dibayar.');
        }

        // Check if there's pending payment
        $pendingPayment = $order->getPendingPayment();
        if ($pendingPayment) {
            return view('payment.checkout', [
                'order' => $order,
                'payment' => $pendingPayment,
                'snap_token' => $pendingPayment->snap_token,
                'client_key' => config('services.midtrans.client_key')
            ]);
        }

        // Create new payment
        try {
            $paymentData = $this->orderService->processPayment($order);
            
            return view('payment.checkout', [
                'order' => $order,
                'payment' => $paymentData['payment'],
                'snap_token' => $paymentData['snap_token'],
                'client_key' => $paymentData['client_key']
            ]);
        } catch (\Exception $e) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    public function callback(Request $request)
    {
        try {
            $payment = $this->paymentService->handlePaymentCallback($request);
            
            // Log the callback for debugging
            Log::info('Payment Callback Received', [
                'order_id' => $payment->order->order_id,
                'payment_id' => $payment->payment_id,
                'status' => $request->transaction_status,
                'data' => $request->all()
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Payment Callback Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 400);
        }
    }

    public function success(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('payment.success', compact('order'));
    }

    public function failure(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('payment.failure', compact('order'));
    }

    public function checkStatus(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $payment = $order->getLatestPayment();
        if (!$payment) {
            return response()->json(['status' => 'no_payment']);
        }

        $status = $this->paymentService->checkPaymentStatus($payment);
        
        return response()->json([
            'status' => $status,
            'is_paid' => $payment->isPaid(),
            'order_status' => $order->status->name
        ]);
    }
}