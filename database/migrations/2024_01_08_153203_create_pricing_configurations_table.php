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
        Schema::create('pricing_configurations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_products');
            $table->integer('from');
            $table->integer('to');
            $table->float('unit_price');
            $table->timestamp('discount_start_datetime')->nullable();
            $table->timestamp('discount_end_datetime')->nullable();
            $table->string('discount_type');
            $table->float('discount_amount')->nullable();
            $table->float('discount_percentage')->nullable();
            $table->timestamps();

            $table->foreign('id_products')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pricing_configurations');
    }
};
