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
        Schema::create('vendor_status_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id') ;
            $table->string('status') ;
            $table->string('suspension_reason')->nullable() ;
            $table->text('details')->nullable() ;
            $table->text('reason')->nullable() ;
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('users')->onDelete('cascade') ;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_status_history');
    }
};
