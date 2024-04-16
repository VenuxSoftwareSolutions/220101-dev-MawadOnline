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
        Schema::create('product_catalogs', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('added_by', 6)->default('admin');
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('brand_id')->nullable();
            $table->string('photos', 2000)->nullable();
            $table->string('thumbnail_img', 100)->nullable();
            $table->string('video_provider', 20)->nullable();
            $table->string('video_link', 100)->nullable();
            $table->string('tags', 500)->nullable();
            $table->longText('description')->nullable();
            $table->double('unit_price', 20, 2);
            $table->double('purchase_price', 20, 2)->nullable();
            $table->unsignedInteger('variant_product')->default(0);
            $table->string('attributes', 1000)->default('[]');
            $table->mediumText('choice_options')->nullable();
            $table->mediumText('colors')->nullable();
            $table->text('variations')->nullable();
            $table->unsignedInteger('todays_deal')->default(0);
            $table->unsignedInteger('published')->default(1);
            $table->unsignedTinyInteger('approved')->default(1);
            $table->string('stock_visibility_state', 10)->default('quantity');
            $table->unsignedTinyInteger('cash_on_delivery')->default(0);
            $table->unsignedInteger('featured')->default(0);
            $table->unsignedInteger('seller_featured')->default(0);
            $table->unsignedInteger('current_stock')->default(0);
            $table->string('unit', 20)->nullable();
            $table->string('weight', 191)->default(0.00)->nullable();
            $table->unsignedInteger('min_qty')->default(1);
            $table->unsignedInteger('low_stock_quantity')->nullable();
            $table->double('discount', 20, 2)->nullable();
            $table->string('discount_type', 10)->nullable();
            $table->unsignedInteger('discount_start_date')->nullable();
            $table->unsignedInteger('discount_end_date')->nullable();
            $table->double('tax', 20, 2)->nullable();
            $table->string('tax_type', 10)->nullable();
            $table->string('shipping_type', 20)->default('flat_rate');
            $table->double('shipping_cost', 20, 2)->default(0.00);
            $table->unsignedTinyInteger('is_quantity_multiplied')->default(0);
            $table->unsignedInteger('est_shipping_days')->nullable();
            $table->unsignedInteger('num_of_sale')->default(0);
            $table->mediumText('meta_title')->nullable();
            $table->longText('meta_description')->nullable();
            $table->string('meta_img', 255)->nullable();
            $table->string('pdf', 255)->nullable();
            $table->mediumText('slug');
            $table->unsignedInteger('refundable')->default(0);
            $table->double('earn_point', 8, 2)->default(0.00);
            $table->double('rating', 8, 2)->default(0.00);
            $table->string('barcode', 255)->nullable();
            $table->unsignedInteger('digital')->default(0);
            $table->unsignedInteger('auction_product')->default(0);
            $table->string('file_name', 255)->nullable();
            $table->string('file_path', 255)->nullable();
            $table->string('external_link', 500)->nullable();
            $table->string('external_link_btn', 255)->default('Buy Now');
            $table->unsignedInteger('wholesale_product')->default(0);
            $table->string('country_code', 191);
            $table->string('manufacturer', 191);
            $table->unsignedBigInteger('parent_id')->default(0);
            $table->string('sku', 191)->nullable();
            $table->unsignedInteger('shipping')->default(0);
            $table->unsignedInteger('is_parent')->default(0);
            $table->unsignedInteger('vat')->default(0);
            $table->unsignedInteger('vat_sample')->default(0);
            $table->text('sample_description')->nullable();
            $table->text('short_description')->nullable();
            $table->unsignedInteger('sample_price')->nullable();
            $table->unsignedTinyInteger('is_draft')->default(0);
            $table->text('rejection_reason')->nullable();
            $table->unsignedInteger('activate_third_party')->default(0);
            $table->unsignedInteger('length')->nullable();
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->unsignedInteger('min_third_party')->nullable();
            $table->unsignedInteger('max_third_party')->nullable();
            $table->string('breakable', 191)->nullable();
            $table->string('unit_third_party', 191)->nullable();
            $table->string('shipper_sample', 191)->nullable();
            $table->unsignedInteger('estimated_sample')->nullable();
            $table->unsignedInteger('estimated_shipping_sample')->nullable();
            $table->string('paid_sample', 191)->nullable();
            $table->double('shipping_amount', 8, 2)->nullable();
            $table->unsignedInteger('sample_available')->default(0);
            $table->string('unit_weight', 191)->nullable();
            $table->softDeletes();
            $table->string('stock_after_create', 191)->nullable();
            $table->unsignedInteger('catalog')->default(0);
            $table->integer('added_from_catalog')->default(0);
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
        Schema::dropIfExists('product_catalogs');
    }
};
