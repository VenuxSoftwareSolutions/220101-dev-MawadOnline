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
            $table->bigInteger('parent_id')->default(0);
            $table->string('sku')->nullable();
            $table->integer('shipping')->default(0);
            $table->integer('is_parent')->default(0);
            $table->integer('vat')->default(0);
            $table->integer('vat_sample')->default(0);
            $table->text('sample_description')->nullable();
            $table->text('short_description')->nullable();
            $table->integer('sample_price')->nullable();
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
