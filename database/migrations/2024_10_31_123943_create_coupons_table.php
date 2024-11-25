<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Unique code for the coupon
            $table->string('scope'); // 'product', 'category', 'order', 'all_orders'
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('category_id')->foreignId('category_id')->nullable()->constrained()->onDelete('cascade');
            $table->double('min_order_amount', 20, 2)->nullable(); 
            $table->double('discount_percentage', 5, 2); 
            $table->double('max_discount', 20, 2)->nullable(); 
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->boolean('status')->default(true); 
            $table->integer('usage_limit')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
};
