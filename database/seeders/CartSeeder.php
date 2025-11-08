<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Order\Cart;
use App\Models\Order\CartItem;
use App\Models\Order\CartItemExtra;
use App\Models\Product\Product;
use App\Models\Product\ProductVariant;
use App\Models\Product\ProductExtra;
use App\Models\User\User;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan foreign key checks untuk truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Clear existing carts
        CartItemExtra::truncate();
        CartItem::truncate();
        Cart::truncate();
        
        // Aktifkan kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Cek apakah ada produk yang approved
        $approvedProducts = Product::where('status_id', 2)->with(['variants', 'extras'])->get();
        
        if ($approvedProducts->isEmpty()) {
            $this->command->error('No approved products found! Please run ProductsSeeder first.');
            return;
        }

        // Cek students yang valid
        $students = User::role('student')->get();
        
        if ($students->isEmpty()) {
            $this->command->error('No students found!');
            return;
        }

        foreach ($students->take(5) as $student) {
            $cart = Cart::create(['user_id' => $student->id]);

            // Add 1-3 random products to cart
            $productCount = rand(1, 3);
            
            // FIX: Gunakan min() untuk menghindari request lebih dari available
            $selectedProducts = $approvedProducts->random(min($productCount, $approvedProducts->count()));

            // FIX: Handle jika $selectedProducts bukan collection
            if (!is_iterable($selectedProducts)) {
                $selectedProducts = [$selectedProducts];
            }

            foreach ($selectedProducts as $product) {
                $variant = $product->variants->first();
                $quantity = rand(1, 2);

                $cartItem = CartItem::create([
                    'cart_id' => $cart->cart_id,
                    'product_id' => $product->product_id,
                    'variant_id' => $variant ? $variant->id : null,
                    'qty' => $quantity,
                    'price_snapshot' => $variant ? $variant->price : $product->price,
                ]);

                // Randomly add extras to some cart items
                if (rand(0, 1) && $product->extras->count() > 0) {
                    $extra = $product->extras->random();
                    $extraQty = rand(1, $extra->max_qty);
                    
                    CartItemExtra::create([
                        'cart_item_id' => $cartItem->cart_item_id,
                        'extra_id' => $extra->id,
                        'qty' => $extraQty,
                        'price_snapshot' => $extra->price,
                    ]);
                }
            }
        }

        // Add items to teacher cart
        $teacher = User::role('guru_biasa')->first();
        if ($teacher) {
            $cart = Cart::create(['user_id' => $teacher->id]);
            
            // FIX: Gunakan min() untuk teacher juga
            $teacherProducts = $approvedProducts->random(min(2, $approvedProducts->count()));
            
            if (!is_iterable($teacherProducts)) {
                $teacherProducts = [$teacherProducts];
            }
            
            foreach ($teacherProducts as $product) {
                $variant = $product->variants->first();
                
                $cartItem = CartItem::create([
                    'cart_id' => $cart->cart_id,
                    'product_id' => $product->product_id,
                    'variant_id' => $variant ? $variant->id : null,
                    'qty' => 1,
                    'price_snapshot' => $variant ? $variant->price : $product->price,
                ]);
            }
        }

        $this->command->info('Carts seeded successfully!');
        $this->command->info('Created carts for ' . min(5, $students->count()) . ' students and ' . ($teacher ? 1 : 0) . ' teacher');
    }
}