<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommissionVatTable extends Migration
{
    public function up()
    {
        Schema::create('commission_vat', function (Blueprint $table) {
            $table->id();
            $table->integer("sub_order_id");
            $table->foreign('sub_order_id')
                  ->references('id')
                  ->on('order_details')
                  ->onDelete('cascade');
            $table->double('price_vat_incl', 20, 2);
            $table->double('discount_percentage')->nullable();
            $table->double('price_after_discount_vat_incl');
            $table->double('mwd_commission_percentage')->nullable();
            $table->double('mwd_commission_percentage_amount')->nullable();
            $table->double('mwd_commission_percentage_vat')->nullable();
            $table->double('mwd_commission_percentage_vat_amount')->nullable();
            $table->double('mwd_total_percentage')->nullable();
            $table->double('price_after_mwd_percentage');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('commission_vat');
    }
}
