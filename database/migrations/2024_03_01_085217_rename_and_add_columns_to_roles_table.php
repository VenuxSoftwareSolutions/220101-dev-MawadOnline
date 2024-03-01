<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameAndAddColumnsToRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            // Rename column from seller_id to created_by
            $table->renameColumn('seller_id', 'created_by');

            // Add a new column role_type
            $table->integer('role_type')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {
            // Rollback changes: Rename created_by column back to seller_id
            $table->renameColumn('created_by', 'seller_id');

            // Rollback changes: Drop role_type column
            $table->dropColumn('role_type');
        });
    }
}

