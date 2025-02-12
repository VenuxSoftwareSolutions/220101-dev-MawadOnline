<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_attribute_values', function (Blueprint $table) {
            $table->unsignedBigInteger("default_unit_id")->nullable();

            $table->foreign("default_unit_id")
                  ->references("id")
                  ->on("unites")
                  ->onDelete("set null");

            $table->text("default_unit_conv_value");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_attribute_values', function (Blueprint $table) {
            $table->dropForeign(["default_unit_id"]);

            $table->dropColumn("default_unit_id");
            $table->dropColumn("default_unit_conv_value");
        });
    }
};
