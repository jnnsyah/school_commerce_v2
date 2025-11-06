<?php
// database/migrations/2024_01_01_000006_create_order_management_tables.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Order Statuses
        Schema::create('order_statuses', function (Blueprint $table) {
            $table->id('status_id');
            $table->string('name'); // pending, paid, processing, completed, cancelled
            $table->timestamps();
        });

        // Carts
        Schema::create('carts', function (Blueprint $table) {
            $table->id('cart_id');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });

        // Cart Items
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id('cart_item_id');
            $table->foreignId('cart_id')->constrained('carts', 'cart_id');
            $table->foreignId('product_id')->constrained('products', 'product_id');
            $table->foreignId('variant_id')->nullable()->constrained('product_variants');
            $table->integer('qty')->default(1);
            $table->decimal('price_snapshot', 15, 2);
            $table->timestamps();
        });

        // Cart Item Extras
        Schema::create('cart_item_extras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_item_id')->constrained('cart_items', 'cart_item_id');
            $table->foreignId('extra_id')->constrained('product_extras');
            $table->integer('qty')->default(1);
            $table->decimal('price_snapshot', 15, 2);
            $table->timestamps();
        });

        // Orders
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->string('invoice_number')->unique();
            $table->foreignId('user_id')->constrained('users');
            $table->decimal('total_amount', 15, 2);
            $table->foreignId('status_id')->constrained('order_statuses', 'status_id');
            $table->text('customer_notes')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        // Order Items
        Schema::create('order_items', function (Blueprint $table) {
            $table->id('order_item_id');
            $table->foreignId('order_id')->constrained('orders', 'order_id');
            $table->foreignId('product_id')->constrained('products', 'product_id');
            $table->foreignId('variant_id')->nullable()->constrained('product_variants');
            $table->integer('qty');
            $table->decimal('price', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });

        // Order Item Extras
        Schema::create('order_item_extras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_item_id')->constrained('order_items', 'order_item_id');
            $table->foreignId('extra_id')->constrained('product_extras');
            $table->integer('qty');
            $table->decimal('price', 15, 2);
            $table->timestamps();
        });

        // Transaction Log
        Schema::create('transaction_log', function (Blueprint $table) {
            $table->id('log_id');
            $table->foreignId('order_id')->constrained('orders', 'order_id');
            $table->foreignId('status_id')->constrained('order_statuses', 'status_id');
            $table->text('note')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_log');
        Schema::dropIfExists('order_item_extras');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('cart_item_extras');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('order_statuses');
    }
};