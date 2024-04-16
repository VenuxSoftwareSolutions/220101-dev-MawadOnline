<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UploadProductCatalog;

class ProductCatalog extends Model
{
    use HasFactory;
    protected $fillable = ['id'];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function getFirstImage(){
        $upload = UploadProductCatalog::where('catalog_id', $this->id)->where('type', 'images')->first();
        $path = '';
        if($upload != null){
            $path = $upload->path;
        }

        return $path;
    }

    public function checkIfParentToGetNumVariants(){
        if($this->is_parent == 0){
            return ProductCatalog::where('parent_id', $this->parent_id)->count();
        }else{
            return ProductCatalog::where('parent_id', $this->id)->count();
        }
    }

    public function getChildrenProducts(){
        $childrens = ProductCatalog::where('parent_id', $this->id)->get();
        return $childrens;
    }
}
