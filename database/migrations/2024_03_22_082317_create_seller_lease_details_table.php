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
        Schema::create('seller_lease_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lease_id') ;
            $table->unsignedBigInteger('role_id')->nullable() ;
            $table->integer('amount')->nullable() ;
            $table->date('start_date') ;
            $table->date('end_date') ;
            $table->timestamps();

            $table->foreign('lease_id')->references('id')->on('seller_leases')->onDelete('cascade') ;
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade') ;

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seller_lease_details');
    }
};
