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
        Schema::table('addresses', function ($table) {
            $table->string('full_name')->nullable();
            $table->string('building_name')->nullable();
            $table->string('landmark')->nullable();
            $table->string('address_type')->nullable();
            $table->text('delivery_instructions')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn(['full_name', 'building_name', 'landmark', 'address_type', 'delivery_instructions']);
        });
    }
};
