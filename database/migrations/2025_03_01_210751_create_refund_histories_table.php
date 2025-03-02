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
        Schema::create('refund_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('refund_id');
            $table->enum('refund_status',['pending','requires_action','succeeded','failed','canceled']);
            $table->string('description_error', 255)->nullable();
            $table->string('payment_refund_id', 255);
            $table->string('payment_charge_id', 255);
            $table->float('amount');
            $table->foreign('refund_id')
                    ->references('id')
                    ->on('refunds');
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
        Schema::dropIfExists('refund_histories');
    }
};
