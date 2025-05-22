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
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('currency'); 
            $table->string('description'); 
            $table->decimal('amount', 15, 2);
            $table->string('reference')->nullable();
            $table->enum('payment_method', ['INTERSWTICH', 'PAYSTACK', 'REFUND', 'BANK TRANSFER'])->default('PAYSTACK');
            $table->enum('status', ['PENDING', 'PAID', 'FAILED'])->default('PENDING');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
