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
        Schema::create('business_information', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('trade_name');
            $table->string('trade_license_doc');
            $table->string('eshop_name');
            $table->text('eshop_desc')->nullable();
            $table->date('license_issue_date');
            $table->date('license_expiry_date');
            $table->foreignId('state')->constrained('emirates');
            $table->foreignId('area_id')->constrained('areas');
            $table->string('street');
            $table->string('building');
            $table->string('unit')->nullable();
            $table->string('po_box')->nullable();
            $table->string('landline')->nullable();
            $table->boolean('vat_registered');
            $table->string('vat_certificate')->nullable();
            $table->string('trn')->nullable();
            $table->string('tax_waiver')->nullable();
            $table->string('civil_defense_approval')->nullable();
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
        Schema::dropIfExists('business_information');
    }
};
