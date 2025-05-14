<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->unsignedBigInteger('applied_discount_id')->nullable()->after('discount_share');

            $table->foreign('applied_discount_id')->references('id')->on('discounts')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropForeign(['applied_discount_id']);
            $table->dropColumn('applied_discount_id');
        });
    }
};
