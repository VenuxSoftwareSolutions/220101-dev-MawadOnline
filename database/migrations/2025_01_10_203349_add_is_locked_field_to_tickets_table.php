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
        Schema::table('tickets', function (Blueprint $table) {
            $table->enum('is_locked',['yes','no'])->default('no');
            $table->unsignedBigInteger('locked_for')->nullable();
            $table->foreign('locked_for')
                    ->references('id')
                    ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('is_locked');
            $table->dropForeign('locked_for');
            $table->dropColumn('locked_for');
        });
    }
};
