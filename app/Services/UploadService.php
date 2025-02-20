<?php
namespace App\Services;

use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class UploadService
{
    protected $imageManager;

    public function __construct()
    {
        $this->imageManager = new ImageManager(['driver' => 'imagick']);
    }

    public function processImage(UploadedFile $file, $maxDimension = 1280, $quality = 90): string
    {
        $img = $this->imageManager->make($file->getPathname());

        // Check if resizing is necessary
        if ($img->width() > $maxDimension || $img->height() > $maxDimension) {
            $scalingFactor = $maxDimension / max($img->width(), $img->height());
            $img->resize(
                (int)($img->width() * $scalingFactor),
                (int)($img->height() * $scalingFactor),
                function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                }
            );
        }else {
            $img->resize(
                (int)($img->width() - 1),
                (int)($img->height() - 1),
                function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                }
            );
        }

        // Encode and save to temporary file
        $tempPath = tempnam(sys_get_temp_dir(), 'image_') . '.jpg';
        $img->encode('jpg', $quality)->save($tempPath);

        return $tempPath;

    }

  
}
