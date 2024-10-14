<?php

namespace App\Http\Controllers\Seller;

use App\Exports\ProductBulkExport;
use App\Imports\ProductsBulkImport;
use PDF;
use Auth;
use Excel;
use App\Models\User;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\ProductsImport;
use App\Services\ProductFlashDealService;
use App\Services\ProductPricingService;
use App\Services\ProductService;
use App\Services\ProductStockService;
use App\Services\ProductTaxService;
use App\Services\ProductUploadsService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProductBulkUploadController extends Controller
{
    protected $productService;
    protected $productTaxService;
    protected $productFlashDealService;
    protected $productStockService;
    protected $productUploadsService;
    protected $productPricingService;

    public function __construct(
        ProductService $productService,
        ProductTaxService $productTaxService,
        ProductFlashDealService $productFlashDealService,
        ProductStockService $productStockService,
        ProductUploadsService $productUploadsService,
        ProductPricingService $productPricingService
    ) {
        $this->productService = $productService;
        $this->productTaxService = $productTaxService;
        $this->productFlashDealService = $productFlashDealService;
        $this->productStockService = $productStockService;
        $this->productUploadsService = $productUploadsService;
        $this->productPricingService = $productPricingService;
    }


    // public function __construct() {
    //    // $this->middleware(['permission:seller_product_bulk_import'])->only('index');
    //   //  $this->middleware(['permission:seller_product_bulk_export'])->only('export');

    // }

    public function index()
    {
        $owner = User::find(Auth::user()->owner_id);
        if($owner->shop->verification_status){
            return view('seller.product.product_bulk_upload.index');
        }
        else{
            flash(translate('Your shop is not verified yet!'))->warning();
            return back();
        }
    }

    public function pdf_download_category()
    {
        $categories = Category::all();

        return PDF::loadView('backend.downloads.category',[
            'categories' => $categories,
        ], [], [])->download('category.pdf');
    }

    public function pdf_download_brand()
    {
        $brands = Brand::all();

        return PDF::loadView('backend.downloads.brand',[
            'brands' => $brands,
        ], [], [])->download('brands.pdf');
    }

    public function bulk_upload(Request $request)
    {
        if($request->hasFile('bulk_file')){
             $import = new ProductsBulkImport(
            $this->productService,
            $this->productTaxService,
            $this->productFlashDealService,
            $this->productStockService,
            $this->productUploadsService,
            $this->productPricingService
        );
        
            Excel::import($import, request()->file('bulk_file'));
        }

        return back();
    }

    public function download_file(Request $request)
    {

        $selectedId = $request->selectedId;

        // Determine the category level
        $categoryLevel = $this->getCategoryLevel($selectedId);

        // Initialize variables for payload
        $level2CategoryId = null;
        $level3CategoryId = null;

        if ($categoryLevel == 3) {
            // If third level, set level3CategoryId and find the parent (level2CategoryId)
            $level3CategoryId = $selectedId;
            $level2CategoryId = $this->getParentCategoryId($selectedId);
        } elseif ($categoryLevel == 2) {
            // If second level, set level2CategoryId
            $level2CategoryId = $selectedId;
        }

        {
            // Define the payload
            $payload = [
                'vendorId' => Auth::id(),
                'level2CategoryId' => $level2CategoryId,
                'level3CategoryId' => $level3CategoryId,
            ];

            // Send the POST request    
            $response = Http::post('http://localhost:9115/bu/xlgen', $payload);

            if ($response->successful()) {
                $data = $response->json();
    
                if ($data['success']) {
                    $filePath = $data['fileName'];
    
                    // Check if the file exists and is readable
                    if (file_exists($filePath) && is_readable($filePath)) {
                        // Return the file for download
                        return response()->download($filePath);
                    } else {
                        return response()->json(['error' => 'File does not exist or is not readable.'], 404);
                    }
                } else {
                    return response()->json(['error' => $data['errorMsg']], 400);
                }
            } else {
                return response()->json(['error' => 'Request failed.'], $response->status());
            }
        }

    }


    // Function to get the level of the category
    private function getCategoryLevel($categoryId)
    {
        // Retrieve the category from the database
        $category = Category::find($categoryId);

        // Check if the category exists and return its level
        if ($category) {
            return $category->level;
        }

        // Return null if the category doesn't exist
        return null;
    }

    // Function to get the parent category ID for a third-level category
    private function getParentCategoryId($categoryId)
    {
        // Retrieve the category from the database
        $category = Category::find($categoryId);

        // Check if the category exists and return its parent ID
        if ($category) {
            return $category->parent_id; // Assuming 'parent_id' is the foreign key for the parent category
        }

        // Return null if the category doesn't exist or has no parent
        return null;
    }
}
