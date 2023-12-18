<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if(Schema::hasTable('categories') && !Schema::hasColumn('categories','status')){

            Schema::table('categories', function (Blueprint $table) {
                $table->boolean('status')->nullable()->default(true);
            });
        }
    }
};
