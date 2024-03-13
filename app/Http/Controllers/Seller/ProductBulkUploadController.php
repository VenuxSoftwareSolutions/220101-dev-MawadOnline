<?php

namespace App\Http\Controllers\Seller;

use PDF;
use Auth;
use Excel;
use App\Models\User;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\ProductsImport;

class ProductBulkUploadController extends Controller
{
    public function __construct() {
        $this->middleware(['permission:seller_product_bulk_import'])->only('index');
        $this->middleware(['permission:seller_product_bulk_export'])->only('export');

    }

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
            $import = new ProductsImport;
            Excel::import($import, request()->file('bulk_file'));
        }

        return back();
    }

}
