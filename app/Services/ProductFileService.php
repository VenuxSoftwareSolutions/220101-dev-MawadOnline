<?php

namespace App\Services;

use App\Models\UploadProducts;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

class ProductFileService
{
    protected $service;
    protected $driveService;
    public function __construct()
    {
        // Initialize Google Drive API service using the API key
        $this->service = new \Google_Client();
        $this->service->setDeveloperKey(env('GOOGLE_DRIVE_API_KEY'));
        $driveService = new \Google_Service_Drive($this->service);
        $this->driveService = $driveService;
    }

    public function traverseFolder($folderId)
    {
        $contents = [];
        $results = $this->driveService->files->listFiles([
            'q' => "'$folderId' in parents and trashed = false",
            'fields' => 'files(id, name, mimeType)',
        ]);

        foreach ($results->getFiles() as $item) {
            $fileId = $item->getId();
            $fileName = $item->getName();
            $mimeType = $item->getMimeType();

            if ($mimeType == 'application/vnd.google-apps.folder') {
                $contents[$fileName] = $this->traverseFolder($fileId);
            } else {
                $downloadLink = "https://drive.google.com/uc?export=download&id={$fileId}";
                $contents[$fileName] = $downloadLink;
            }
        }
        return $contents;
    }

    public function uploadProductFiles($productId, $productData)
    {
        $filteredImageData = $this->getValuesBetweenRefundableAndVideoProvider($productData);

        $filteredFileData = $this->getValuesBetweenVideoLinkAndSKU($productData);

        if (count($filteredImageData) > 0) {
            $this->handleProductFiles($productId, $filteredImageData, true, true, false);
        }

        if (count($filteredFileData) > 0) {
            $this->handleProductFiles($productId, $filteredFileData, false, false, true);
        }

        return true;
    }



    public function handleProductFiles($productId, $data, $image, $thumbnail, $document)
    {
        $iteration = 0;
        foreach ($data as $header => $value) {
            $iteration++;

            if ($iteration < 2) {
                // Assuming the $value is a Google Drive folder URL
                if (filter_var($value, FILTER_VALIDATE_URL)) {

                    // Extract the folder ID from the Google Drive link
                    $folderId = $this->extractFolderId($value);

                    // Traverse the folder and retrieve files
                    $files = $this->traverseFolder($folderId);

                    // Debugging: Check the structure of the $files variable

                    // Process the retrieved files and folders
                    $this->processFolder($productId, $files, null, $image, $thumbnail, $document);
                }
            } else {
                if (filter_var($value, FILTER_VALIDATE_URL)) {
                    // Check if it's a Google Drive file URL
                    if (strpos($value, 'drive.google.com/file/d/') !== false) {
                        // Extract the file ID from the Google Drive URL
                        $fileId = $this->extractFileId($value);

                        // Convert the Google Drive file URL to a direct download link
                        $downloadLink = "https://drive.google.com/uc?export=download&id={$fileId}";

                        // Process the file with the download link
                        $this->processFile($productId, 'test.jpg', $downloadLink, null, $image, false, $document); // Process file
                    }
                }
            }
        }

        return true;
    }


    private function extractFileId($url)
    {
        preg_match('/\/file\/d\/([^\/]+)\//', $url, $matches);
        return $matches[1] ?? null; // Return the file ID if found, otherwise null
    }


    private function processFolder($productId, $files, $folderName = null, $image, $thumbnail, $document)
    {
        foreach ($files as $key => $fileData) {
            // If fileData is an array, it's a folder
            if (is_array($fileData)) {
                // Recursively process sub-folders
                $this->processFolder($productId, $fileData, $key, $image, $thumbnail, $document); // Pass the folder name as $key
            } else {
                // Debug individual files
                // Process file (no folder)
                $this->processFile($productId, $key, $fileData, $folderName, true, $image, $thumbnail, $document); // Process file with folder context
            }
        }
    }

    public function processFile($productId, $fileName = null, $downloadLink, $folderName = null, $image = null, $thumbnail = null, $document = null)
    {
        // Download the file content
        $response = Http::get($downloadLink);

        // Attempt to get the file name from the headers
        $contentDisposition = $response->header('Content-Disposition');
        if ($contentDisposition && preg_match('/filename="(.+?)"/', $contentDisposition, $matches)) {
            $fileName = $matches[1]; // Extract file name from header
        }

        // If no file name is available from the header, use the default or generate one
        if (!$fileName) {
            $fileName = 'default_name.jpg'; // Fallback to default name if not found
        }

        $filePath = $folderName
            ? "upload_products/Product-{$productId}/$folderName/$fileName"
            : "upload_products/Product-{$productId}/$fileName"; // Save directly if no folder

        // Store the file in Laravel's storage
        Storage::put($filePath, $response->body());



        if ($thumbnail) {
            // Create thumbnail path
            $thumbnailFolder = public_path("/upload_products/Product-{$productId}/thumbnails");

            // Ensure the thumbnail directory exists
            if (!File::exists($thumbnailFolder)) {
                File::makeDirectory($thumbnailFolder, 0755, true);
            }



            // Resize and save the thumbnail
            $img3 = Image::make(Storage::path($filePath)); // Use Storage::path to get the correct path
            $img3->resize(300, 300);
            $path_thumbnail = "upload_products/Product-{$productId}/thumbnails/$fileName"; // Relative path
            $path_to_save = public_path($path_thumbnail); // Public path for saving
            $img3->save($path_to_save);

            // Save to database (Thumbnail)
            $productThumbnail = new UploadProducts();
            $productThumbnail->id_product = $productId;
            $productThumbnail->path = $path_thumbnail; // Store the relative path
            $productThumbnail->extension = 'jpg';
            $productThumbnail->type = 'thumbnails';
            $productThumbnail->save();
        }


        if ($image) {
            // Save to database (Main Image)
            $productthumbnail = new UploadProducts();
            $productthumbnail->id_product = $productId;
            $productthumbnail->path = $filePath;
            $productthumbnail->extension = 'jpg';
            $productthumbnail->type = 'images';
            $productthumbnail->save();
        }


        if ($document) {
            $productDocument = new UploadProducts();
            $productDocument->id_product = $productId;
            $productDocument->path = $filePath;
            $productDocument->extension = 'jpg';
            $productDocument->document_name = $fileName;

            $productDocument->type = 'documents';
            $productDocument->save();
        }




        // Output file storage path for verification
        // echo "Stored file: " . Storage::url($filePath);
    }




    function getValuesBetweenVideoLinkAndSKU(array $productData)
    {
        // Initialize the result array
        $result = [];

        // Set flag to determine when to start and stop collecting data
        $startCollecting = false;

        // Loop through the array
        foreach ($productData as $header => $value) {
            // Start collecting after "Refundable *"
            if ($header === "Video Link") {
                $startCollecting = true;
                continue; // Skip "Refundable *"
            }

            // Stop collecting after reaching "Video Provider"
            if ($header === "SKU *") {
                break;
            }

            // Collect headers and values if flag is set
            if ($startCollecting) {
                $result[$header] = $value;
            }
        }

        return $result;
    }


    function getValuesBetweenRefundableAndVideoProvider(array $productData)
    {
        // Initialize the result array
        $result = [];

        // Set flag to determine when to start and stop collecting data
        $startCollecting = false;

        // Loop through the array
        foreach ($productData as $header => $value) {
            // Start collecting after "Refundable *"
            if ($header === "Refundable *") {
                $startCollecting = true;
                continue; // Skip "Refundable *"
            }

            // Stop collecting after reaching "Video Provider"
            if ($header === "Video Provider") {
                break;
            }

            // Collect headers and values if flag is set
            if ($startCollecting) {
                $result[$header] = $value;
            }
        }

        return $result;
    }
    // Helper function to extract folder ID from Google Drive URL
    protected function extractFolderId($url)
    {
        $start = strrpos($url, '/') + 1;
        $end = strpos($url, '?', $start);
        return substr($url, $start, $end - $start);
    }
}
