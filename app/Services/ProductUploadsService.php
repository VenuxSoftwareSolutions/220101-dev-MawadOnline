<?php

namespace App\Services;

use AizPackages\CombinationGenerate\Services\CombinationService;
use App\Models\UploadProducts;
use Illuminate\Support\Facades\DB;

class ProductUploadsService
{
    public function store_documents(array $data)
    {
        $collection = collect($data);

        $structure = public_path('upload_products');
        if (!file_exists($structure)) {
            mkdir(public_path('upload_products', 0777));
        }

        if(!file_exists(public_path('/upload_products/Product-'.$collection['product']->id))){
            mkdir(public_path('/upload_products/Product-'.$collection['product']->id, 0777));
            mkdir(public_path('/upload_products/Product-'.$collection['product']->id.'/documents', 0777));
        }else{
            if(!file_exists(public_path('/upload_products/Product-'.$collection['product']->id.'/documents'))){
                mkdir(public_path('/upload_products/Product-'.$collection['product']->id.'/documents', 0777));
            }
        }
        if(count($data['document_names']) == count($data['documents'])){
            foreach($data['documents'] as $document){
                $image = $document;
                $imageName = time().rand(5, 15).'.'.$image->getClientOriginalExtension();
                $image->move(public_path('/upload_products/Product-'.$collection['product']->id.'/documents') , $imageName);
                $path = '/upload_products/Product-'.$collection['product']->id.'/documents'.'/'.$imageName;
            }
        }

        dd($collection);
    }
}
