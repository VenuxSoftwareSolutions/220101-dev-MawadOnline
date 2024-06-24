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
        return Excel::download(new ProductBulkExport, 'products.xlsx');

    }
}
