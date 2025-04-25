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
        Schema::create('order_fees', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->double('total_fee')->nullable();
            $table->json('fee_details')->nullable();
            $table->foreign('order_id')
                ->references('id')
                ->on('orders');
            $table->string('payment_charge_id', 255);
            $table->string('payment_balance_id', 255);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('order_fees');
    }
};
