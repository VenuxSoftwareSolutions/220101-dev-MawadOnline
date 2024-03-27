<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\UploadProducts;
use App\Models\PricingConfiguration;
use App\Models\ProductAttributeValues;
use Illuminate\Support\Facades\File;
use App\Models\Unity;
use App\Models\Brand;
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
        $parent = Product::find($id);
        
        if($parent->is_parent == 0){
            if($parent->parent_id != 0){
                $parent = Product::find($parent->parent_id);
            }
        }

        $brand = Brand::find($parent->brand_id);

        $pricing = PricingConfiguration::where('id_products', $parent->id)->get();
        $pricing = [];
        $pricing['from'] = PricingConfiguration::where('id_products', $parent->id)->pluck('from')->toArray();
        $pricing['to'] = PricingConfiguration::where('id_products', $parent->id)->pluck('to')->toArray();
        $pricing['unit_price'] = PricingConfiguration::where('id_products', $parent->id)->pluck('unit_price')->toArray();
        
        $variations = [];
        $pricing_children = [];
        if($parent->is_parent != 0){
            $childrens_ids = Product::where('parent_id', $parent->id)->pluck('id')->toArray();
            foreach($childrens_ids as $children_id){
                $variations[$children_id]['variant_pricing-from']['from'] = PricingConfiguration::where('id_products', $children_id)->pluck('from')->toArray();
                $variations[$children_id]['variant_pricing-from']['to'] = PricingConfiguration::where('id_products', $children_id)->pluck('to')->toArray();
                $variations[$children_id]['variant_pricing-from']['unit_price'] = PricingConfiguration::where('id_products', $children_id)->pluck('unit_price')->toArray();
                
                $attributes_variant = ProductAttributeValues::where('id_products', $children_id)->where('is_variant', 1)->get();

                foreach($attributes_variant as $attribute){
                    if($attribute->id_units != null){
                        $unit = Unity::find($attribute->id_units);
                        if ($unit){
                            $variations[$children_id][$attribute->id_attribute] = $attribute->value.' '.$unit->name;
                        }
                    }else{
                        $variations[$children_id][$attribute->id_attribute] = $attribute->value;
                    }
                }

                $variations[$children_id]['storedFilePaths'] = UploadProducts::where('id_product', $children_id)->where('type', 'images')->pluck('path')->toArray();

            }
        }

        
        $storedFilePaths = UploadProducts::where('id_product', $parent->id)->where('type', 'images')->pluck('path')->toArray();

        $attributes_general = ProductAttributeValues::where('id_products', $parent->id)->where('is_general', 1)->get();
        $attributesGeneralArray = [];
        foreach($attributes_general as $attribute_general){
            if($attribute_general->id_units != null){
                $unit = Unity::find($attribute_general->id_units);
                if ($unit){
                    $attributesGeneralArray[$attribute_general->id_attribute] = $attribute_general->value.' '.$unit->name;
                }
            }else{
                $attributesGeneralArray[$attribute_general->id_attribute] = $attribute_general->value;
            }
        }

        $attributes = [];
        if(count($variations) > 0){
            foreach ($variations as $variation) {
                foreach ($variation as $attributeId => $value) {
                    if ($attributeId != "storedFilePaths" && $attributeId != "variant_pricing-from" ) {
                     if (!isset($attributes[$attributeId])) {
                         $attributes[$attributeId] = [];
                     }
                     // Add value to the unique attributes array if it doesn't already exist
                     if (!in_array($value, $attributes[$attributeId])) {
                         $attributes[$attributeId][] = $value;
                     }
                    }
     
                }
             }
        }

        if (is_array($variations) && !empty($variations)) {
            $lastItem  = end($variations);
            $variationId = key($variations);
        }

        $total = isset($pricing['from'][0]) && isset($pricing['unit_price'][0]) ? $pricing['from'][0] * $pricing['unit_price'][0] : "";
    
        $detailedProduct = [
                'name' => $parent->name,
                'brand' => $brand ? $brand->name : "",
                'unit' => $parent->unit,
                'description' => $parent->description,
                'main_photos' => $lastItem['storedFilePaths'] ?? $storedFilePaths, // Add stored file paths to the detailed product data
                'quantity' => $lastItem['variant_pricing-from']['from'][0] ?? $pricing['from'][0] ?? '',
                'price' => $lastItem['variant_pricing-from']['unit_price'][0] ?? $pricing['unit_price'][0] ?? '',
                'total' => isset($lastItem['variant_pricing-from']['from'][0]) && isset($lastItem['variant_pricing-from']['unit_price'][0]) ? $lastItem['variant_pricing-from']['from'][0] * $lastItem['variant_pricing-from']['unit_price'][0] : $total,
    
                'general_attributes' =>$attributesGeneralArray,
                'attributes' =>$attributes ?? [] ,
                'from' =>$pricing['from'] ?? [] ,
                'to' =>$pricing['to']  ?? [],
                'unit_price' =>$pricing['unit_price'] ?? [] ,
                'variations' =>$variations,
                'variationId' => $variationId ?? null,
                'lastItem' => $lastItem ?? [],
                'catalog' => true,
                'product_id' => $parent->id
            ];

        $previewData['detailedProduct'] = $detailedProduct;

        session(['productPreviewData' => $previewData]);

        return view('frontend.product_details.preview', compact('previewData'));
    }

    public function add_product(Request $request){
        $existingProduct = Product::find($request->id);

        if (!$existingProduct) {
            // Handle the case where the product with the specific ID doesn't exist
            return redirect()->back()->with('error', 'Product not found');
        }

        // Create a new instance of the Product model
        $newProduct = new Product;

        // Copy the attributes from the existing product to the new instance
        $newProduct->fill($existingProduct->toArray());

        // Make necessary updates to the attributes (if any)
        $newProduct->id = null; // Set id to null to let the database auto-increment the id
        $newProduct->user_id = Auth::user()->id;

        // Save the new product instance to the database
        $newProduct->save();

        $path = public_path('/upload_products/Product-'.$request->id);
        $destinationFolder = public_path('/upload_products/Product-'.$newProduct->id);
        if (!File::isDirectory($destinationFolder)) {
            File::makeDirectory($destinationFolder);
        }
        
        if (File::isDirectory($path)) {
            // $files = File::allFiles($path);

            // foreach ($files as $file) {
            //     $fileName = $file->getFilename(); // Get the file name
            //     File::copy($file, $destinationFolder.'/'.$fileName);
            // }

            File::copyDirectory($path, $destinationFolder);
        }
        if(count($existingProduct->getChildrenProducts()) > 0){
            foreach($existingProduct->getChildrenProducts() as $children){
                $newProductChildren = new Product;

                // Copy the attributes from the existing product to the new instance
                $newProductChildren->fill($children->toArray());
        
                // Make necessary updates to the attributes (if any)
                $newProductChildren->id = null; // Set id to null to let the database auto-increment the id
                $newProductChildren->user_id = Auth::user()->id;
                $newProductChildren->parent_id = $newProduct->id; 
        
                // Save the new product instance to the database
                $newProductChildren->save();
            }
        }
        return response()->json($request->all(), 200);
    }
}
