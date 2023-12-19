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
        Schema::create('product_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_products');
            $table->unsignedBigInteger('id_attribute');
            $table->unsignedBigInteger('id_units')->nullable();
            $table->unsignedBigInteger('id_values')->nullable();
            $table->unsignedBigInteger('id_colors')->nullable();
            $table->string('value');
            $table->string('color_name_en');
            $table->string('color_name_ar');

            $table->foreign('id_products')->references('id')->on('products');
            $table->foreign('id_attribute')->references('id')->on('attributes');
            $table->foreign('id_units')->references('id')->on('unites');
            $table->foreign('id_values')->references('id')->on('attribute_values');
            $table->foreign('id_colors')->references('id')->on('colors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_attribute_values');
    }
};
