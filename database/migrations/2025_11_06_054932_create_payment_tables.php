<?php
// database/migrations/2024_01_01_000007_create_payment_tables.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Invoice Statuses
        Schema::create('invoice_statuses', function (Blueprint $table) {
            $table->id('status_id');
            $table->string('name'); // pending, paid, overdue, cancelled
            $table->timestamps();
        });

        // Payment Methods
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id('method_id');
            $table->string('name'); // QRIS, Cash, Transfer
            $table->string('provider')->nullable(); // Midtrans, Manual, etc.
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Payment Statuses
        Schema::create('payment_statuses', function (Blueprint $table) {
            $table->id('status_id');
            $table->string('name'); // pending, paid, failed, expired
            $table->timestamps();
        });

        // Invoices
        Schema::create('invoices', function (Blueprint $table) {
            $table->id('invoice_id');
            $table->foreignId('order_id')->constrained('orders', 'order_id');
            $table->string('invoice_number')->unique();
            $table->foreignId('status_id')->constrained('invoice_statuses', 'status_id');
            $table->timestamp('issued_at');
            $table->timestamp('due_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Payments
        Schema::create('payments', function (Blueprint $table) {
            $table->id('payment_id');
            $table->foreignId('order_id')->constrained('orders', 'order_id');
            $table->foreignId('status_id')->constrained('payment_statuses', 'status_id');
            $table->foreignId('method_id')->constrained('payment_methods', 'method_id');
            $table->string('reference_id')->nullable();
            $table->decimal('amount', 15, 2);
            $table->string('snap_token')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('payment_statuses');
        Schema::dropIfExists('payment_methods');
        Schema::dropIfExists('invoice_statuses');
    }
};