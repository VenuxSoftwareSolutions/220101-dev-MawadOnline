<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shippers_areas', function (Blueprint $table) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            if (Schema::hasColumn('shippers_areas', 'area_id')) {
                $table->dropColumn('area_id');
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            $table->unsignedBigInteger('state_id')->after("emirate_id");

            $table->foreign('state_id')->references('id')->on('states');
        });
    }

    public function down(): void
    {
        Schema::table('shippers_areas', function (Blueprint $table) {
            $table->dropForeign(['state_id']);
            $table->dropColumn('state_id');

            $table->unsignedBigInteger('area_id')->nullable()->after('emirate_id');

            $table->foreign('area_id')->references('id')->on('areas');
        });
    }
};
