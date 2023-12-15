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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
             $table->string('warehouse_name')->required();
            $table->string('address_street')->required();
            $table->string('address_building')->required();
            $table->string('address_unit')->nullable();
            $table->unsignedBigInteger('emirate_id')->required();
            $table->unsignedBigInteger('area_id')->required();
            // Add other columns as needed

            $table->foreign('emirate_id')->references('id')->on('emirates');
            $table->foreign('area_id')->references('id')->on('areas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouses');
    }
};
