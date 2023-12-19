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
        Schema::table('business_information', function (Blueprint $table) {
                   // Make all columns except user_id nullable
                   $table->string('trade_name')->nullable()->change();
                   $table->string('trade_license_doc')->nullable()->change();
                   $table->string('eshop_name')->nullable()->change();
                   $table->text('eshop_desc')->nullable()->change();
                   $table->date('license_issue_date')->nullable()->change();
                   $table->date('license_expiry_date')->nullable()->change();
                   $table->unsignedBigInteger('state')->nullable()->change();
                   $table->unsignedBigInteger('area_id')->nullable()->change();

                   $table->string('street')->nullable()->change();
                   $table->string('building')->nullable()->change();
                   $table->string('unit')->nullable()->change();
                   $table->string('po_box')->nullable()->change();
                   $table->string('landline')->nullable()->change();
                   $table->boolean('vat_registered')->nullable()->change();
                   $table->string('vat_certificate')->nullable()->change();
                   $table->string('trn')->nullable()->change();
                   $table->string('tax_waiver')->nullable()->change();
                   $table->string('civil_defense_approval')->nullable()->change();

                   // Add a new column 'saveasdraft'
                   $table->boolean('saveasdraft')->default(false)->comment('Save as Draft');
                });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('business_information', function (Blueprint $table) {
            //
        });
    }
};
