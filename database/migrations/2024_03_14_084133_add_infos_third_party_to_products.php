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
        Schema::table('products', function (Blueprint $table) {
            $table->integer('activate_third_party')->default(0);
            $table->integer('length')->nullable();
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->integer('min_third_party')->nullable();
            $table->integer('max_third_party')->nullable();
            $table->string('breakable')->nullable();
            $table->string('unit_third_party')->nullable();
            $table->string('shipper_sample')->nullable();
            $table->integer('estimated_sample')->nullable();
            $table->integer('estimated_shipping_sample')->nullable();
            $table->string('paid_sample')->nullable();
            $table->double('shipping_amount', 8, 2)->nullable();
            $table->integer('sample_available')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
