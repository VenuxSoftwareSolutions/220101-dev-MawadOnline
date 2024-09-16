<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\BrandTranslation;
use App\Models\Upload;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Specify the folder containing the brand images
        $imageFolder = public_path('All_Brands');
        $images = File::files($imageFolder);
    
        foreach ($images as $image) {
            // Extract brand name from the file name (assuming name is in the file name)
            $brandName = Str::before($image->getFilename(), '.');
    
            // Create the brand
            $brand = new Brand;
            $brand->name = ucfirst($brandName);
            $brand->meta_title = ucfirst($brandName) . ' Meta Title';
            $brand->meta_description = 'Description for ' . ucfirst($brandName);
    
            // Slug logic from your store method
            $brand->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $brandName)) . '-' . Str::random(5);
    
            // Gather file information
            $fileOriginalName = ucfirst($brandName);
            $fileName = $image->getFilename();
            $fileSize = $image->getSize(); // Size in bytes
            $fileExtension = $image->getExtension(); // File extension
    
            // Create a new upload record
            $upload = new Upload;
            $upload->file_original_name = $fileOriginalName;
            $upload->file_name = 'All_Brands/' . $fileName;
            $upload->user_id = 1; // Assuming user ID 1
            $upload->file_size = $fileSize; // Set file size
            $upload->extension = $fileExtension; // Set file extension
            $upload->type = 'image'; // Type is image
    
            $upload->save();
    
            // Set the brand logo to the upload ID
            $brand->logo = $upload->id;
    
            // Save the brand
            $brand->save();
    
            // Create a brand translation
            $brandTranslation = BrandTranslation::firstOrNew([
                'lang' => env('DEFAULT_LANGUAGE'), 
                'brand_id' => $brand->id
            ]);
    
            $brandTranslation->name = ucfirst($brandName);
            $brandTranslation->save();
        }
    }
    
}
