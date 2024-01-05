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
        Schema::table('attributes', function (Blueprint $table) {
            $table->string('name_display_english')->after('name');
            $table->string('name_display_arabic')->after('name_display_english');
            $table->string('type_value')->after('name_display_arabic');
            $table->text('description_english')->after('type_value')->nullable();
            $table->text('description_arabic')->after('description_english')->nullable();
            $table->unsignedBigInteger('id_unites')->after('description_arabic')->nullable();

            $table->foreign('id_unites')->references('id')->on('unites');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attributes', function (Blueprint $table) {
            //
        });
    }
};
