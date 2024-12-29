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
        Schema::create('setups', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->enum('account_type', ['SELLER', 'BUYER']);
            $table->string('company_name')->nullable();
            $table->string('company_description')->nullable();
            $table->string('company_address_1')->nullable();
            $table->string('company_address_2')->nullable();
            $table->string('company_mobile_1')->nullable();
            $table->string('company_mobile_2')->nullable();
            $table->string('company_image')->nullable();
            $table->string('company_image_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setups');
    }
};
