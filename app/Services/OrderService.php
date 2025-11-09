<?php

namespace App\Services;

use App\Models\Order\Order;
use App\Models\Order\OrderStatus;
use App\Models\Order\Cart;
use App\Models\Order\TransactionLog;
use App\Models\Inventory\StockReferenceType;
use App\Models\Inventory\StockProductVariant;
use Illuminate\Support\Facades\DB;

class OrderService
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function createOrderFromCart($userId, $orderData)
    {
        return DB::transaction(function () use ($userId, $orderData) {
            // Get user's cart
            $cart = Cart::with(['items.product', 'items.variant', 'items.extras.extra'])
                ->where('user_id', $userId)
                ->firstOrFail();

            if ($cart->isEmpty()) {
                throw new \Exception('Cart is empty');
            }

            // Generate invoice number
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(uniqid());

            // Calculate total amount
            $totalAmount = $cart->getSubtotal();

            // Create order
            $order = Order::create([
                'invoice_number' => $invoiceNumber,
                'user_id' => $userId,
                'total_amount' => $totalAmount,
                'status_id' => OrderStatus::PENDING,
                'customer_notes' => $orderData['customer_notes'] ?? null,
            ]);

            // Create order items from cart items
            foreach ($cart->items as $cartItem) {
                $orderItem = $order->items()->create([
                    'product_id' => $cartItem->product_id,
                    'variant_id' => $cartItem->variant_id,
                    'qty' => $cartItem->qty,
                    'price' => $cartItem->price_snapshot,
                    'subtotal' => $cartItem->price_snapshot * $cartItem->qty,
                ]);

                // Create order item extras
                foreach ($cartItem->extras as $cartExtra) {
                    $orderItem->extras()->create([
                        'extra_id' => $cartExtra->extra_id,
                        'qty' => $cartExtra->qty,
                        'price' => $cartExtra->price_snapshot,
                    ]);
                }

                // Reduce stock for variants
                if ($cartItem->variant_id) {
                    $variant = $cartItem->variant;
                    if ($variant->stock_at < $cartItem->qty) {
                        throw new \Exception("Insufficient stock for {$variant->name}");
                    }
                    
                    $variant->reduceStock($cartItem->qty);
                    
                    // Record stock movement
                    StockProductVariant::create([
                        'variant_id' => $variant->id,
                        'ref_type_id' => StockReferenceType::ORDER,
                        'ref_id' => $order->order_id,
                        'qty' => -$cartItem->qty,
                        'note' => 'Order #' . $order->invoice_number,
                        'user_id' => $userId,
                    ]);
                }

                // Reduce stock for extras
                foreach ($cartItem->extras as $cartExtra) {
                    $extra = $cartExtra->extra;
                    if ($extra->stock_cache < $cartExtra->qty) {
                        throw new \Exception("Insufficient stock for {$extra->name}");
                    }
                    
                    $extra->reduceStock($cartExtra->qty);
                }
            }

            // Clear cart
            $cart->clear();

            // Create transaction log
            TransactionLog::create([
                'order_id' => $order->order_id,
                'status_id' => OrderStatus::PENDING,
                'note' => 'Order created',
                'created_by' => $userId,
            ]);

            return $order;
        });
    }

    public function updateOrderStatus(Order $order, $statusId, $note, $userId)
    {
        DB::transaction(function () use ($order, $statusId, $note, $userId) {
            $oldStatus = $order->status_id;
            $order->update(['status_id' => $statusId]);

            // If order is cancelled, restore stock
            if ($statusId == OrderStatus::CANCELLED && $oldStatus != OrderStatus::CANCELLED) {
                $this->restoreOrderStock($order);
            }

            // If order is completed, mark completion time
            if ($statusId == OrderStatus::COMPLETED) {
                $order->update(['completed_at' => now()]);
            }

            // Create transaction log
            TransactionLog::create([
                'order_id' => $order->order_id,
                'status_id' => $statusId,
                'note' => $note ?? 'Status updated',
                'created_by' => $userId,
            ]);
        });
    }

    public function processPayment(Order $order, $paymentMethod = 1) // 1 = QRIS
    {
        return $this->paymentService->createPayment($order, $paymentMethod);
    }

    public function cancelOrder(Order $order, $userId)
    {
        $this->updateOrderStatus($order, OrderStatus::CANCELLED, 'Order cancelled by user', $userId);
    }

    private function restoreOrderStock(Order $order)
    {
        foreach ($order->items as $orderItem) {
            // Restore variant stock
            if ($orderItem->variant_id) {
                $variant = $orderItem->variant;
                $variant->increaseStock($orderItem->qty);
                
                StockProductVariant::create([
                    'variant_id' => $variant->id,
                    'ref_type_id' => StockReferenceType::RETURN,
                    'ref_id' => $order->order_id,
                    'qty' => $orderItem->qty,
                    'note' => 'Order cancellation - stock restored',
                    'user_id' => auth()->id(),
                ]);
            }

            // Restore extra stock
            foreach ($orderItem->extras as $orderExtra) {
                $extra = $orderExtra->extra;
                $extra->increaseStock($orderExtra->qty);
            }
        }
    }
}