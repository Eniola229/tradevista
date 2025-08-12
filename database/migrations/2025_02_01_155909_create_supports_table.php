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
        Schema::create('supports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('attendant_id')->nullable();
            $table->string('ticket_id');
            $table->string('problem_type');
            $table->enum('status', ['REJECTED', 'PENDING', 'APPROVED', 'ISSUE FIXED', 'CLOSED']);
            $table->text('message');
            $table->string('image_url')->nullable();
            $table->string('image_id')->nullable();
            $table->text('answer')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('attendant_id')->references('id')->on('admins')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supports');
    }
};
