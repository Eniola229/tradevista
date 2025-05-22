<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID as primary key
            $table->uuid('user_id');
            $table->string('transaction_id')->unique();
            $table->string('shipping_charges')->nullable();
            $table->string('courier_name')->nullable();
            $table->enum('payment_status', ['PAID', 'PENDING', 'FAILED'])->default('PENDING');
            $table->enum('delivery_status', ['Processing', 'Shipped', 'Delivered', 'Cancelled'])->default('Processing');
            $table->text('shipping_address');
            $table->text('order_note');
            $table->decimal('total_weight', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('total', 10, 2);
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
