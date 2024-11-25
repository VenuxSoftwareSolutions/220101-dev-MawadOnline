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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('scope'); 
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade'); //  product-based discounts
            $table->unsignedBigInteger('category_id')->foreignId('category_id')->nullable()->constrained()->onDelete('cascade'); //  category-based discounts
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->double('min_order_amount', 20, 2)->nullable(); //  order over a specified amount
            $table->double('discount_percentage', 5, 2);
            $table->double('max_discount', 20, 2)->nullable(); 
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->boolean('status')->default(true);
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
        Schema::dropIfExists('discounts');
    }
};
