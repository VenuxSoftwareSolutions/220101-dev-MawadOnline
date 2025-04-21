<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        if (!Schema::hasTable('bu_jobs')) {
            Schema::create('bu_jobs', function (Blueprint $table) {
                $table->string('id', 40)->primary();
                $table->unsignedBigInteger('vendor_user_id');
                $table->string('vendor_products_file', 255)->nullable();
                $table->string('preprocessed_file', 255)->nullable();
                $table->string('ai_processed_file', 255)->nullable();
                $table->integer('total_rows')->nullable();
                $table->string('images_base_folder', 255)->nullable();
                $table->string('images_base_folder_final_url', 512)->nullable();
                $table->string('images_base_folder_host', 32)->nullable();
                $table->string('docs_base_folder', 255)->nullable();
                $table->string('docs_base_folder_final_url', 512)->nullable();
                $table->string('docs_base_folder_host', 32)->nullable();
                $table->string('vendor_product_shipping', 1024)->nullable();
                $table->string('mwd3p_product_shipping', 512)->nullable();
                $table->string('product_discount', 512)->nullable();
                $table->timestamp('created_at')->nullable()->useCurrent();
                $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
                $table->string('stage', 16)->nullable();
                $table->tinyInteger('progress')->unsigned()->default(0);
                $table->boolean('has_errors')->default(false);
                $table->string('error_msg', 255)->nullable();
                $table->string('error_file', 255)->nullable();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('bu_jobs');
    }
};
