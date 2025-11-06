<?php
// database/migrations/2024_01_01_000005_create_stock_management_tables.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Stock References Types
        Schema::create('stock_references_types', function (Blueprint $table) {
            $table->id('type_id');
            $table->string('code'); // order, adjustment, return, etc.
            $table->string('description');
            $table->timestamps();
        });

        // Stock Product
        Schema::create('stock_product', function (Blueprint $table) {
            $table->id('stock_id');
            $table->foreignId('product_id')->constrained('products', 'product_id');
            $table->foreignId('ref_type_id')->constrained('stock_references_types', 'type_id');
            $table->integer('ref_id'); // reference to order_id, adjustment_id, etc.
            $table->integer('qty'); // positive for addition, negative for deduction
            $table->text('note')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });

        // Stock Product Cache
        Schema::create('stock_product_cache', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained('products', 'product_id')->primary();
            $table->integer('total_stock')->default(0);
            $table->timestamp('updated_at');
        });

        // Stock Product Variants
        Schema::create('stock_product_variants', function (Blueprint $table) {
            $table->id('stock_id');
            $table->foreignId('variant_id')->constrained('product_variants');
            $table->foreignId('ref_type_id')->constrained('stock_references_types', 'type_id');
            $table->integer('ref_id');
            $table->integer('qty');
            $table->text('note')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });

        // Stock Product Variant Cache
        Schema::create('stock_product_variant_cache', function (Blueprint $table) {
            $table->foreignId('variant_id')->constrained('product_variants')->primary();
            $table->integer('total_stock')->default(0);
            $table->timestamp('updated_at');
        });

        // Stock Product Extra
        Schema::create('stock_product_extra', function (Blueprint $table) {
            $table->id('stock_id');
            $table->foreignId('extra_id')->constrained('product_extras');
            $table->foreignId('ref_type_id')->constrained('stock_references_types', 'type_id');
            $table->integer('ref_id');
            $table->integer('qty');
            $table->text('note')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });

        // Stock Product Extra Cache
        Schema::create('stock_product_extra_cache', function (Blueprint $table) {
            $table->foreignId('extra_id')->constrained('product_extras')->primary();
            $table->integer('total_stock')->default(0);
            $table->timestamp('updated_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_product_extra_cache');
        Schema::dropIfExists('stock_product_extra');
        Schema::dropIfExists('stock_product_variant_cache');
        Schema::dropIfExists('stock_product_variants');
        Schema::dropIfExists('stock_product_cache');
        Schema::dropIfExists('stock_product');
        Schema::dropIfExists('stock_references_types');
    }
};