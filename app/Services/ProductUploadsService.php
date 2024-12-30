<?php

namespace App\Services;

use App\Models\Product;
use App\Models\UploadProducts;
use Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Intervention\Image\ImageManagerStatic as ImageManager;
use Illuminate\Support\Facades\Storage;

class ProductUploadsService
{
    public function store_uploads(array $data, $update)
    {
        $collection = collect($data);
        $parent = Product::find($collection['product']->id);
        $history = DB::table('revisions')
            ->whereNull('deleted_at')
            ->where('revisionable_id', $collection['product']->id)
            ->where('revisionable_type', 'App\Models\Product')
            ->get();

        //check if upload_products folder is exist, if not exist create it
        $structure = public_path('upload_products');
        if (!file_exists($structure)) {
            mkdir(public_path('upload_products', 0755));
        }

        //check if product folder with id of product is exist, if not exist create it with documents folder
        if (!file_exists(public_path('/upload_products/Product-' . $collection['product']->id))) {
            mkdir(public_path('/upload_products/Product-' . $collection['product']->id, 0755));
            mkdir(public_path('/upload_products/Product-' . $collection['product']->id . '/documents', 0755));
        } else {
            if (!file_exists(public_path('/upload_products/Product-' . $collection['product']->id . '/documents'))) {
                mkdir(public_path('/upload_products/Product-' . $collection['product']->id . '/documents', 0755));
            }
        }

        //insert paths of documents in DB and upload documents in folder under public/upload_products/Product{id_porduct}/document
        if (($data['document_names'] != null) && ($data['documents'] != null)) {
            if (count($data['document_names']) == count($data['documents'])) {
                $check_added = false;
                foreach ($data['documents'] as $key => $document) {
                    if ($document != null) {
                        $document_name = time() . rand(5, 15) . '.' . $document->getClientOriginalExtension();
                        $document->move(public_path('/upload_products/Product-' . $collection['product']->id . '/documents'), $document_name);
                        $path = '/upload_products/Product-' . $collection['product']->id . '/documents' . '/' . $document_name;

                        $uploaded_document = new UploadProducts;
                        $uploaded_document->id_product = $collection['product']->id;
                        $uploaded_document->path = $path;
                        $uploaded_document->extension = $document->getClientOriginalExtension();
                        $uploaded_document->document_name = $data['document_names'][$key];
                        $uploaded_document->type = 'documents';
                        $uploaded_document->save();
                        if (($collection['product']->is_draft == 0) && ($update == true)) {
                            $check_added = true;
                            DB::table('revisions')->insert([
                                'revisionable_type' => "App\Models\UploadProducts",
                                'revisionable_id' => $uploaded_document->id,
                                'user_id' => Auth::user()->owner_id,
                                'key' => 'add_document',
                                'old_value' => null,
                                'new_value' => $uploaded_document->id,
                                'created_at' => new \DateTime,
                                'updated_at' => new \DateTime,
                            ]);
                        }
                    }
                }

                if (($parent->product_added_from_catalog == 1) && (count($history) == 0) && ($check_added == false)) {
                    $parent->approved = 1;
                    $parent->save();
                } else {
                    // Update the approved field in the parent product
                    $parent->update(['approved' => 0]);

                    // Update the approved field in the children related to the parent
                    $parent->children()->update(['approved' => 0]);
                }
            }
        }

        //Update old document
        if (array_key_exists('old_document_names', $data) || (array_key_exists('old_documents', $data))) {
            $ids_documents = [];
            if ($data['old_document_names'] != null) {
                foreach ($data['old_document_names'] as $key => $document) {
                    $uploaded_document = UploadProducts::find($key);
                    $history = [
                        'old' => ['old_path' => $uploaded_document->path, 'old_document_name' => $uploaded_document->document_name],
                        'new' => [],
                    ];
                    if ($uploaded_document != null) {
                        if ($data['old_documents'] != null) {
                            if (array_key_exists($key, $data['old_documents'])) {
                                $new_document = $data['old_documents'][$key];
                                $document_name = time() . rand(5, 15) . '.' . $new_document->getClientOriginalExtension();
                                $new_document->move(public_path('/upload_products/Product-' . $collection['product']->id . '/documents'), $document_name);
                                $path = '/upload_products/Product-' . $collection['product']->id . '/documents' . '/' . $document_name;

                                $uploaded_document->path = $path;
                                $uploaded_document->extension = $new_document->getClientOriginalExtension();

                                $history['new']['new_path'] = $path;
                            }
                        }

                        if ($uploaded_document->document_name != $document) {
                            $history['new']['new_document_name'] = $document;
                        }

                        $uploaded_document->document_name = $document;
                        $uploaded_document->save();

                        array_push($ids_documents, $uploaded_document->id);

                        if (count($history['new']) > 0) {
                            $check_added = true;
                            DB::table('revisions')->insert([
                                'revisionable_type' => "App\Models\UploadProducts",
                                'revisionable_id' => $uploaded_document->id,
                                'user_id' => Auth::user()->owner_id,
                                'key' => 'update_document',
                                'old_value' => json_encode($history['old']),
                                'new_value' => json_encode($history['new']),
                                'created_at' => new \DateTime,
                                'updated_at' => new \DateTime,
                            ]);
                        }
                    }
                }
            } else {
                if ($data['old_documents'] != null) {
                    foreach ($data['old_documents'] as $key => $document) {
                        $uploaded_document = UploadProducts::find($key);
                        if ($uploaded_document != null) {
                            $history = [
                                'old' => ['old_path' => $uploaded_document->path, 'old_document_name' => $uploaded_document->name],
                                'new' => [],
                            ];
                            // if(file_exists(public_path($uploaded_document->path))){
                            //     unlink(public_path($uploaded_document->path));
                            // }
                            $document_name = time() . rand(5, 15) . '.' . $document->getClientOriginalExtension();
                            $new_document->move(public_path('/upload_products/Product-' . $collection['product']->id . '/documents'), $document_name);
                            $path = '/upload_products/Product-' . $collection['product']->id . '/documents' . '/' . $document_name;

                            $uploaded_document->path = $path;
                            $uploaded_document->extension = $document->getClientOriginalExtension();

                            $history['new']['new_path'] = $path;

                            if ($data['old_document_names'] != null) {
                                if (array_key_exists($key, $data['old_document_names'])) {
                                    $uploaded_document->document_name = $data['old_document_names'][$key];
                                    $history['new']['new_document_name'] = $document;
                                }
                            }
                            $uploaded_document->save();

                            array_push($ids_documents, $uploaded_document->id);

                            $check_added = true;
                            DB::table('revisions')->insert([
                                'revisionable_type' => "App\Models\UploadProducts",
                                'revisionable_id' => $uploaded_document->id,
                                'user_id' => Auth::user()->owner_id,
                                'key' => 'update_document',
                                'old_value' => json_encode($history['old']),
                                'new_value' => json_encode($history['new']),
                                'created_at' => new \DateTime,
                                'updated_at' => new \DateTime,
                            ]);
                        }
                    }
                }
            }

            $history_documents = DB::table('revisions')->whereNull('deleted_at')->whereIn('revisionable_id', $ids_documents)->where('revisionable_type', 'App\Models\UploadProducts')->get();
            if (($parent->product_added_from_catalog == 1) && (count($history_documents) == 0) && (count($history) == 0)) {
                $parent->approved = 1;
                $parent->save();
            } else {
                // Update the approved field in the parent product
                $parent->update(['approved' => 0]);

                // Update the approved field in the children related to the parent
                $parent->children()->update(['approved' => 0]);
            }
        }

        //check if images folder is exist, if not exist create it under under public/upload_products/Product{id_porduct}/images
        if (!file_exists(public_path('/upload_products/Product-' . $collection['product']->id . '/images'))) {
            mkdir(public_path('/upload_products/Product-' . $collection['product']->id . '/images', 0755));
        }

        //check if thumbnails folder is exist, if not exist create it under under public/upload_products/Product{id_porduct}/thumbnails
        if (!file_exists(public_path('/upload_products/Product-' . $collection['product']->id . '/thumbnails'))) {
            mkdir(public_path('/upload_products/Product-' . $collection['product']->id . '/thumbnails', 0755));
        }

        //insert paths of images in DB and upload images in folder under public/upload_products/Product{id_porduct}/images
        if ($data['main_photos'] != null) {
            if (count($data['main_photos']) > 0) {
                $check_added = false;
                foreach ($data['main_photos'] as $key => $image) {                                    
                    $imageName = time() . rand(5, 15) . '.jpg';
                    $extension = $image->getClientOriginalExtension();
                    
                    if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png'])) {
                        
                        $processedImagePath = $this->resizeImageIfNeeded($image, 1200, 90);
                        $path = '/upload_products/Product-' . $collection['product']->id . '/images/' . $imageName;
                        $thumbnailPath = public_path($path);
                        copy($processedImagePath, $thumbnailPath);
        
                        //check if vendor not uploaded thumbnails, the system will create a thumbnail from a gallery image
                        if ($data['photosThumbnail'] == null) {
                            //$this->createThumbnail(public_path($path), $imageName,$collection['product']->id);
                            $img3 = Image::make($thumbnailPath);
                            //$img3->resize(300, 300);
                            $path_thumbnail = '/upload_products/Product-' . $collection['product']->id . '/thumbnails' . '/' . $imageName;
                            $path_to_save = public_path('/upload_products/Product-' . $collection['product']->id . '/thumbnails' . '/' . $imageName);
                            $img3->save($path_to_save);

                            $uploaded_document = new UploadProducts;
                            $uploaded_document->id_product = $collection['product']->id;
                            $uploaded_document->path = $path_thumbnail;
                            $uploaded_document->extension = 'jpg';
                            $uploaded_document->type = 'thumbnails';
                            $uploaded_document->save();

                            if (($collection['product']->is_draft == 0) && ($update == true)) {
                                $check_added = true;
                                DB::table('revisions')->insert([
                                    'revisionable_type' => "App\Models\UploadProducts",
                                    'revisionable_id' => $uploaded_document->id,
                                    'user_id' => Auth::user()->owner_id,
                                    'key' => 'add_image',
                                    'old_value' => null,
                                    'new_value' => $uploaded_document->id,
                                    'created_at' => new \DateTime,
                                    'updated_at' => new \DateTime,
                                ]);
                            }
                        }

                        $image->move(public_path('/upload_products/Product-' . $collection['product']->id . '/images'), $imageName);

                        $uploaded_document = new UploadProducts;
                        $uploaded_document->id_product = $collection['product']->id;
                        $uploaded_document->path = $path;
                        $uploaded_document->extension = 'jpg';
                        $uploaded_document->type = 'images';
                        $uploaded_document->save();

                        if (($collection['product']->is_draft == 0) && ($update == true)) {
                            $check_added = true;
                            DB::table('revisions')->insert([
                                'revisionable_type' => "App\Models\UploadProducts",
                                'revisionable_id' => $uploaded_document->id,
                                'user_id' => Auth::user()->owner_id,
                                'key' => 'add_image',
                                'old_value' => null,
                                'new_value' => $uploaded_document->id,
                                'created_at' => new \DateTime,
                                'updated_at' => new \DateTime,
                            ]);
                        }
                        unlink($processedImagePath);

                    }
                }

                if (($parent->product_added_from_catalog == 1) && (count($history) == 0) && ($check_added == false)) {
                    $parent->approved = 1;
                    $parent->save();
                } else {
                    // Update the approved field in the parent product
                    $parent->update(['approved' => 0]);

                    // Update the approved field in the children related to the parent
                    $parent->children()->update(['approved' => 0]);
                }
            }
        }

        //insert paths of thumbnails in DB and upload thumbnails in folder under public/upload_products/Product{id_porduct}/thumbnails
        if ($data['photosThumbnail'] != null) {
            $check_added = false;
            foreach ($data['photosThumbnail'] as $key => $image) {
                $imageName = time() . rand(5, 15) . '.jpg';
                // $image->move(public_path('/upload_products/Product-'.$collection['product']->id.'/thumbnails') , $imageName);
                // $path = '/upload_products/Product-'.$collection['product']->id.'/thumbnails'.'/'.$imageName;
                $extension = $image->getClientOriginalExtension();

                if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png'])) {
                    $processedImagePath = $this->resizeImageIfNeeded($image, 400, 90);
                    $path_thumbnail = '/upload_products/Product-' . $collection['product']->id . '/thumbnails/' . $imageName;
                    $destinationPath = public_path($path_thumbnail);
                    copy($processedImagePath, $destinationPath);
        
                    $uploaded_document = new UploadProducts;
                    $uploaded_document->id_product = $collection['product']->id;
                    $uploaded_document->path = $path_thumbnail;
                    $uploaded_document->extension = 'jpg';
                    $uploaded_document->type = 'thumbnails';
                    $uploaded_document->save();

                    if (($collection['product']->is_draft == 0) && ($update == true)) {
                        $check_added = true;
                        DB::table('revisions')->insert([
                            'revisionable_type' => "App\Models\UploadProducts",
                            'revisionable_id' => $uploaded_document->id,
                            'user_id' => Auth::user()->owner_id,
                            'key' => 'add_image',
                            'old_value' => null,
                            'new_value' => $uploaded_document->id,
                            'created_at' => new \DateTime,
                            'updated_at' => new \DateTime,
                        ]);
                    }
                    unlink($processedImagePath);

                }
            }

            if (($parent->product_added_from_catalog == 1) && (count($history) == 0) && ($check_added == false)) {
                $parent->approved = 1;
                $parent->save();
            } else {
                // Update the approved field in the parent product
                $parent->update(['approved' => 0]);

                // Update the approved field in the children related to the parent
                $parent->children()->update(['approved' => 0]);
            }
        }
    }


    public function resizeImageIfNeeded($image, $maxDimension, $quality = 90) {
        $tempPath = $image->getPathname();
    
        if (!file_exists($tempPath)) {
            throw new \Exception('Temporary file does not exist: ' . $tempPath);
        }
    
        // Load the image using Intervention Image
        $img = Image::make($tempPath);
        
        // Get original dimensions
        $originalWidth = $img->width();
        $originalHeight = $img->height();
    
        // Check if resizing is needed
        if ($originalWidth > $maxDimension || $originalHeight > $maxDimension) {
            $scalingFactor = $maxDimension / max($originalWidth, $originalHeight);
            $newWidth = $originalWidth * $scalingFactor;
            $newHeight = $originalHeight * $scalingFactor;
            $img->resize($newWidth, $newHeight, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }
    
        // Convert to JPG and compress
        $tempPath = tempnam(sys_get_temp_dir(), 'image_') . '.jpg';
        $img->encode('jpg', $quality)->save($tempPath);
    
        return $tempPath;
    }
    
    
    
}
