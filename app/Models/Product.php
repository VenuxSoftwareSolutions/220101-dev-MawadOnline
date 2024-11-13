<?php

namespace App\Models;

use App;
use App\Traits\EnhancedRevisionableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use EnhancedRevisionableTrait, SoftDeletes;

    protected $dontKeepRevisionOf = [
        'is_draft',
        'approved',
        'sku',
        'deleted_at',
        'rejection_reason',
        'slug',
        'low_stock_quantity',
        'published',
        'shipping',
        'vat_sample',
        'sample_description',
        'vat',
        'sample_price',
        'activate_third_party',
        'length',
        'width',
        'height',
        'min_third_party',
        'max_third_party',
        'breakable',
        'unit_third_party',
        'shipper_sample',
        'estimated_sample',
        'estimated_shipping_sample',
        'paid_sample',
        'shipping_amount',
        'sample_available',
        'unit_weight',
        'last_version',
        'product_added_from_catalog',
        'activate_third_party_sample',
        'length_sample',
        'width_sample',
        'height_sample',
        'package_weight_sample',
        'weight_unit_sample',
        'breakable_sample',
        'unit_third_party_sample',
        'min_third_party_sample',
        'max_third_party_sample',
        'product_catalog_id',
    ];
    protected $guarded = ['choice_attributes'];

    protected $with = ['product_translations', 'taxes', 'thumbnail'];

    // Ensure cascading deletes at the model level
    protected static function booted()
    {
        static::deleting(function ($product) {
            // Automatically delete related stock summaries
            $product->stockSummaries()->delete();
        });
    }

    public function stockSummaries()
    {
        return $this->hasMany(StockSummary::class, 'variant_id', 'id');
    }

    public function getTotalQuantity()
    {
        return $this->stockSummaries()->sum('current_total_quantity');
    }

    protected function getLastActionNumber()
    {
        $lastRevision = $this->revisionHistory()->latest('id')->first();

        return $lastRevision ? $lastRevision->action_number + 1 : 0;
    }

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

    public function variants()
    {
        return $this->hasMany(Product::class, 'parent_id', 'id');
    }

    public function medias()
    {
        return $this->hasMany(UploadProducts::class, 'id_product', 'id');
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

    public function pricingConfiguration()
    {
        return $this->hasMany(PricingConfiguration::class, 'id_products', 'id');
    }

    public function get_ratting()
    {
        if ($this->reviews->count() > 0) {
            return (int) ($this->reviews->sum('rating') / $this->reviews->count());
        } else {
            return 0;
        }
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

    public function getChildrenProducts()
    {
        return Product::where('parent_id', $this->id)->get();
    }

    public function getChildrenProductsDesc()
    {
        return Product::where('parent_id', $this->id)->orderBy('id', 'asc')->get();
    }

    public function getImagesProduct()
    {
        return UploadProducts::where('id_product', $this->id)->where('type', 'images')->get();
    }

    public function getThumbnailsProduct()
    {
        return UploadProducts::where('id_product', $this->id)->where('type', 'thumbnails')->get();
    }

    public function getDocumentsProduct()
    {
        return UploadProducts::where('id_product', $this->id)->where('type', 'documents')->get();
    }

    public function getPricingConfiguration()
    {
        return PricingConfiguration::where('id_products', $this->id)->get();
    }

    public function getFirstPricingConfiguration()
    {
        return PricingConfiguration::where('id_products', $this->id)->first();
    }

    public function getIdsAttributesVariant()
    {
        return ProductAttributeValues::where('id_products', $this->id)->where('is_variant', 1)->pluck('id_attribute')->toArray();
    }

    public function getAttributesVariant()
    {
        $attributes = ProductAttributeValues::where('id_products', $this->id)->where('is_variant', 1)->get();
        $variants_id = ProductAttributeValues::where('id_products', $this->id)->where('is_variant', 1)->pluck('id')->toArray();
        $historique_children = DB::table('revisions')->whereNull('deleted_at')->whereIn('revisionable_id', $variants_id)->where('revisionable_type', 'App\Models\ProductAttributeValues')->get();

        if (count($historique_children) > 0) {
            foreach ($historique_children as $historique_child) {
                foreach ($attributes as $variant) {
                    if ($variant->id == $historique_child->revisionable_id) {
                        $variant->key = $historique_child->key;
                        if ($historique_child->key == 'add_attribute') {
                            $variant->added = true;
                        } else {
                            if ($historique_child->key == 'id_units') {
                                $unit = Unity::find($historique_child->old_value);
                                if ($unit != null) {
                                    $variant->old_value = $unit->name;
                                } else {
                                    $variant->old_value = '';
                                }
                            } else {
                                $variant->old_value = $historique_child->old_value;
                            }
                        }
                    }
                }
            }
        }

        $data = [];
        if (count($attributes) > 0) {
            foreach ($attributes as $attribute) {
                if ($attribute->id_colors != null) {
                    if (isset($attribute->added)) {
                        if (array_key_exists($attribute->id_attribute, $data)) {
                            array_push($data[$attribute->id_attribute], 'yes');
                        } else {
                            $data[$attribute->id_attribute] = ['yes'];
                        }
                    }
                    if (array_key_exists($attribute->id_attribute, $data)) {
                        array_push($data[$attribute->id_attribute], $attribute->id_colors);
                    } else {
                        $data[$attribute->id_attribute] = [$attribute->id_colors];
                    }
                } else {
                    $data[$attribute->id_attribute] = $attribute;
                }

            }
        }

        return $data;
    }

    public function getIdsAttributesChildren()
    {
        $children = Product::where('parent_id', $this->id)->first();
        $ids = [];
        if ($children != null) {
            $ids = ProductAttributeValues::where('id_products', $children->id)->where('is_variant', 1)->pluck('id_attribute')->toArray();
        }

        return $ids;
    }

    public function getAttributesVariantChildren()
    {
        $attributes = ProductAttributeValues::where('id_products', $this->id)->where('is_variant', 1)->get();
        $data = [];
        if (count($attributes) > 0) {
            foreach ($attributes as $attribute) {
                $data[$attribute->id_attribute] = $attribute;
            }
        }

        return $data;
    }

    public function pathCategory()
    {
        $product_category = ProductCategory::where('product_id', $this->id)->first();
        $path = '';
        if ($product_category != null) {
            $current_category = Category::find($product_category->category_id);
            if ($current_category != null) {
                while ($current_category->parent_id != 0) {
                    if ($path == '') {
                        $path = $current_category->name;
                    } else {
                        $path = $current_category->name.' > '.$path;
                    }
                    $current_category = Category::find($current_category->parent_id);
                }
                if ($current_category->parent_id == 0) {
                    if ($path == '') {
                        $path = $current_category->name;
                    } else {
                        $path = $current_category->name.' > '.$path;
                    }
                }
            }
        }

        return $path;
    }

    public function productAttributeValues()
    {
        return $this->hasMany(ProductAttributeValues::class, 'id_products', 'id');
    }

    public function productVariantDetails()
    {
        try {
            $productVariantName = ' ';
            foreach ($this->productAttributeValues as $productAttributeValue) {
                if ($productAttributeValue->attribute->type_value == 'numeric') {
                    $productVariantName .= $productAttributeValue->attribute->name.' '.$productAttributeValue->value.' '.$productAttributeValue->unity->name.' ';
                } elseif ($productAttributeValue->attribute->type_value == 'list') {
                    $productVariantName .= $productAttributeValue->attribute->name.' '.$productAttributeValue->attributeValues->value.' ';

                } elseif ($productAttributeValue->attribute->type_value == 'color') {
                    $productVariantName .= $productAttributeValue->attribute->name.' '.$productAttributeValue->color->name.' ';

                } else {
                    $productVariantName .= $productAttributeValue->attribute->name.' '.$productAttributeValue->value.' ';

                }
            }

            return $productVariantName;
        } catch (\Exception $e) {
            return ' ';
        }
    }

    public function getShipping()
    {
        return Shipping::where('product_id', $this->id)->get();
    }

    public function getIdsChildrens()
    {
        return Product::where('parent_id', $this->id)->pluck('id')->toArray();
    }

    public function getPriceRange()
    {
        $firstPrice = PricingConfiguration::where('id_products', $this->id)
            ->orderBy('unit_price', 'asc')
            ->pluck('unit_price')
            ->first();

        $lastPrice = PricingConfiguration::where('id_products', $this->id)
            ->orderBy('unit_price', 'desc')
            ->pluck('unit_price')
            ->first();

        if ($lastPrice == $firstPrice) {
            return $firstPrice.' AED';
        } else {
            return $firstPrice.' AED - '.$lastPrice.' AED';
        }
    }

    public function getFirstImage()
    {
        $upload = UploadProducts::where('id_product', $this->id)->where('type', 'images')->first();

        $path = '';
        if ($upload != null) {
            $path = $upload->path;
        }

        return $path;
    }

    public function checkIfParentToGetNumVariants()
    {
        if ($this->is_parent == 0) {
            return Product::where('parent_id', $this->parent_id)->count();
        } else {
            return Product::where('parent_id', $this->id)->count();
        }
    }

    public function children()
    {
        return $this->hasMany(Product::class, 'parent_id', 'id');
    }

    public function hasUnapprovedChildren()
    {
        return $this->children()->where('approved', 0)->exists();
    }

    public function getShopName()
    {
        $shop = Shop::where('user_id', $this->user_id)->first();
        if ($shop != null) {
            return $shop->name;
        } else {
            return null;
        }
    }

    public function CheckIfAddedToCatalog()
    {
        $exist = ProductCatalog::where('product_id', $this->id)->first();
        if ($exist != null) {
            return true;
        } else {
            return false;
        }
    }

    public function shippingOptions($qty)
    {
        // Fetch the shipping options based on quantity range
        return Shipping::where('product_id', $this->id)->where('from_shipping', '<=', $qty)
            ->where('to_shipping', '>=', $qty)
            ->first();
    }

    public function minMaxQuantity()
    {
        $minFrom = PricingConfiguration::where('id_products', $this->id)->min('from');

        $maxTo = PricingConfiguration::where('id_products', $this->id)->max('to');

        return [
            'minFrom' => $minFrom ?? 1,
            'maxTo' => $maxTo ?? 1,
        ];
    }
}
