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
        Schema::table('product_catalogs', function (Blueprint $table) {
            $table->integer('activate_third_party_sample')->default(0);
            $table->integer('length_sample')->nullable();
            $table->integer('width_sample')->nullable();
            $table->integer('height_sample')->nullable();
            $table->string('package_weight_sample')->nullable();
            $table->string('weight_unit_sample')->nullable();
            $table->string('breakable_sample')->nullable();
            $table->string('unit_third_party_sample')->nullable();
            $table->integer('min_third_party_sample')->nullable();
            $table->integer('max_third_party_sample')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_catalogs', function (Blueprint $table) {
            //
        });
    }
};
