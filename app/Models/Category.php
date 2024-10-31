<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App;

class Category extends Model
{
    protected $with = ['category_translations'];

    // Parent Category Relationship
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Recursive parents
    public function parents()
    {
        $parents = collect([]);

        $parent = $this->parent;

        while (!is_null($parent)) {
            $parents->prepend($parent);
            $parent = $parent->parent;
        }

        return $parents;
    }

    public function parents_ids()
    {
        $parents = collect([]);

        $parent = $this->parent;

        while (!is_null($parent)) {
            $parents->prepend($parent->id);
            $parent = $parent->parent;
        }

        return $parents;
    }

    public function getTranslation($field = '', $lang = false)
    {
        $lang = $lang == false ? App::getLocale() : $lang;
        $category_translation = $this->category_translations->where('lang', $lang)->first();
        return $category_translation != null ? $category_translation->$field : $this->$field;
    }

    public function category_translations()
    {
        return $this->hasMany(CategoryTranslation::class);
    }

    public function coverImage()
    {
        return $this->belongsTo(Upload::class, 'cover_image');
    }

    public function catIcon()
    {
        return $this->belongsTo(Upload::class, 'icon');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_categories');
    }

    public function bannerImage()
    {
        return $this->belongsTo(Upload::class, 'banner');
    }

    public function classified_products()
    {
        return $this->hasMany(CustomerProduct::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function childrenCategories()
    {
        return $this->hasMany(Category::class, 'parent_id')->with('categories');
    }

    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class);
    }

    public function sizeChart()
    {
        return $this->belongsTo(SizeChart::class, 'id', 'category_id');
    }

    public function categories_attributes()
    {
        return $this->belongsToMany(Attribute::class, 'categories_has_attributes', 'category_id', 'attribute_id');
    }
    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }

    public function hasActiveDiscounts()
    {
        return $this->discounts()->active()->withinDateRange()->exists();
    }

    public function hasActiveCoupons()
    {
        return $this->coupons()->active()->withinDateRange()->exists();
    }


    
}
