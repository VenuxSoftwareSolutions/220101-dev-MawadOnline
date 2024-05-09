<?php

namespace App\Http\Controllers\Seller;

use Auth;
use App\Models\Tour;
use App\Models\Brand;
use App\Models\Unity;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductCatalog;
use App\Models\UploadProducts;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\PricingConfiguration;
use App\Models\UploadProductCatalog;
use Illuminate\Support\Facades\File;
use App\Models\ProductAttributeValues;
use App\Models\ProductAttributeValueCatalog;

class CatalogController extends Controller
{
    public function search(){
        $tour_steps=Tour::orderBy('step_number')->get();
        $catalogs = ProductCatalog::orderBy('created_at', 'desc')->paginate(12);
        //$paginationView = $catalogs->onEachSide(1)->render('seller.product.catalog.pagination', ['elements' => $catalogs->elements()]);

        return view('seller.product.catalog.search',compact('tour_steps', 'catalogs'));
    }

    public function search_action(Request $request){
        $products = [];
        $catalogs = [];
        $searchTerm = $request->name;

        if(Auth::user()->user_type == "seller"){
            
            $catalogs = ProductCatalog::where(function ($query) use ($searchTerm) {
                                    $query->where('name', 'like', "%{$searchTerm}%")
                                        ->orWhereHas('brand', function ($query) use ($searchTerm) {
                                            $query->where('name', 'like', "%{$searchTerm}%");
                                        });
                                })->take(3)->get();
            return view('seller.product.catalog.old_result')->with(['products' =>  $products, 'catalogs' => $catalogs, 'search' => $request->name]);
        }elseif((Auth::user()->user_type == "admin") || (Auth::user()->user_type == "staff")){
            $products = Product::where(function ($query) use ($searchTerm) {
                                    $query->where('name', 'like', "%{$searchTerm}%")
                                        ->orWhereHas('brand', function ($query) use ($searchTerm) {
                                            $query->where('name', 'like', "%{$searchTerm}%");
                                        });
                                })->where(function ($query) {
                                    $query->where('catalog', 1)
                                        ->orWhere(function ($query) {
                                            $query->where('catalog', 0)
                                                    ->where('approved', 1);
                                        });
                                })->take(1)->get();

            if(count($products) == 0){
                $catalogs = ProductCatalog::where(function ($query) use ($searchTerm) {
                    $query->where('name', 'like', "%{$searchTerm}%")
                        ->orWhereHas('brand', function ($query) use ($searchTerm) {
                            $query->where('name', 'like', "%{$searchTerm}%");
                        });
                })->take(3)->get();
            }else{
                $catalogs = ProductCatalog::where(function ($query) use ($searchTerm) {
                    $query->where('name', 'like', "%{$searchTerm}%")
                        ->orWhereHas('brand', function ($query) use ($searchTerm) {
                            $query->where('name', 'like', "%{$searchTerm}%");
                        });
                })->take(2)->get();
            }

            //return response()->json($products, 200);

            return view('backend.product.catalog.result')->with(['products' =>  $products, 'catalogs' => $catalogs, 'search' => $request->name]);
        }


    }

    public function new_search_action(Request $request){
        $searchTerm = $request->name;

        if($searchTerm == ""){
            $catalogs = ProductCatalog::orderBy('created_at', 'desc')->paginate(12);
        }else{
            $catalogs = ProductCatalog::where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', "%{$searchTerm}%")
                    ->orWhereHas('brand', function ($query) use ($searchTerm) {
                        $query->where('name', 'like', "%{$searchTerm}%");
                    });
            })->orderBy('created_at', 'desc')->paginate(12);
        }

        return view('seller.product.catalog.result')->with(['catalogs' => $catalogs]);
    }

    public function see_all($search){
        if (!is_null($search)) {
            $products = [];
            $catalogs = [];
            if(Auth::user()->user_type == "seller"){
                $catalogs = ProductCatalog::where(function ($query) use ($search) {
                                        $query->where('name', 'like', "%{$search}%")
                                            ->orWhereHas('brand', function ($query) use ($search) {
                                                $query->where('name', 'like', "%{$search}%");
                                            });
                                    })->get();

                return view('seller.product.catalog.see_all',[
                    'products' => $products,
                    'catalogs' => $catalogs,
                ]);
            }elseif((Auth::user()->user_type == "admin") || (Auth::user()->user_type == "staff")){
                $products = Product::where(function ($query) use ($search) {
                                        $query->where('name', 'like', "%{$search}%")
                                            ->orWhereHas('brand', function ($query) use ($search) {
                                                $query->where('name', 'like', "%{$search}%");
                                            });
                                    })->where(function ($query) {
                                        $query->where('catalog', 1)
                                            ->orWhere(function ($query) {
                                                $query->where('catalog', 0)
                                                        ->where('approved', 1);
                                            });
                                    })->get();

                $catalogs = ProductCatalog::where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhereHas('brand', function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%");
                        });
                })->get();

                return view('backend.product.catalog.see_all',[
                    'products' => $products,
                    'catalogs' => $catalogs,
                ]);
            }
        }else{
            abort(404);
        }
    }

    public function getYoutubeVideoId($videoLink) {
        // Parse the YouTube video URL to extract the video ID
        $videoId = '';
        parse_str(parse_url($videoLink, PHP_URL_QUERY), $queryParams);
        if (isset($queryParams['v'])) {
            $videoId = $queryParams['v'];
        }
        return $videoId;
    }

    public function getVimeoVideoId($videoLink) {
        // Parse the Vimeo video URL to extract the video ID
        $videoId = '';
        $regex = '/(?:https?:\/\/)?(?:www\.)?(?:vimeo\.com)\/?(.+)/';
        if (preg_match($regex, $videoLink, $matches)) {
            $videoId = $matches[1];
        }
        return $videoId;
    }

    public function displayPreviewProductInCatalogProduct($id, $is_catalog){
        $is_added_to_catalog = false;
        if($is_catalog == 2){
            $parent = Product::find($id);
            $product_catalog = ProductCatalog::where('product_id', $id)->first();
            // Check if product is existe in catalog or not
            if($product_catalog != null){
                $is_added_to_catalog = true;
            }
        }else{
            $parent = ProductCatalog::find($id);
        }


        if($parent->is_parent == 0){
            if($parent->parent_id != 0){
                if($is_catalog == 2){
                    $parent = Product::find($parent->parent_id);
                }else{
                    $parent = ProductCatalog::find($parent->parent_id);
                }
            }
        }

        $brand = Brand::find($parent->brand_id);
        if($is_catalog == 2){
            $pricing = PricingConfiguration::where('id_products', $parent->id)->get();
            $pricing = [];
            $pricing['from'] = PricingConfiguration::where('id_products', $parent->id)->pluck('from')->toArray();
            $pricing['to'] = PricingConfiguration::where('id_products', $parent->id)->pluck('to')->toArray();
            $pricing['unit_price'] = PricingConfiguration::where('id_products', $parent->id)->pluck('unit_price')->toArray();
            $pricing['discount'] =[
                'type' => null,
                'amount' => null,
                'percentage' => null,
                'date' => null,
            ];
        }else{
            $pricing = [];
            $pricing['from'] = [0];
            $pricing['to'] = [0];
            $pricing['unit_price'] = [0];
            $pricing['discount'] =[
                'type' => null,
                'amount' => null,
                'percentage' => null,
                'date' => null,
            ];
        }

        $variations = [];
        $pricing_children = [];
        if($parent->is_parent == 1){

            if($is_catalog == 2){
                $childrens_ids = Product::where('parent_id', $parent->id)->pluck('id')->toArray();
            }else{
                $childrens_ids = ProductCatalog::where('parent_id', $parent->id)->pluck('id')->toArray();
            }

            foreach($childrens_ids as $children_id){
                if($is_catalog == 2){
                    $variations[$children_id]['variant_pricing-from']['from'] = PricingConfiguration::where('id_products', $children_id)->pluck('from')->toArray();
                    $variations[$children_id]['variant_pricing-from']['to'] = PricingConfiguration::where('id_products', $children_id)->pluck('to')->toArray();
                    $variations[$children_id]['variant_pricing-from']['unit_price'] = PricingConfiguration::where('id_products', $children_id)->pluck('unit_price')->toArray();
                    $variations[$children_id]['variant_pricing-from']['discount'] =[
                        'type' => null,
                        'amount' => null,
                        'percentage' => null,
                        'date' => null,
                    ];
                }else{
                    $variations[$children_id]['variant_pricing-from']['from'] = [0];
                    $variations[$children_id]['variant_pricing-from']['to'] = [0];
                    $variations[$children_id]['variant_pricing-from']['unit_price'] = [0];
                    $variations[$children_id]['variant_pricing-from']['discount'] =[
                        'type' => null,
                        'amount' => null,
                        'percentage' => null,
                        'date' => null,
                    ];
                }

                if($is_catalog == 2){
                    $attributes_variant = ProductAttributeValues::where('id_products', $children_id)->where('is_variant', 1)->get();
                }else{
                    $attributes_variant = ProductAttributeValueCatalog::where('catalog_id', $children_id)->where('is_variant', 1)->get();
                }

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
                if($is_catalog == 2){
                    $variations[$children_id]['storedFilePaths'] = UploadProducts::where('id_product', $children_id)->where('type', 'images')->pluck('path')->toArray();
                }else{
                    $variations[$children_id]['storedFilePaths'] = UploadProductCatalog::where('catalog_id', $children_id)->where('type', 'images')->pluck('path')->toArray();
                }
            }
        }

        if($is_catalog == 2){
            $storedFilePaths = UploadProducts::where('id_product', $parent->id)->where('type', 'images')->pluck('path')->toArray();
        }else{
            $storedFilePaths = UploadProductCatalog::where('catalog_id', $parent->id)->where('type', 'images')->pluck('path')->toArray();
        }
        if($is_catalog == 2){
            $attributes_general = ProductAttributeValues::where('id_products', $parent->id)->where('is_general', 1)->get();
        }else{
            $attributes_general = ProductAttributeValueCatalog::where('catalog_id', $parent->id)->where('is_general', 1)->get();
        }
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
            if(count($lastItem['variant_pricing-from']['to']) >0){
                $max =max($lastItem['variant_pricing-from']['to']) ;
            }
            if(count($lastItem['variant_pricing-from']['from']) >0){
                $min =min($lastItem['variant_pricing-from']['from']) ;
            }

        }

        if (isset($pricing['from']) && is_array($pricing['from']) && count($pricing['from']) > 0) {
            if(!isset($min))
                $min = min($pricing['from']) ;
        }

        if (isset($pricing['to']) && is_array($pricing['to']) && count($pricing['to']) > 0) {
            if(!isset($max))
                $max = max($pricing['to']) ;
        }

        if ($parent->video_provider === "youtube") {
            $getYoutubeVideoId=$this->getYoutubeVideoId($parent->video_link) ;
        }
        else {
            $getVimeoVideoId=$this->getVimeoVideoId($parent->video_link) ;
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
                'product_id' => $parent->id,
                'max' =>$max ?? 1 ,
                'min' =>$min ?? 1 ,
                'video_provider'  => $parent->video_provider,
                'getYoutubeVideoId' =>$getYoutubeVideoId ?? null ,
                'getVimeoVideoId' => $getVimeoVideoId ?? null,
                'is_catalog' => $is_catalog,
                'is_added_to_catalog' => $is_added_to_catalog,
                'discountedPrice' => $discountedPrice ?? null,
                'totalDiscount' => $totalDiscount ?? null,
                'date_range_pricing' =>  $pricing['discount']['date']  ?? null,
                'discount_type' => $pricing['discount']['type'] ?? null ,
                'discount_percentage' => $pricing['discount']['percentage'],
                'discount_amount'=> $pricing['discount']['amount'],
                'percent'=> $percent ?? null,
            ];

        $previewData['detailedProduct'] = $detailedProduct;

        session(['productPreviewData' => $previewData]);

        return view('frontend.product_details.preview', compact('previewData'));
    }

    public function add_product(Request $request){
        $existingProduct = ProductCatalog::find($request->id);

        if (!$existingProduct) {
            // Handle the case where the product with the specific ID doesn't exist
            return redirect()->back()->with('error', 'Product not found');
        }

        $data = $existingProduct->attributesToArray();
        // Make necessary updates to the attributes (if any)
        unset($data['id']);
        unset($data['product_id']);
        $data['added_from_catalog'] = 1;
        $data['product_added_from_catalog'] = 1;
        $data['user_id'] = Auth::user()->id;
        $newProduct = Product::insertGetId($data);

        DB::table('product_categories')->insert([
            'product_id' => $newProduct,
            'category_id' => $existingProduct->category_id
        ]);

        $path = public_path('/upload_products_catalog/Product-'.$request->id);
        $destinationFolder = public_path('/upload_products/Product-'.$newProduct);
        if (!File::isDirectory($destinationFolder)) {
            File::makeDirectory($destinationFolder);
        }

        if (File::isDirectory($path)) {
            File::copyDirectory($path, $destinationFolder);
        }

        $uploads = UploadProductCatalog::where('catalog_id', $request->id)->get();
        $new_records = [];
        if(count($uploads) > 0){
            foreach($uploads as $file){
                $current_file = [];
                $newPath = str_replace("/upload_products_catalog/Product-{$request->id}", "/upload_products/Product-{$newProduct}", $file->path);

                $current_file['id_product'] = $newProduct;
                $current_file['path'] = $newPath;
                $current_file['extension'] = $file->extension;
                $current_file['document_name'] = $file->document_name;
                $current_file['type'] = $file->type;

                array_push($new_records, $current_file);
            }

            if(count($new_records) > 0){
                UploadProducts::insert($new_records);
            }
        }

        $attributes = ProductAttributeValueCatalog::where('catalog_id', $request->id)->get();

        $new_records_attributes = [];

        if(count($attributes) > 0){
            foreach($attributes as $attribute){
                $current_attribute = [];
                $current_attribute['id_products'] = $newProduct;
                $current_attribute['id_attribute'] = $attribute->id_attribute;
                $current_attribute['id_units'] = $attribute->id_units;
                $current_attribute['id_values'] = $attribute->id_values;
                $current_attribute['id_colors'] = $attribute->id_colors;
                $current_attribute['value'] = $attribute->value;
                $current_attribute['is_variant'] = $attribute->is_variant;
                $current_attribute['is_general'] = $attribute->is_general;

                array_push($new_records_attributes, $current_attribute);
            }

            if(count($new_records_attributes) > 0){
                ProductAttributeValues::insert($new_records_attributes);
            }
        }

        if(count($existingProduct->getChildrenProducts()) > 0){
            foreach($existingProduct->getChildrenProducts() as $children){
                $data = $children->attributesToArray();
                unset($data['id']);
                $data['parent_id'] = $newProduct;
                $data['user_id'] = Auth::user()->id;
                unset($data['product_id']);
                $data['added_from_catalog'] = 1;
                $data['product_added_from_catalog'] = 1;
                $newProductChildren = Product::insertGetId($data);

                DB::table('product_categories')->insert([
                    'product_id' => $newProductChildren,
                    'category_id' => $children->category_id
                ]);

                $path = public_path('/upload_products_catalog/Product-'.$children->id);
                $destinationFolder = public_path('/upload_products/Product-'.$newProductChildren);
                if (!File::isDirectory($destinationFolder)) {
                    File::makeDirectory($destinationFolder);
                }

                if (File::isDirectory($path)) {
                    File::copyDirectory($path, $destinationFolder);
                }

                $uploads = UploadProductCatalog::where('catalog_id', $children->id)->get();
                $new_records = [];
                if(count($uploads) > 0){
                    foreach($uploads as $file){
                        $current_file = [];
                        $newPath = str_replace("/upload_products_catalog/Product-{$children->id}", "/upload_products/Product-{$newProductChildren}", $file->path);

                        $current_file['id_product'] = $newProductChildren;
                        $current_file['path'] = $newPath;
                        $current_file['extension'] = $file->extension;
                        $current_file['document_name'] = $file->document_name;
                        $current_file['type'] = $file->type;

                        array_push($new_records, $current_file);
                    }

                    if(count($new_records) > 0){
                        UploadProducts::insert($new_records);
                    }
                }

                $attributes = ProductAttributeValueCatalog::where('catalog_id', $children->id)->get();
                $new_records_attributes = [];

                if(count($attributes) > 0){
                    foreach($attributes as $attribute){
                        $current_attribute = [];
                        $current_attribute['id_products'] = $newProductChildren;
                        $current_attribute['id_attribute'] = $attribute->id_attribute;
                        $current_attribute['id_units'] = $attribute->id_units;
                        $current_attribute['id_values'] = $attribute->id_values;
                        $current_attribute['id_colors'] = $attribute->id_colors;
                        $current_attribute['value'] = $attribute->value;
                        $current_attribute['is_variant'] = $attribute->is_variant;
                        $current_attribute['is_general'] = $attribute->is_general;

                        array_push($new_records_attributes, $current_attribute);
                    }

                    if(count($new_records_attributes) > 0){
                        ProductAttributeValues::insert($new_records_attributes);
                    }
                }
            }
        }
        return response()->json([
            'data' => $newProduct
        ]);
    }

    public function add_product_to_catalog(Request $request){

        $existingProduct = Product::find($request->id);

        if (!$existingProduct) {
            // Handle the case where the product with the specific ID doesn't exist
            return redirect()->back()->with('error', 'Product not found');
        }

        $data = $existingProduct->attributesToArray();
        // Make necessary updates to the attributes (if any)
        unset($data['id']);
        $data['product_id'] = $request->id;
        $newProduct = ProductCatalog::insertGetId($data);

        $path = public_path('/upload_products/Product-'.$request->id);
        $destinationFolder = public_path('/upload_products_catalog/Product-'.$newProduct);
        if (!File::isDirectory($destinationFolder)) {
            File::makeDirectory($destinationFolder);
        }

        if (File::isDirectory($path)) {
            File::copyDirectory($path, $destinationFolder);
        }

        $uploads = UploadProducts::where('id_product', $request->id)->get();
        $new_records = [];
        if(count($uploads) > 0){
            foreach($uploads as $file){
                $current_file = [];
                $newPath = str_replace("/upload_products/Product-{$request->id}", "/upload_products_catalog/Product-{$newProduct}", $file->path);

                $current_file['catalog_id'] = $newProduct;
                $current_file['path'] = $newPath;
                $current_file['extension'] = $file->extension;
                $current_file['document_name'] = $file->document_name;
                $current_file['type'] = $file->type;

                array_push($new_records, $current_file);
            }

            if(count($new_records) > 0){
                UploadProductCatalog::insert($new_records);
            }
        }

        $attributes = ProductAttributeValues::where('id_products', $request->id)->get();

        $new_records_attributes = [];

        if(count($attributes) > 0){
            foreach($attributes as $attribute){
                $current_attribute = [];
                $current_attribute['catalog_id'] = $newProduct;
                $current_attribute['id_attribute'] = $attribute->id_attribute;
                $current_attribute['id_units'] = $attribute->id_units;
                $current_attribute['id_values'] = $attribute->id_values;
                $current_attribute['id_colors'] = $attribute->id_colors;
                $current_attribute['value'] = $attribute->value;
                $current_attribute['is_variant'] = $attribute->is_variant;
                $current_attribute['is_general'] = $attribute->is_general;

                array_push($new_records_attributes, $current_attribute);
            }

            if(count($new_records_attributes) > 0){
                ProductAttributeValueCatalog::insert($new_records_attributes);
            }
        }

        if(count($existingProduct->getChildrenProducts()) > 0){
            foreach($existingProduct->getChildrenProducts() as $children){
                $data = $children->attributesToArray();
                // Make necessary updates to the attributes (if any)
                unset($data['id']);
                $data['parent_id'] = $newProduct;
                $data['product_id'] = $children->id;
                $newProductChildren = ProductCatalog::insertGetId($data);

                $path = public_path('/upload_products/Product-'.$children->id);
                $destinationFolder = public_path('/upload_products_catalog/Product-'.$newProductChildren);
                if (!File::isDirectory($destinationFolder)) {
                    File::makeDirectory($destinationFolder);
                }

                if (File::isDirectory($path)) {
                    File::copyDirectory($path, $destinationFolder);
                }

                $uploads = UploadProducts::where('id_product', $children->id)->get();
                $new_records = [];
                if(count($uploads) > 0){
                    foreach($uploads as $file){
                        $current_file = [];
                        $newPath = str_replace("/upload_products/Product-{$children->id}", "/upload_products_catalog/Product-{$newProductChildren}", $file->path);

                        $current_file['catalog_id'] = $newProductChildren;
                        $current_file['path'] = $newPath;
                        $current_file['extension'] = $file->extension;
                        $current_file['document_name'] = $file->document_name;
                        $current_file['type'] = $file->type;

                        array_push($new_records, $current_file);
                    }

                    if(count($new_records) > 0){
                        UploadProductCatalog::insert($new_records);
                    }
                }

                $attributes = ProductAttributeValues::where('id_products', $children->id)->get();
                $new_records_attributes = [];

                if(count($attributes) > 0){
                    foreach($attributes as $attribute){
                        $current_attribute = [];
                        $current_attribute['catalog_id'] = $newProductChildren;
                        $current_attribute['id_attribute'] = $attribute->id_attribute;
                        $current_attribute['id_units'] = $attribute->id_units;
                        $current_attribute['id_values'] = $attribute->id_values;
                        $current_attribute['id_colors'] = $attribute->id_colors;
                        $current_attribute['value'] = $attribute->value;
                        $current_attribute['is_variant'] = $attribute->is_variant;
                        $current_attribute['is_general'] = $attribute->is_general;

                        array_push($new_records_attributes, $current_attribute);
                    }

                    if(count($new_records_attributes) > 0){
                        ProductAttributeValueCatalog::insert($new_records_attributes);
                    }
                }
            }
        }
        return response()->json([
            'data' => $newProduct
        ]);
    }
}
