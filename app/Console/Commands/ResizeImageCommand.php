<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use Intervention\Image\Facades\Image;

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
                            {--minWidth=300 : Minimum width for resizing} 
                            {--maxWidth=1200 : Maximum width for resizing} 
                            {--quality=90 : Compression quality (1-100)}';

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
        $minWidth = (int)$this->option('minWidth');
        $maxWidth = (int)$this->option('maxWidth');
        $quality = (int)$this->option('quality');


        if (!file_exists($inputPath)) {
            $this->error("Input file does not exist: $inputPath");
            return Command::FAILURE;
        }

        try {
            // Load the image
            $img = Image::make($inputPath);

            // Get original dimensions
            $originalWidth = $img->width();
            $originalHeight = $img->height();

            // Resize if necessary
            if ($originalWidth > $maxWidth || $originalWidth < $minWidth) {
                $scalingFactor = $maxWidth / $originalWidth;
                $newWidth = $originalWidth * $scalingFactor;
                $newHeight = $originalHeight * $scalingFactor;
                $img->resize($newWidth, $newHeight, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            // Encode and save as JPG
            $img->encode('jpg', $quality)->save($outputPath);

            $this->info("Image processed successfully and saved to: $outputPath");
            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("An error occurred: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
