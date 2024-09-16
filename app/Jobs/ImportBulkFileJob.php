<?php

namespace App\Jobs;

use App\Imports\ProductsBulkImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ImportBulkFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function handle()
    {
        $path = Storage::disk('public')->path($this->filePath);

        $import = new ProductsBulkImport(
            app()->make('App\Services\ProductService'),
            app()->make('App\Services\ProductTaxService'),
            app()->make('App\Services\ProductFlashDealService'),
            app()->make('App\Services\ProductStockService'),
            app()->make('App\Services\ProductUploadsService'),
            app()->make('App\Services\ProductPricingService')
        );


        $x =    Excel::import($import, $path);

        dd($x);
        // Optionally, update the file status in the database
        // $fileModel = BulkUploadFile::where('path', $this->filePath)->first();
        // $fileModel->status = 'completed';
        // $fileModel->save();
    }
}
