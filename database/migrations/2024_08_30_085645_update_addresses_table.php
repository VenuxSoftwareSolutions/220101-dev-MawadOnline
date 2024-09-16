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
        Schema::table('addresses', function (Blueprint $table) {

            // Add new columns
            $table->string('full_name')->after('user_id')->nullable();


            $table->string('building_name')->nullable();

            $table->string('landmark')->nullable();
            $table->enum('address_type', ['home', 'work', 'site', 'other'])->after('landmark')->nullable();
            $table->text('delivery_instructions')->after('address_type')->nullable();
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
            //
        });
    }
};
