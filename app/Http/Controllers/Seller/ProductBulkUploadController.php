<?php

namespace App\Http\Controllers\Seller;

use App\Exports\ErrorsExport;
use App\Exports\ProductBulkExport;
use App\Imports\ProductsBulkImport;
use App\Jobs\ImportBulkFileJob;
use App\Mail\ValidationReportMail;
use PDF;
use Auth;
use Excel;
use App\Models\User;
use App\Models\Brand;
use App\Models\BulkUploadFile;
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
use Illuminate\Support\Facades\Mail;
use Storage;

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


    public function getFiles(Request $request)
    {
        $perPage = $request->input('length'); // Number of records per page
        $pageStart = $request->input('start'); // Start index for the current page
        $orderColumn = $request->input('columns')[$request->input('order')[0]['column']]['data']; // Column to sort
        $orderDir = $request->input('order')[0]['dir']; // Order direction (asc/desc)
    
        $searchValue = $request->input('search')['value']; // Search value
    
        $query = BulkUploadFile::where('user_id', Auth::user()->id);
    
        if ($searchValue) {
            $query->where(function($q) use ($searchValue) {
                $q->where('filename', 'like', '%' . $searchValue . '%')
                  ->orWhere('status', 'like', '%' . $searchValue . '%')
                  ->orWhere('extension', 'like', '%' . $searchValue . '%')
                  ->orWhere('size', 'like', '%' . $searchValue . '%');
            });
        }
    
        $totalRecords = $query->count();
    
        $files = $query->orderBy($orderColumn, $orderDir)
                       ->offset($pageStart)
                       ->limit($perPage)
                       ->get();
    
        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $files
        ]);
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
        
        $request->validate([
            'bulk_file' => 'required|file', // Adjusted validation rule
            ], [
                'bulk_file.mimes' => 'The uploaded file must be a file of type: xlsx, xlsm.',
        ]);

        if ($request->file('bulk_file')->isValid()) {
            $file = $request->file('bulk_file');
            $path = $file->store('uploads', 'public');
            $fileModel = new BulkUploadFile();
            $fileModel->user_id = auth()->id(); // Assuming you have user authentication in place
            $fileModel->filename = $file->getClientOriginalName();
            $fileModel->path = $path;
            $fileModel->status = 'processing';
            $fileModel->extension = $file->getClientOriginalExtension();
            $fileModel->size = humanFileSize($file->getSize());
            $fileModel->save();


             // Read and validate the Excel file
            $errors = $this->validateExcel($file);

            if (count($errors) > 0) {
                $fileModel->status = 'failed';
                $fileModel->save();

                // Generate the report and send it by email
                $reportPath = $this->generateReport($errors);

                Mail::to('recipient@example.com')->send(new ValidationReportMail($reportPath));

                flash(translate('Validation errors found. A report has been sent to your email.'))->error();

                return back();
            }

            flash(translate('File uploaded successfully.'))->success();

                // Dispatch the job
         //   ImportBulkFileJob::dispatch($path);
            

         $path = Storage::disk('public')->path($path);

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
          //  return back();
        }         
    }


    private function validateExcel($file)
    {
        $errors = [];
        $data = Excel::toArray([], $file)[1]; // Assuming you want to use the second sheet
    
        // Start iterating from the 4th row (index 3 in zero-based index)
        for ($rowIndex = 3; $rowIndex < count($data); $rowIndex++) {
            $row = $data[$rowIndex];
            // Check if the row has any non-empty cells
            if ($this->rowHasData($row)) {
                // Validate specific cells in the row
                $this->validateCell($errors, $rowIndex, 'A', $row, ['required', 'max:255']);
                $this->validateCell($errors, $rowIndex, 'B', $row, ['required', 'max:255']);
                $this->validateCell($errors, $rowIndex, 'C', $row, ['required', 'max:255']);
                $this->validateCell($errors, $rowIndex, 'D', $row, ['required', 'max:255']);
                $this->validateCell($errors, $rowIndex, 'E', $row, ['required', 'max:255']);
                $this->validateCell($errors, $rowIndex, 'F', $row, ['required', 'max:255']);
                $this->validateCell($errors, $rowIndex, 'G', $row, ['required', 'max:255']);
                $this->validateCell($errors, $rowIndex, 'H', $row, ['required', 'max:255']);
                $this->validateCell($errors, $rowIndex, 'J', $row, ['required', 'max:255']);
                $this->validateCell($errors, $rowIndex, 'K', $row, ['required', 'max:255']);
                $this->validateCell($errors, $rowIndex, 'L', $row, ['required', 'max:255']);
                $this->validateCell($errors, $rowIndex, 'M', $row, ['required', 'max:255']);
                $this->validateCell($errors, $rowIndex, 'AJ', $row, ['required', 'max:255']);
                $this->validateCell($errors, $rowIndex, 'AY', $row, ['required', 'max:255']);
                $this->validateCell($errors, $rowIndex, 'AZ', $row, ['required', 'max:255']);
                $this->validateCell($errors, $rowIndex, 'BA', $row, ['required', 'max:255']);
                $this->validateCell($errors, $rowIndex, 'BY', $row, ['required', 'max:255']);
                $this->validateCell($errors, $rowIndex, 'CB', $row, ['required', 'max:255']);
                $this->validateCell($errors, $rowIndex, 'CC', $row, ['required', 'max:255']);
                $this->validateCell($errors, $rowIndex, 'CD', $row, ['required', 'max:255']);
                $this->validateCell($errors, $rowIndex, 'CE', $row, ['required', 'max:255']);
                $this->validateCell($errors, $rowIndex, 'CF', $row, ['required', 'max:255']);
                $this->validateCell($errors, $rowIndex, 'CG', $row, ['required', 'max:255']);
                $this->validateCell($errors, $rowIndex, 'CH', $row, ['required', 'max:255']);
                $this->validateCell($errors, $rowIndex, 'CJ', $row, ['required', 'max:255']);
                $this->validateCell($errors, $rowIndex, 'CK', $row, ['required', 'max:255']);
                $this->validateCell($errors, $rowIndex, 'CL', $row, ['required', 'max:255']);
                $this->validateCell($errors, $rowIndex, 'CM', $row, ['required', 'max:255']);

                // Add more validation rules as needed for other cells in the row
            }
        }
    
        return $errors;
    }

    private function rowHasData($row)
    {
        // Check if the row has any non-empty cells
        foreach ($row as $cell) {
            if (!empty($cell)) {
                return true;
            }
        }
        return false;
    }
    
    private function validateCell(&$errors, $rowIndex, $column, $row, $rules)
    {
        $colIndex = $this->excelColumnIndex($column);
        $cell = isset($row[$colIndex]) ? $row[$colIndex] : null;

        // Simulate validation using Laravel's Validator::make() for custom rules
        $validator = \Validator::make(
            [$column => $cell],
            [$column => $rules],
            [],
            [$column => $this->customAttributes[$column] ?? $column] // Use custom attribute names
        );

        if ($validator->fails()) {
            $cellAddress = $this->getCellAddress($rowIndex, $colIndex);
            $errors[] = "Cell $cellAddress: " . $validator->errors()->first($column);
        }
    }

    private $customAttributes = [
        'A' => 'Product Type (Leaf Category)',
        'B' => 'Product Name',
        'C' => 'Brand',
        'D' => 'Unit of Sale',
        'E' => 'Country of Origin',
        'F' => 'Manufacturer',
        'G' => 'Tags',
        'H' => 'Short Description',
        'J' => 'Show Stock Quantity',
        'L' => 'Product Media',
        'M' => 'Gallery Image 1 Relative Path',

        // Add more as needed
        'AJ' => 'SKU',
        'AY' => 'From Quantity',
        'AZ' => 'To Quantity',
        'BA' => 'Unit Price',
        'BY' => 'Sample Available',
        'CB' => 'Length',
        'CC' => 'Width',
        'CD' => 'Height',
        'CE' => 'Weight',
        'CF' => 'Breakable',
        'CG' => 'Min. Temperature',
        'CH' => 'Max. Temperature',
        'CJ' => 'From Quantity',
        'CK' => 'To Quantity',
        'CL' => 'Shipper',
        'CM' => 'Order Preparation Days',
        // Continue for all necessary columns
    ];


    private function excelColumnIndex($column)
    {
        $columnIndex = 0;
        $length = strlen($column);

        // Iterate over each character of the column name
        for ($i = 0; $i < $length; $i++) {
            $char = strtoupper($column[$i]);
            $columnIndex = $columnIndex * 26 + (ord($char) - 64);
        }

        return $columnIndex - 1; // Return zero-based index
    }

    private function getCellAddress($rowIndex, $colIndex)
    {
        // Convert column index (0-based) to Excel-style column letter (A, B, C, ..., Z, AA, AB, ..., AZ, BA, BB, ..., AJ, etc.)
        $colLetter = $this->excelColumnIndexToLetter($colIndex);

        // Convert 0-based row index to 1-based row number
        $rowNumber = $rowIndex + 1;

        // Return cell address in Excel format (e.g., A1, B2, AJ4)
        return $colLetter . $rowNumber;
    }

    private function excelColumnIndexToLetter($index)
    {
        $numeric = $index + 1;
        $alpha = '';

        while ($numeric > 0) {
            $remainder = ($numeric - 1) % 26;
            $alpha = chr(65 + $remainder) . $alpha;
            $numeric = intval(($numeric - $remainder) / 26);
        }

        return $alpha;
    }
    
  

    private function generateReport($errors)
    {
        $fileName = 'validation_report.xlsx';
        $filePath = 'reports/' . $fileName; // Relative path inside storage/app
    
        // Generate Excel file and store it
        Excel::store(new ErrorsExport($errors), $filePath, 'local');
    
        // Return the full path to the stored file
        return public_path($filePath);
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
