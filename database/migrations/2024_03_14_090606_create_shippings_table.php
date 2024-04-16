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
        Schema::create('shippings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->integer('from_shipping');
            $table->integer('to_shipping');
            $table->string('shipper');
            $table->integer('estimated_order');
            $table->integer('estimated_shipping');
            $table->string('paid');
            $table->integer('vat_shipping');
            $table->string('shipping_charge');
            $table->integer('flat_rate_shipping');
            $table->integer('charge_per_unit_shipping');
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
        Schema::dropIfExists('shippings');
    }
};
