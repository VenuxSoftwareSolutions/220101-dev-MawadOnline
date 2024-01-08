<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if(Schema::hasTable('categories') && !Schema::hasColumn('categories','description')){

            Schema::table('categories', function (Blueprint $table) {
                $table->string('description',255)->nullable()->after('slug');
            });
        }


        if(Schema::hasTable('category_translations') && !Schema::hasColumn('category_translations','description')){

            Schema::table('category_translations', function (Blueprint $table) {
                $table->string('description',255)->nullable()->after('name');
            });
        }
        if(Schema::hasTable('category_translations') && !Schema::hasColumn('category_translations','meta_title')){

            Schema::table('category_translations', function (Blueprint $table) {
                $table->string('meta_title',255)->nullable()->after('description');
            });
        }
        if(Schema::hasTable('category_translations') && !Schema::hasColumn('category_translations','meta_description')){

            Schema::table('category_translations', function (Blueprint $table) {
                $table->string('meta_description',255)->nullable()->after('meta_title');
            });
        }
    }
};
