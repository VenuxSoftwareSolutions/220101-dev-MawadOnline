<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tracking_shipments', function (Blueprint $table) {
            $table->id();
			$table->string('shipment_id');
			$table->string('label_url');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('order_detail_id');
            $table->foreign('order_detail_id')->references('id')->on('order_details');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tracking_shipments');
    }
};
