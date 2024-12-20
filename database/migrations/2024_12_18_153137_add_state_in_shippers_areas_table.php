<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shippers_areas', function (Blueprint $table) {
            // Check if `area_id` exists and drop it if it does
            if (Schema::hasColumn('shippers_areas', 'area_id')) {
                $table->dropForeign(['area_id']); // Drop foreign key first
                $table->dropColumn('area_id');
            }

            // Check if `state_id` already exists before adding it
            if (!Schema::hasColumn('shippers_areas', 'state_id')) {
                $table->unsignedBigInteger('state_id')->after('emirate_id');

                $table->foreign('state_id')
                      ->references('id')
                      ->on('states')
                      ->onDelete('cascade'); // Optionally cascade delete
            }
        });
    }

    public function down(): void
    {
        Schema::table('shippers_areas', function (Blueprint $table) {
            // Rollback: remove `state_id` and re-add `area_id`
            if (Schema::hasColumn('shippers_areas', 'state_id')) {
                $table->dropForeign(['state_id']); // Drop the foreign key first
                $table->dropColumn('state_id'); // Then drop the column
            }

            // Re-add `area_id` if it was dropped
            if (!Schema::hasColumn('shippers_areas', 'area_id')) {
                $table->unsignedBigInteger('area_id')->nullable()->after('emirate_id');
                $table->foreign('area_id')
                      ->references('id')
                      ->on('areas')
                      ->onDelete('cascade'); // Optionally cascade delete
            }
        });
    }
};
