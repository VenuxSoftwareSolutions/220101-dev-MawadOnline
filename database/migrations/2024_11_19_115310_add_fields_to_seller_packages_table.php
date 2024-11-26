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
        Schema::table('seller_packages', function (Blueprint $table) {
           $table->string('stripe_product_id')->nullable()->index();
           $table->string('stripe_price_id')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seller_packages', function (Blueprint $table) {
            $table->dropColumn('stripe_product_id');
            $table->dropColumn('stripe_price_id');

        });
    }
};
