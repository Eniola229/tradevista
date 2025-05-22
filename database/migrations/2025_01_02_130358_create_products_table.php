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
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('category_id');
            $table->longText('categories')->nullable();
            $table->string('product_name');
            $table->string('product_code')->nullable();
            $table->string('product_color')->nullable();
            $table->text('product_description')->nullable();
            $table->decimal('product_price', 8, 2)->nullable();
            $table->unsignedBigInteger('stock')->nullable();
            $table->decimal('product_discount', 8, 2)->nullable();
            $table->decimal('shipping_fee', 8, 2)->nullable();
            $table->decimal('product_weight', 8, 2)->nullable();
            $table->json('weight')->nullable();
            $table->json('dimensions')->nullable();
            $table->string('product_video')->nullable();
            $table->string('main_image')->nullable();
            $table->string('image_id')->nullable();
            $table->string('image_url')->nullable();
            $table->string('video_id')->nullable();
            $table->string('video_url')->nullable();
            $table->text('description')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->enum('is_featured', ['No', 'Yes']);
            $table->enum('status', ['ACTIVE', 'INACTIVE'])->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
