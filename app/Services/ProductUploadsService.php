<?php

namespace App\Services;

use App\Models\UploadProducts;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Auth;


class ProductUploadsService
{
    public function store_uploads(array $data, $update)
    {
        $collection = collect($data);

        //check if upload_products folder is existe, if not existe create it
        $structure = public_path('upload_products');
        if (!file_exists($structure)) {
            mkdir(public_path('upload_products', 0755));
        }

        //check if product folder with id of product is existe, if not existe create it with documents folder
        if(!file_exists(public_path('/upload_products/Product-'.$collection['product']->id))){
            mkdir(public_path('/upload_products/Product-'.$collection['product']->id, 0755));
            mkdir(public_path('/upload_products/Product-'.$collection['product']->id.'/documents', 0755));
        }else{
            if(!file_exists(public_path('/upload_products/Product-'.$collection['product']->id.'/documents'))){
                mkdir(public_path('/upload_products/Product-'.$collection['product']->id.'/documents', 0755));
            }
        }

        //insert paths of documents in DB and upload documents in folder under public/upload_products/Product{id_porduct}/document
        if(($data['document_names'] != null) && ($data['documents'] != null)){
            if(count($data['document_names']) == count($data['documents'])){
                foreach($data['documents'] as $key => $document){
                    if($document != null){
                        $documen_name = time().rand(5, 15).'.'.$document->getClientOriginalExtension();
                        $document->move(public_path('/upload_products/Product-'.$collection['product']->id.'/documents') , $documen_name);
                        $path = '/upload_products/Product-'.$collection['product']->id.'/documents'.'/'.$documen_name;
        
                        $uploaded_document = new UploadProducts();
                        $uploaded_document->id_product = $collection['product']->id;
                        $uploaded_document->path = $path;
                        $uploaded_document->extension = $document->getClientOriginalExtension();
                        $uploaded_document->document_name = $data['document_names'][$key];
                        $uploaded_document->type = 'documents';
                        $uploaded_document->save();
                        if(($collection['product']->is_draft == 0) && ($update == true)){
                            DB::table('revisions')->insert([
                                "revisionable_type" => "App\Models\UploadProducts",
                                "revisionable_id" => $uploaded_document->id,
                                "user_id" => Auth::user()->id,
                                "key" => 'add_document',
                                "old_value" => NULL,
                                "new_value" => $uploaded_document->id,
                                'created_at'            => new \DateTime(),
                                'updated_at'            => new \DateTime(),
                            ]);
                        }
                    } 
                }
            }
        }

        //Update old document
        if(array_key_exists('old_document_names', $data) || (array_key_exists('old_documents', $data))){
            if($data['old_document_names'] != null){
                foreach($data['old_document_names'] as $key => $document){
                    $uploaded_document = UploadProducts::find($key);
                    $historique = [
                        "old" => ['old_path' => $uploaded_document->path, 'old_document_name' => $uploaded_document->document_name],
                        "new" => []
                    ];
                    if($uploaded_document != null){
                        if($data['old_documents'] != null){
                            if(array_key_exists($key, $data['old_documents'])){
                                // if(file_exists(public_path($uploaded_document->path))){
                                //     unlink(public_path($uploaded_document->path));
                                // }
                                $new_document = $data['old_documents'][$key];
                                $documen_name = time().rand(5, 15).'.'.$new_document->getClientOriginalExtension();
                                $new_document->move(public_path('/upload_products/Product-'.$collection['product']->id.'/documents') , $documen_name);
                                $path = '/upload_products/Product-'.$collection['product']->id.'/documents'.'/'.$documen_name;
    
                                $uploaded_document->path = $path;
                                $uploaded_document->extension = $new_document->getClientOriginalExtension();

                                $historique["new"]["new_path"] = $path;
                            }
                        }

                        if($uploaded_document->document_name != $document){
                            $historique["new"]["new_document_name"] = $document;
                        }
                        
                        $uploaded_document->document_name = $document;
                        $uploaded_document->save();

                        
                        
                        if(count($historique['new']) > 0){
                            DB::table('revisions')->insert([
                                "revisionable_type" => "App\Models\UploadProducts",
                                "revisionable_id" => $uploaded_document->id,
                                "user_id" => Auth::user()->id,
                                "key" => 'update_document',
                                "old_value" => json_encode($historique["old"]),
                                "new_value" => json_encode($historique["new"]),
                                'created_at'            => new \DateTime(),
                                'updated_at'            => new \DateTime(),
                            ]);
                        }
                    }   
                }
            }else{
                if($data['old_documents'] != null){
                    foreach($data['old_documents'] as $key => $document){
                        $uploaded_document = UploadProducts::find($key);
                        if($uploaded_document != null){
                            $historique = [
                                "old" => ['old_path' => $uploaded_document->path, 'old_document_name' => $uploaded_document->name],
                                "new" => []
                            ];
                            // if(file_exists(public_path($uploaded_document->path))){
                            //     unlink(public_path($uploaded_document->path));
                            // }
                            $documen_name = time().rand(5, 15).'.'.$document->getClientOriginalExtension();
                            $new_document->move(public_path('/upload_products/Product-'.$collection['product']->id.'/documents') , $documen_name);
                            $path = '/upload_products/Product-'.$collection['product']->id.'/documents'.'/'.$documen_name;

                            $uploaded_document->path = $path;
                            $uploaded_document->extension = $document->getClientOriginalExtension();

                            $historique["new"]["new_path"] = $path;
                                
                            if($data['old_document_names'] != null){
                                if(array_key_exists($key, $data['old_document_names'])){
                                    $uploaded_document->document_name = $data['old_document_names'][$key];
                                    $historique["new"]["new_document_name"] = $document;
                                }
                            }
                            $uploaded_document->save();

                            DB::table('revisions')->insert([
                                "revisionable_type" => "App\Models\UploadProducts",
                                "revisionable_id" => $uploaded_document->id,
                                "user_id" => Auth::user()->id,
                                "key" => 'update_document',
                                "old_value" => json_encode($historique["old"]),
                                "new_value" => json_encode($historique["new"]),
                                'created_at'            => new \DateTime(),
                                'updated_at'            => new \DateTime(),
                            ]);
                        }   
                    }
                }
            }
        }
        
        //check if images folder is existe, if not existe create it under under public/upload_products/Product{id_porduct}/images
        if(!file_exists(public_path('/upload_products/Product-'.$collection['product']->id.'/images'))){
            mkdir(public_path('/upload_products/Product-'.$collection['product']->id.'/images', 0755));
        }

        //check if thumbnails folder is existe, if not existe create it under under public/upload_products/Product{id_porduct}/thumbnails
        if(!file_exists(public_path('/upload_products/Product-'.$collection['product']->id.'/thumbnails'))){
            mkdir(public_path('/upload_products/Product-'.$collection['product']->id.'/thumbnails', 0755));
        }

        //insert paths of images in DB and upload images in folder under public/upload_products/Product{id_porduct}/images
        if($data['main_photos'] != null){
            if(count($data['main_photos']) > 0){
                foreach($data['main_photos'] as $key => $image){
                    $imageName = time().rand(5, 15).'.jpg';
                    $extension = $image->getClientOriginalExtension();

                    if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png') {
                    
                        $path = '/upload_products/Product-'.$collection['product']->id.'/images'.'/'.$imageName;
        
                        //check if vendor not uploaded thumbnails, the system will create a thumbnail from a gallery image
                        if($data['photosThumbnail'] == null){
                            //$this->createThumbnail(public_path($path), $imageName,$collection['product']->id);
                            $img3 = Image::make($image);
                            $img3->resize(300, 300);
                            $path_thumbnail = '/upload_products/Product-'.$collection['product']->id.'/thumbnails'.'/'.$imageName;
                            $path_to_save = public_path('/upload_products/Product-'.$collection['product']->id.'/thumbnails'.'/'.$imageName);
                            $img3->save($path_to_save);
        
                            $uploaded_document = new UploadProducts();
                            $uploaded_document->id_product = $collection['product']->id;
                            $uploaded_document->path = $path_thumbnail;
                            $uploaded_document->extension = 'jpg';
                            $uploaded_document->type = 'thumbnails';
                            $uploaded_document->save();

                            if(($collection['product']->is_draft == 0) && ($update == true)){
                                DB::table('revisions')->insert([
                                    "revisionable_type" => "App\Models\UploadProducts",
                                    "revisionable_id" => $uploaded_document->id,
                                    "user_id" => Auth::user()->id,
                                    "key" => 'add_image',
                                    "old_value" => NULL,
                                    "new_value" => $uploaded_document->id,
                                    'created_at'            => new \DateTime(),
                                    'updated_at'            => new \DateTime(),
                                ]);
                            }
                        }

                        $image->move(public_path('/upload_products/Product-'.$collection['product']->id.'/images') , $imageName);
        
                        $uploaded_document = new UploadProducts();
                        $uploaded_document->id_product = $collection['product']->id;
                        $uploaded_document->path = $path;
                        $uploaded_document->extension = 'jpg';
                        $uploaded_document->type = 'images';
                        $uploaded_document->save();

                        if(($collection['product']->is_draft == 0) && ($update == true)){
                            DB::table('revisions')->insert([
                                "revisionable_type" => "App\Models\UploadProducts",
                                "revisionable_id" => $uploaded_document->id,
                                "user_id" => Auth::user()->id,
                                "key" => 'add_image',
                                "old_value" => NULL,
                                "new_value" => $uploaded_document->id,
                                'created_at'            => new \DateTime(),
                                'updated_at'            => new \DateTime(),
                            ]);
                        }
                    }
                }
            }
        }
        

        //insert paths of thumbnails in DB and upload thumbnails in folder under public/upload_products/Product{id_porduct}/thumbnails
        if($data['photosThumbnail'] != null){
            foreach($data['photosThumbnail'] as $key => $image){
                $imageName = time().rand(5, 15).'.jpg';
                // $image->move(public_path('/upload_products/Product-'.$collection['product']->id.'/thumbnails') , $imageName);
                // $path = '/upload_products/Product-'.$collection['product']->id.'/thumbnails'.'/'.$imageName;
                $extension = $image->getClientOriginalExtension();

                if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png') {
                    $img3 = Image::make($image);
                    $img3->resize(300, 300);
                    $path_thumbnail = '/upload_products/Product-'.$collection['product']->id.'/thumbnails'.'/'.$imageName;
                    $path_to_save = public_path('/upload_products/Product-'.$collection['product']->id.'/thumbnails'.'/'.$imageName);
                    $img3->save($path_to_save);

                    $uploaded_document = new UploadProducts();
                    $uploaded_document->id_product = $collection['product']->id;
                    $uploaded_document->path = $path_thumbnail;
                    $uploaded_document->extension = 'jpg';
                    $uploaded_document->type = 'thumbnails';
                    $uploaded_document->save();

                    if(($collection['product']->is_draft == 0) && ($update == true)){
                        DB::table('revisions')->insert([
                            "revisionable_type" => "App\Models\UploadProducts",
                            "revisionable_id" => $uploaded_document->id,
                            "user_id" => Auth::user()->id,
                            "key" => 'add_image',
                            "old_value" => NULL,
                            "new_value" => $uploaded_document->id,
                            'created_at'            => new \DateTime(),
                            'updated_at'            => new \DateTime(),
                        ]);
                    }
                }
            }
        }
    }
}
