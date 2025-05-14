<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->renameColumn('price_before_discount', 'product_price_before_discount');
            $table->renameColumn('price_after_discount', 'product_price_after_discount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->renameColumn('product_price_before_discount', 'price_before_discount');
            $table->renameColumn('product_price_after_discount', 'price_after_discount');
        });
    }
};
