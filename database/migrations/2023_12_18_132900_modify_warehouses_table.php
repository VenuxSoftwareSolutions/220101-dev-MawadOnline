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
        Schema::table('warehouses', function (Blueprint $table) {
            $table->string('warehouse_name')->nullable()->change();
            $table->string('address_street')->nullable()->change();
            $table->string('address_building')->nullable()->change();
            $table->string('address_unit')->nullable()->change();
            $table->unsignedBigInteger('emirate_id')->nullable()->change();
            $table->unsignedBigInteger('area_id')->nullable()->change();
            $table->boolean('saveasdraft')->default(false);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warehouses', function (Blueprint $table) {
            //
        });
    }
};
