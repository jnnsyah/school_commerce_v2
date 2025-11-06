<?php
// database/migrations/2024_01_01_000004_create_product_management_tables.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Product Categories
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Product Statuses
        Schema::create('product_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // pending, approved, rejected
            $table->timestamps();
        });

        // Products
        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id');
            $table->foreignId('class_id')->constrained('classes', 'class_id');
            $table->string('name');
            $table->string('sku')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2);
            $table->foreignId('category_id')->constrained('product_categories');
            $table->foreignId('status_id')->constrained('product_statuses');
            $table->text('rejection_reason')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        // Product Variants
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products', 'product_id');
            $table->string('sku');
            $table->string('name');
            $table->decimal('price', 15, 2);
            $table->foreignId('status_id')->constrained('product_statuses');
            $table->integer('stock_at');
            $table->timestamps();
        });

        // Product Variant Values
        Schema::create('product_variant_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variant_id')->constrained('product_variants');
            $table->string('option_name'); // Size, Color, etc.
            $table->string('option_value'); // L, Red, etc.
            $table->timestamps();
        });

        // Product Images
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products', 'product_id');
            $table->foreignId('variant_id')->nullable()->constrained('product_variants');
            $table->string('file_path');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        // Product Extras
        Schema::create('product_extras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products', 'product_id');
            $table->string('name');
            $table->decimal('price', 15, 2);
            $table->integer('max_qty')->default(1);
            $table->integer('stock_cache')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_extras');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('product_variant_values');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_statuses');
        Schema::dropIfExists('product_categories');
    }
};