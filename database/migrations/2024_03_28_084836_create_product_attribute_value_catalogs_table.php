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
        Schema::create('product_attribute_value_catalogs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('catalog_id');
            $table->unsignedBigInteger('id_attribute');
            $table->unsignedBigInteger('id_units')->nullable();
            $table->unsignedBigInteger('id_values')->nullable();
            $table->unsignedBigInteger('id_colors')->nullable();
            $table->string('value', 191);
            $table->unsignedInteger('is_variant')->default(0);
            $table->unsignedInteger('is_general')->default(0);
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
        Schema::dropIfExists('product_attribute_value_catalogs');
    }
};
