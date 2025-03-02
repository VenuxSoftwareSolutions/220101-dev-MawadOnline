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
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('buyer_id');
            $table->unsignedBigInteger('seller_id');
            $table->integer('order_detail_id');
            $table->enum('refund_status',['pending','requires_action','succeeded','failed','canceled']);
            $table->string('description_error', 255)->nullable();
            $table->string('payment_refund_id', 255);
            $table->string('payment_charge_id', 255);
            $table->float('amount');
            $table->foreign('buyer_id')
                    ->references('id')
                    ->on('users');
            $table->foreign('seller_id')
                    ->references('id')
                    ->on('users');
            $table->foreign('order_detail_id')
                    ->references('id')
                    ->on('order_details');
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
        Schema::dropIfExists('refunds');
    }
};
