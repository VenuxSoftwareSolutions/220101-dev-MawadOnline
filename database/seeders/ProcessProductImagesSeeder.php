<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use App\Models\UploadProducts; // Adjust the namespace as per your model

class ProcessProductImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the base directory
        $baseDirectory = public_path('products');

        // Get all directories under public/products
        $directories = File::directories($baseDirectory);

        foreach ($directories as $directory) {
            // Extract the product ID from the directory name
            $productId = basename($directory);

            // Create the target directories for images and thumbnails
            $targetImageDirectory = public_path("upload_products/Product-{$productId}/images");
            $targetThumbnailDirectory = public_path("upload_products/Product-{$productId}/thumbnails");

            // Ensure the target directories exist
            File::ensureDirectoryExists($targetImageDirectory);
            File::ensureDirectoryExists($targetThumbnailDirectory);

            // Get all images in the current directory
            $images = File::files($directory);

            foreach ($images as $image) {
                // Generate a unique name for the image
                $imageName = time() . rand(5, 15) . '.jpg';

                // Define paths for the image and thumbnail
                $imagePath = "upload_products/Product-{$productId}/images/{$imageName}";
                $thumbnailPath = "upload_products/Product-{$productId}/thumbnails/{$imageName}";

                // Save the original image
                File::copy($image->getPathname(), public_path($imagePath));

                // Create and save the thumbnail
                $thumbnail = Image::make($image->getPathname());
                $thumbnail->resize(300, 300); // Adjust the size as needed
                $thumbnail->save(public_path($thumbnailPath));

                // Insert the image path into the database
                UploadProducts::create([
                    'id_product' => $productId,
                    'path' => $imagePath,
                    'extension' => 'jpg',
                    'type' => 'images',
                ]);

                // Insert the thumbnail path into the database
                UploadProducts::create([
                    'id_product' => $productId,
                    'path' => $thumbnailPath,
                    'extension' => 'jpg',
                    'type' => 'thumbnails',
                ]);
            }
        }
    }
}