<?php
// app/Http/Controllers/Admin/PaymentManagementController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment\Payment;
use App\Models\Order\Order;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentManagementController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index(Request $request)
    {
        $query = Payment::with(['order.user', 'status', 'method']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status_id', $request->status);
        }

        // Filter by payment method
        if ($request->has('method') && $request->method) {
            $query->where('method_id', $request->method);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        $payments = $query->latest()->paginate(20);
        $paymentMethods = \App\Models\Payment\PaymentMethod::where('is_active', true)->get();
        $paymentStatuses = \App\Models\Payment\PaymentStatus::all();

        return view('admin.payments.index', compact('payments', 'paymentMethods', 'paymentStatuses'));
    }

    public function manualIndex(Request $request)
    {
        // Payments that need manual confirmation (cash payments)
        $query = Payment::with(['order.user', 'status', 'method'])
            ->where('method_id', 1) // 1 = Cash
            ->where('status_id', 1); // 1 = Pending

        if ($request->has('date_from') && $request->date_from) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        $payments = $query->latest()->paginate(15);

        return view('admin.payments.manual', compact('payments'));
    }

    public function confirmPayment(Payment $payment)
    {
        try {
            DB::transaction(function () use ($payment) {
                // Mark payment as paid
                $payment->update([
                    'status_id' => 2, // Paid
                    'paid_at' => now(),
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                ]);

                // Update order status
                $payment->order->update(['status_id' => 2]); // Paid

                // Create transaction log
                \App\Models\Order\TransactionLog::create([
                    'order_id' => $payment->order->order_id,
                    'status_id' => 2, // Paid
                    'note' => 'Pembayaran tunai dikonfirmasi oleh ' . auth()->user()->name,
                    'created_by' => auth()->id(),
                ]);
            });

            return redirect()->back()->with('success', 'Pembayaran berhasil dikonfirmasi.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengonfirmasi pembayaran: ' . $e->getMessage());
        }
    }

    public function cancelPayment(Payment $payment)
    {
        try {
            DB::transaction(function () use ($payment) {
                $payment->update([
                    'status_id' => 3, // Cancelled
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                ]);

                // Create transaction log
                \App\Models\Order\TransactionLog::create([
                    'order_id' => $payment->order->order_id,
                    'status_id' => 1, // Pending
                    'note' => 'Pembayaran dibatalkan oleh ' . auth()->user()->name,
                    'created_by' => auth()->id(),
                ]);
            });

            return redirect()->back()->with('success', 'Pembayaran berhasil dibatalkan.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membatalkan pembayaran: ' . $e->getMessage());
        }
    }

    public function show(Payment $payment)
    {
        $payment->load([
            'order.user', 
            'order.items.product.images',
            'status', 
            'method',
            'order.transactionLogs.user'
        ]);

        return view('admin.payments.show', compact('payment'));
    }
}