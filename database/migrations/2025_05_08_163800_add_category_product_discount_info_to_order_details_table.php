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
            $table->double('price_before_discount', 20, 2)
                ->nullable()
                ->default(null)
                ->after('discount_share');
            $table->double('price_after_discount', 20, 2)
                ->nullable()
                ->default(null)
                ->after('discount_share');
            $table->double('discount_percentage', 20, 2)
                ->nullable()
                ->default(null)
                ->after('discount_share');
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
            $table->dropColumn('price_before_discount');
            $table->dropColumn('price_after_discount');
            $table->dropColumn('discount_percentage');
        });
    }
};
