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
        Schema::table('payout_information', function (Blueprint $table) {
            $table->string('bank_name')->nullable()->change();
            $table->string('account_name')->nullable()->change();
            $table->string('account_number')->nullable()->change();
            $table->string('iban')->nullable()->change();
            $table->string('swift_code')->nullable()->change();
            $table->string('iban_certificate')->nullable()->change();
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
        //
    }
};
