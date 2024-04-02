<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\UploadProducts;
use Auth;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function search(){
        return view('seller.product.catalog.search');
    }

    public function search_action(Request $request){
        $products = [];
        $searchTerm = $request->name;
        if(Auth::user()->user_type == "seller"){
            $products = Product::where(function ($query) use ($searchTerm) {
                                    $query->where('name', 'like', "%{$searchTerm}%")
                                        ->orWhereHas('brand', function ($query) use ($searchTerm) {
                                            $query->where('name', 'like', "%{$searchTerm}%");
                                        });
                                })->where('catalog', 1)->take(3)->get();
        }elseif(Auth::user()->user_type == "admin"){
            $products = Product::where(function ($query) use ($searchTerm) {
                                    $query->where('name', 'like', "%{$searchTerm}%")
                                        ->orWhereHas('brand', function ($query) use ($searchTerm) {
                                            $query->where('name', 'like', "%{$searchTerm}%");
                                        });
                                })
                                ->where(function ($query) {
                                    $query->where('catalog', 1)
                                        ->orWhere(function ($query) {
                                            $query->where('catalog', 0)
                                                    ->where('approved', 1);
                                        });
                                })->take(3)->get();
        }

        return view('seller.product.catalog.result')->with(['products' =>  $products, 'search' => $request->name]);
    }

    public function see_all($search){
        if (!is_null($search)) {
            $products = [];
            if(Auth::user()->user_type == "seller"){
                $products = Product::where(function ($query) use ($search) {
                                        $query->where('name', 'like', "%{$search}%")
                                            ->orWhereHas('brand', function ($query) use ($search) {
                                                $query->where('name', 'like', "%{$search}%");
                                            });
                                    })->where('catalog', 1)->take(3)->get();
            }elseif(Auth::user()->user_type == "admin"){
                $products = Product::where(function ($query) use ($search) {
                                        $query->where('name', 'like', "%{$search}%")
                                            ->orWhereHas('brand', function ($query) use ($search) {
                                                $query->where('name', 'like', "%{$search}%");
                                            });
                                    })
                                    ->where(function ($query) {
                                        $query->where('catalog', 1)
                                            ->orWhere(function ($query) {
                                                $query->where('catalog', 0)
                                                        ->where('approved', 1);
                                            });
                                    })->take(3)->get();
            }
            return view('seller.product.catalog.see_all',[
                'products' => $products
            ]);
        }else{
            abort(404);
        }
    }

    public function displayPreviewProductInCatalogProduct($id){
        $product = Product::find($id);
        if($product->is_parent != 0){
            $brand = Brand::find($product->brand_id);
        }
    }
}
