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
        Schema::table('contact_people', function (Blueprint $table) {
            $table->string('first_name')->nullable()->change();
            $table->string('last_name')->nullable()->change();
            $table->string('email')->unique()->nullable()->change();
            $table->string('mobile_phone')->nullable()->change();
            $table->string('additional_mobile_phone')->nullable()->change();
            $table->string('nationality')->nullable()->change();
            $table->date('date_of_birth')->nullable()->change();
            $table->string('emirates_id_number')->nullable()->change();
            $table->date('emirates_id_expiry_date')->nullable()->change();
            $table->string('emirates_id_file_path')->nullable()->change();
            $table->boolean('business_owner')->nullable()->change();
            $table->string('designation')->nullable()->change();
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
        Schema::table('contact_people', function (Blueprint $table) {
            //
        });
    }
};
