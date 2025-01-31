<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderDetailIdToStockDetailsTable extends Migration
{
    public function up()
    {
        Schema::table('stock_details', function (Blueprint $table) {
            $table->integer('order_detail_id')->after('order_id');

            $table->foreign('order_detail_id')
                  ->references('id')
                  ->on('order_details');
        });
    }

    public function down()
    {
        Schema::table('stock_details', function (Blueprint $table) {
            $table->dropForeign(['order_detail_id']);

            $table->dropColumn('order_detail_id');
        });
    }
}
