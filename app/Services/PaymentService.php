<?php
namespace App\Services;

use App\Models\Order\Order;
use App\Models\Payment\Payment;
use App\Models\Payment\PaymentMethod;
use App\Models\Payment\PaymentStatus;
use App\Models\Payment\Invoice;
use App\Models\Payment\InvoiceStatus;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class PaymentService
{
    public function __construct()
    {
        // Setup Midtrans configuration
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$clientKey = config('services.midtrans.client_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized', true);
        Config::$is3ds = config('services.midtrans.is_3ds', true);
    }

    public function createPayment(Order $order, $paymentMethod = PaymentMethod::QRIS)
    {
        return DB::transaction(function () use ($order, $paymentMethod) {
            // Create invoice
            $invoice = $this->createInvoice($order);
            
            // Create payment record
            $payment = Payment::create([
                'order_id' => $order->order_id,
                'status_id' => PaymentStatus::PENDING,
                'method_id' => $paymentMethod,
                'amount' => $order->total_amount,
                'snap_token' => null,
            ]);

            // Generate Midtrans transaction
            $snapToken = $this->createMidtransTransaction($order, $payment);
            
            // Update payment with snap token
            $payment->update(['snap_token' => $snapToken]);

            return [
                'payment' => $payment,
                'snap_token' => $snapToken,
                'client_key' => config('services.midtrans.client_key')
            ];
        });
    }

    private function createInvoice(Order $order)
    {
        return Invoice::create([
            'order_id' => $order->order_id,
            'invoice_number' => 'INV-' . date('Ymd') . '-' . strtoupper(uniqid()),
            'status_id' => InvoiceStatus::PENDING,
            'issued_at' => now(),
            'due_date' => now()->addHours(24), // 24 jam expired
            'notes' => 'Invoice untuk order #' . $order->invoice_number,
        ]);
    }

    private function createMidtransTransaction(Order $order, Payment $payment)
    {
        $transactionDetails = [
            'order_id' => $payment->payment_id . '-' . time(),
            'gross_amount' => (int) $order->total_amount,
        ];

        $customerDetails = [
            'first_name' => $order->user->name,
            'email' => $order->user->email,
            'phone' => $order->user->no_hp,
        ];

        $itemDetails = [];
        foreach ($order->items as $item) {
            $itemDetails[] = [
                'id' => $item->product_id,
                'price' => (int) $item->price,
                'quantity' => $item->qty,
                'name' => $item->getProductName(),
            ];
        }

        $params = [
            'transaction_details' => $transactionDetails,
            'customer_details' => $customerDetails,
            'item_details' => $itemDetails,
            'expiry' => [
                'start_time' => now()->format('Y-m-d H:i:s O'),
                'unit' => 'hour',
                'duration' => 24
            ]
        ];

        try {
            return Snap::getSnapToken($params);
        } catch (\Exception $e) {
            throw new \Exception('Failed to create Midtrans transaction: ' . $e->getMessage());
        }
    }

    public function handlePaymentCallback($request)
    {
        $orderId = $request->order_id;
        $statusCode = $request->status_code;
        $grossAmount = $request->gross_amount;
        $transactionStatus = $request->transaction_status;

        // Extract payment_id from Midtrans order_id (format: payment_id-timestamp)
        $paymentId = explode('-', $orderId)[0];
        
        $payment = Payment::findOrFail($paymentId);
        $order = $payment->order;

        // Verify amount
        if ((int) $grossAmount !== (int) $order->total_amount) {
            throw new \Exception('Invalid payment amount');
        }

        return DB::transaction(function () use ($payment, $transactionStatus, $order) {
            switch ($transactionStatus) {
                case 'capture':
                case 'settlement':
                    $this->markAsPaid($payment);
                    break;
                    
                case 'pending':
                    $payment->update(['status_id' => PaymentStatus::PENDING]);
                    break;
                    
                case 'deny':
                case 'cancel':
                case 'expire':
                    $this->markAsFailed($payment);
                    break;
            }

            return $payment;
        });
    }

    private function markAsPaid(Payment $payment)
    {
        $payment->update([
            'status_id' => PaymentStatus::PAID,
            'paid_at' => now(),
            'reference_id' => request()->transaction_id ?? uniqid()
        ]);

        // Update order status to paid
        $payment->order->update(['status_id' => 2]); // 2 = paid

        // Update invoice status
        $payment->order->invoice->update(['status_id' => InvoiceStatus::PAID]);

        // Create transaction log
        \App\Models\Order\TransactionLog::create([
            'order_id' => $payment->order->order_id,
            'status_id' => 2, // paid
            'note' => 'Payment completed via ' . $payment->method->name,
            'created_by' => 1, // system
        ]);
    }

    private function markAsFailed(Payment $payment)
    {
        $payment->update([
            'status_id' => PaymentStatus::FAILED,
            'reference_id' => request()->transaction_id ?? uniqid()
        ]);

        // Create transaction log
        \App\Models\Order\TransactionLog::create([
            'order_id' => $payment->order->order_id,
            'status_id' => 1, // pending
            'note' => 'Payment failed: ' . request()->transaction_status,
            'created_by' => 1, // system
        ]);
    }

    public function checkPaymentStatus(Payment $payment)
    {
        try {
            $status = Transaction::status($payment->payment_id . '-' . time());
            return $status->transaction_status;
        } catch (\Exception $e) {
            return 'error';
        }
    }
}