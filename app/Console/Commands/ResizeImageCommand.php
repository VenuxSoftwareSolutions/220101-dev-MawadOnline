<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Intervention\Image\ImageManager;

class ResizeImageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:resize 
        {input : Path to the input image} 
        {output : Path to save the processed image} 
        {--maxDimension=1200 : Maximum dimension for resizing} 
        {--quality=90 : Compression quality for JPEG (1-100)} 
        {--driver=gd : Image driver to use (gd or imagick)}' ;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resize and compress an image based on given dimensions and quality';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $inputPath = $this->argument('input');
        $outputPath = $this->argument('output');
        $maxDimension = (int) $this->option('maxDimension');
        $quality = (int) $this->option('quality');
        $driverOption = $this->option('driver');

        // Validate driver
        if (!in_array($driverOption, ['gd', 'imagick'])) {
            $this->error("Invalid driver specified. Use 'gd' or 'imagick'.");
            return Command::FAILURE;
        }

        // Create the ImageManager instance with the chosen driver
        $manager = $driverOption === 'gd' ? ImageManager::gd() : ImageManager::imagick();

        if (!file_exists($inputPath)) {
            $this->error("Input file does not exist: $inputPath");
            return Command::FAILURE;
        }

        try {
            // Load the image
            $img = $manager->read($inputPath);

            // Get original dimensions
            $originalWidth = $img->width();
            $originalHeight = $img->height();

            // Resize if necessary
            if ($originalWidth > $maxDimension || $originalHeight > $maxDimension) {
                $scalingFactor = $maxDimension / max($originalWidth, $originalHeight);

                $newWidth = (int)($originalWidth * $scalingFactor);
                $newHeight = (int)($originalHeight * $scalingFactor);
                $img->resize($newWidth, $newHeight, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

        // Encode and save based on file extension
        $extension = strtolower(pathinfo($outputPath, PATHINFO_EXTENSION));

        switch ($extension) {
            case 'webp':
                $img->toWebp($quality)->save($outputPath);
                break;
            case 'avif':
                $img->toAvif($quality)->save($outputPath);
                break;
            case 'bmp':
            case 'bitmap':
                $img->toBitmap($quality)->save($outputPath);
                break;
            case 'gif':
                $img->toGif($quality)->save($outputPath);
                break;
            case 'png':
                $img->toPng($quality)->save($outputPath);
                break;
            case 'heic':
                $img->toHeic($quality)->save($outputPath);
                break;
            case 'jpg':
            case 'jpeg':
            default:
                $img->toJpeg($quality)->save($outputPath);
                break;
        }

        $this->info("Image successfully saved in {$extension} format at: $outputPath");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("An error occurred: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
