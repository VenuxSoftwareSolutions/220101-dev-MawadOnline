<?php

namespace App\Models;

use App;
use App\Models\UploadProducts;
use App\Models\ProductCategory;
use App\Models\ProductAttributeValues;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use \Venturecraft\Revisionable\RevisionableTrait;
use App\Traits\EnhancedRevisionableTrait;
use Illuminate\Support\Facades\DB;

class Product extends Model
{

    use EnhancedRevisionableTrait;

    protected function getLastActionNumber()
    {
        $lastRevision = $this->revisionHistory()->latest('id')->first();
        return $lastRevision ? $lastRevision->action_number + 1 : 0;
    }
    
    protected $guarded = ['choice_attributes'];

    protected $with = ['product_translations', 'taxes', 'thumbnail'];

    public function getTranslation($field = '', $lang = false)
    {
        $lang = $lang == false ? App::getLocale() : $lang;
        $product_translations = $this->product_translations->where('lang', $lang)->first();
        return $product_translations != null ? $product_translations->$field : $this->$field;
    }

    public function product_translations()
    {
        return $this->hasMany(ProductTranslation::class);
    }

    public function main_category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->where('status', 1);
    }

    public function product_queries()
    {
        return $this->hasMany(ProductQuery::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function stocks()
    {
        return $this->hasMany(ProductStock::class);
    }

    public function taxes()
    {
        return $this->hasMany(ProductTax::class);
    }

    public function flash_deal_product()
    {
        return $this->hasOne(FlashDealProduct::class);
    }

    public function bids()
    {
        return $this->hasMany(AuctionProductBid::class);
    }

    public function thumbnail()
    {
        return $this->belongsTo(Upload::class, 'thumbnail_img');
    }

    public function scopePhysical($query)
    {
        return $query->where('digital', 0);
    }

    public function scopeDigital($query)
    {
        return $query->where('digital', 1);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function scopeIsApprovedPublished($query)
    {
        return $query->where('approved', '1')->where('published', 1);
    }

    public function getChildrenProducts(){
        $childrens = Product::where('parent_id', $this->id)->get();
        return $childrens;
    }

    public function getChildrenProductsDesc(){
        $childrens = Product::where('parent_id', $this->id)->orderBy('id', 'asc')->get();
        return $childrens;
    }

    public function getImagesProduct(){
        $images = UploadProducts::where('id_product', $this->id)->where('type', 'images')->get();
        return $images;
    }

    public function getThumbnailsProduct(){
        $thumbnails = UploadProducts::where('id_product', $this->id)->where('type', 'thumbnails')->get();
        return $thumbnails;
    }

    public function getDocumentsProduct(){
        $documents = UploadProducts::where('id_product', $this->id)->where('type', 'documents')->get();
        return $documents;
    }

    public function getPricingConfiguration(){
        $pricing = PricingConfiguration::where('id_products', $this->id)->get();
        return $pricing;
    }

    public function getIdsAttributesVariant(){
        $ids = ProductAttributeValues::where('id_products', $this->id)->where('is_variant', 1)->pluck('id_attribute')->toArray();
        return $ids;
    }

    public function getAttributesVariant(){
        $attributes = ProductAttributeValues::where('id_products', $this->id)->where('is_variant', 1)->get();
        $variants_id = ProductAttributeValues::where('id_products', $this->id)->where('is_variant', 1)->pluck('id')->toArray();
        $historique_children = DB::table('revisions')->whereIn('revisionable_id', $variants_id)->where('revisionable_type', 'App\Models\ProductAttributeValues')->get();
        if(count($historique_children) > 0){
            foreach($historique_children as $historique_child){
                foreach($attributes as $variant){
                    if($variant->id == $historique_child->revisionable_id){
                        $variant->old_value = $historique_child->old_value;
                    }
                }
            }
        }
        $data = [];
        if(count($attributes) > 0){
            foreach ($attributes as $attribute){
                $data[$attribute->id_attribute] = $attribute;
            }
        }
        return $data;
    }

    public function getIdsAttributesChildren(){
        $children = Product::where('parent_id', $this->id)->first();
        $ids = [];
        if($children != null){
            $ids = ProductAttributeValues::where('id_products', $children->id)->where('is_variant', 1)->pluck('id_attribute')->toArray();
        }

        return $ids;
    }

    public function getAttributesVariantChildren(){
        $attributes = ProductAttributeValues::where('id_products', $this->id)->where('is_variant', 1)->get();
        
        $data = [];
        if(count($attributes) > 0){
            foreach ($attributes as $attribute){
                $data[$attribute->id_attribute] = $attribute;
            }
        }
        return $data;
    }

    public function pathCategory(){
        $product_category = ProductCategory::where('product_id', $this->id)->first();
        $path = '';
        if($product_category != null){
            $current_category = Category::find($product_category->category_id);
            if($current_category != null){
                while($current_category->parent_id != 0){
                    if($path == ''){
                        $path = $current_category->name;
                    }else{
                        $path = $current_category->name . ' > '  . $path;
                    }
                    $current_category = Category::find($current_category->parent_id);
                }
                if($current_category->parent_id == 0){
                    if($path == ''){
                        $path = $current_category->name;
                    }else{
                        $path = $current_category->name . ' > '  . $path;
                    }
                }
            }

            
        }

        return $path;
    }
}
