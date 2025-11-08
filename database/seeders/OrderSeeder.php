<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order\Order;
use App\Models\Order\OrderItem;
use App\Models\Order\OrderItemExtra;
use App\Models\Order\TransactionLog;
use App\Models\Product\Product;
use App\Models\Product\ProductVariant;
use App\Models\Product\ProductExtra;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Order::truncate();
        OrderItem::truncate();
        OrderItemExtra::truncate();
        TransactionLog::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $orders = [];

        // Student orders (completed)
        for ($i = 1; $i <= 8; $i++) {
            $studentId = $i + 7; // Student users start from ID 8
            $orderDate = Carbon::now()->subDays(rand(1, 30));
            
            $orders[] = [
                'user_id' => $studentId,
                'total_amount' => rand(15000, 50000),
                'status_id' => 4, // Completed
                'created_at' => $orderDate,
                'updated_at' => $orderDate->copy()->addHours(rand(1, 24)),
            ];
        }

        // Teacher orders (completed & pending)
        for ($i = 1; $i <= 4; $i++) {
            $teacherId = 7; // Guru Biasa
            $orderDate = Carbon::now()->subDays(rand(1, 15));
            $status = $i <= 2 ? 4 : 1; // 2 completed, 2 pending
            
            $orders[] = [
                'user_id' => $teacherId,
                'total_amount' => rand(20000, 80000),
                'status_id' => $status,
                'created_at' => $orderDate,
                'updated_at' => $orderDate->copy()->addHours(rand(1, 24)),
            ];
        }

        // Create orders and order items
        foreach ($orders as $orderData) {
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(uniqid());
            
            $order = Order::create([
                'invoice_number' => $invoiceNumber,
                'user_id' => $orderData['user_id'],
                'total_amount' => $orderData['total_amount'],
                'status_id' => $orderData['status_id'],
                'created_at' => $orderData['created_at'],
                'updated_at' => $orderData['updated_at'],
            ]);

            // Add 1-3 random products to each order
            $productCount = rand(1, 3);
            $approvedProducts = Product::where('status_id', 2)->with('variants')->get();
            
            for ($j = 0; $j < $productCount; $j++) {
                if ($approvedProducts->count() > 0) {
                    $product = $approvedProducts->random();
                    $variant = $product->variants->first();
                    $quantity = rand(1, 3);
                    
                    $orderItem = OrderItem::create([
                        'order_id' => $order->order_id,
                        'product_id' => $product->product_id,
                        'variant_id' => $variant ? $variant->id : null,
                        'qty' => $quantity,
                        'price' => $variant ? $variant->price : $product->price,
                        'subtotal' => ($variant ? $variant->price : $product->price) * $quantity,
                    ]);

                    // Randomly add extras to some order items
                    if (rand(0, 1) && $product->extras->count() > 0) {
                        $extra = $product->extras->random();
                        $extraQty = rand(1, $extra->max_qty);
                        
                        OrderItemExtra::create([
                            'order_item_id' => $orderItem->order_item_id,
                            'extra_id' => $extra->id,
                            'qty' => $extraQty,
                            'price' => $extra->price,
                        ]);
                    }
                }
            }

            // Create transaction logs
            $this->createTransactionLogs($order);
        }

        $this->command->info('Orders seeded successfully!');
        $this->command->info('Created: 12 orders with random products');
        $this->command->info('Status: 10 Completed, 2 Pending');
    }

    private function createTransactionLogs(Order $order)
    {
        $statuses = [
            ['status_id' => 1, 'note' => 'Order created', 'delay_minutes' => 0],
            ['status_id' => 2, 'note' => 'Payment confirmed', 'delay_minutes' => 5],
            ['status_id' => 3, 'note' => 'Order being processed', 'delay_minutes' => 30],
        ];

        if ($order->status_id == 4) { // Completed orders
            $statuses[] = ['status_id' => 4, 'note' => 'Order completed', 'delay_minutes' => 60];
        }

        $createdAt = $order->created_at;

        foreach ($statuses as $status) {
            if ($status['status_id'] <= $order->status_id) {
                TransactionLog::create([
                    'order_id' => $order->order_id,
                    'status_id' => $status['status_id'],
                    'note' => $status['note'],
                    'created_by' => $order->user_id,
                    'created_at' => $createdAt->copy()->addMinutes($status['delay_minutes']),
                    'updated_at' => $createdAt->copy()->addMinutes($status['delay_minutes']),
                ]);
            }
        }
    }
}