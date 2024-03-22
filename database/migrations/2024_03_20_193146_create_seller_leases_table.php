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
        Schema::create('seller_leases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id') ;
            $table->integer('package_id') ;
            $table->integer('total')->nullable() ;
            $table->integer('discount')->nullable() ;
            $table->date('start_date')->nullable() ;
            $table->date('end_date')->nullable() ;
            $table->text('roles')->nullable() ;
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('users')->onDelete('cascade') ;
            $table->foreign('package_id')->references('id')->on('seller_packages')->onDelete('cascade') ;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seller_leases');
    }
};
