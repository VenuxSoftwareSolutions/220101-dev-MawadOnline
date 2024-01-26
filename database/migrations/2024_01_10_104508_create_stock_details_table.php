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
        Schema::create('stock_details', function (Blueprint $table) {
            $table->id();
            $table->string('operation_type');
            $table->unsignedBigInteger('variant_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('seller_id'); // Add the seller_id column
            $table->unsignedBigInteger('order_id')->nullable(); // Add the order_id column, nullable
            $table->integer('before_quantity');
            $table->integer('transaction_quantity');
            $table->integer('after_quantity');
            $table->text('user_comment')->nullable();
            $table->timestamps();

            // Add foreign keys
            $table->foreign('variant_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->foreign('seller_id')->references('id')->on('users')->onDelete('cascade'); // Add foreign key for seller_id
            // $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade'); // Foreign key for order_id

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_details');
    }
};
