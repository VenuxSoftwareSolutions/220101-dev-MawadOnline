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
        Schema::create('order_details_fees', function (Blueprint $table) {
           $table->id();
            $table->integer('order_detail_id');
            $table->unsignedBigInteger('order_fee_id');
            $table->double('fee_amount')->nullable();
            $table->foreign('order_fee_id')
                ->references('id')
                ->on('order_fees');
            $table->foreign('order_detail_id')
                ->references('id')
                ->on('order_details');
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
        Schema::dropIfExists('order_details_fees');
    }
};
