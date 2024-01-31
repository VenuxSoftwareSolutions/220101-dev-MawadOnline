<?php

namespace App\Http\Controllers\Seller;

use App\Exports\StockSummaryExport;
use App\Models\StockDetails;
use App\Models\StockSummary;
use App\Models\Warehouse;
use Auth;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;

class StockController extends Controller
{
    // public function index(Request $request)
    // {

    //     $productVariant = $request->input('productVariant');
    //     $warehouse = $request->input('warehouse');

    //     if ($productVariant && $warehouse) {
    //         // Fetch inventory data based on the provided parameters
    //         // For example, you might want to filter the data using these parameters
    //         $inventoryData = StockSummary::where('variant_id', $productVariant)
    //             ->where('warehouse_id', $warehouse)
    //             ->paginate(10);
    //     } else {
    //         $query = StockSummary::query();
    //         // Check if a search term is provided
    //         if ($searchTerm = $request->input('search')) {
    //             $query->whereHas('productVariant', function ($query) use ($searchTerm) {
    //                 $query->where('name', 'like', "%{$searchTerm}%") ;
    //                     // ->orWhere('sku', 'like', "%{$searchTerm}%");
    //             })
    //                 ->orWhereHas('warehouse', function ($query) use ($searchTerm) {
    //                     $query->where('warehouse_name', 'like', "%{$searchTerm}%");
    //                 })
    //                 ->orWhere('current_total_quantity', 'like', "%{$searchTerm}%")
    //                 ->orWhereDate('updated_at', 'like', "%{$searchTerm}%");
    //         }

    //         $inventoryData = $query->paginate(10);
    //     }
    //     $warehouses = Warehouse::all();
    //     return view('backend.stock.index', compact('inventoryData', 'warehouses'));
    // }

    public function index(Request $request, $type = null)
    {
        $productVariant = $request->input('productVariant');
        $warehouse = $request->input('warehouse');

        // $query = StockSummary::query();

        // // Join with product variants and warehouses to allow sorting on related model fields
        // $query->join('products', 'stock_summaries.variant_id', '=', 'products.id')
        //     ->join('warehouses', 'stock_summaries.warehouse_id', '=', 'warehouses.id')
        //     ->select('stock_summaries.*', 'products.name as product_variant_name',/* , 'products.sku', */ 'warehouses.warehouse_name');

        // // Check if a search term is provided
        // if ($searchTerm = $request->input('search')) {
        //     $query->whereHas('productVariant', function ($query) use ($searchTerm) {
        //         $query->where('name', 'like', "%{$searchTerm}%");
        //         //   ->orWhere('sku', 'like', "%{$searchTerm}%"); // Assuming 'sku' is a column in 'product_variants' table
        //     })
        //         ->orWhereHas('warehouse', function ($query) use ($searchTerm) {
        //             $query->where('warehouse_name', 'like', "%{$searchTerm}%");
        //         })
        //         ->orWhere('current_total_quantity', 'like', "%{$searchTerm}%")
        //         ->orWhereDate('stock_summaries.updated_at', 'like', "%{$searchTerm}%");
        // }

        // // Handling sorting
        // $sortField = $request->input('sort_field', 'product_variant'); // default sort field
        // $sortDirection = $request->input('sort_direction', 'asc'); // default sort direction

        // if ($sortField == 'product_variant') {
        //     $query->orderBy('products.name', $sortDirection);
        // }
        // // elseif ($sortField == 'sku') {
        // //     $query->orderBy('products.sku', $sortDirection);
        // // }
        // elseif ($sortField == 'warehouse') {
        //     $query->orderBy('warehouses.warehouse_name', $sortDirection);
        // } elseif ($sortField == 'quantity') {
        //     $query->orderBy('current_total_quantity', $sortDirection);
        // } elseif ($sortField == 'quantity') {
        //     $query->orderBy('current_total_quantity', $sortDirection);
        // } elseif ($sortField == 'updated_at') {
        //     $query->orderBy('updated_at', $sortDirection);
        // } else {
        //     $query->orderBy($sortField, $sortDirection);
        // }

        // // Filter by product variant and warehouse if provided
        // if ($productVariant && $warehouse) {
        //     $query->where('variant_id', $productVariant)
        //         ->where('warehouse_id', $warehouse);
        // }

        // if ($type == "excel") {
        //     $inventoryData = $query->get();
        //     return $inventoryData;
        // }
        // $inventoryData = $query->paginate(8);

     // Filter by product variant and warehouse if provided
        $seller =Auth::user() ;
        if ($productVariant && $warehouse) {
            $inventoryData =  StockSummary::where('variant_id', $productVariant)
                ->where('warehouse_id', $warehouse)->where('seller_id',$seller->id)->get();
        }
        else
            $inventoryData =  StockSummary::where('seller_id',$seller->id)->get();
        $warehouses = Warehouse::where('user_id',$seller->id)->get();

        return view('seller.stock.index', compact('inventoryData', 'warehouses'/* , 'sortField', 'sortDirection' */));
    }

    /**
     * Save Record of Stock Addition
     *
     * This function is responsible for creating a new stock summary record and a corresponding stock detail record.
     * It is typically used for adding new stock items to the inventory.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * Request Params:
     * - product_variant: The ID of the product variant to which the stock belongs.
     * - warehouse: The ID of the warehouse where the stock is stored.
     * - quantity: The quantity of the stock being added.
     * - comment: Additional comments regarding the stock transaction.
     *
     * The function performs the following actions:
     * 1. Validates the incoming request data.
     * 2. Extracts data from the request.
     * 3. Creates a new record in the stock_summary table.
     * 4. Calculates the 'before_quantity' for the stock_details record.
     * 5. Creates a new record in the stock_details table with appropriate details.
     * 6. Returns a redirect response with a success message.
     */
    public function saveRecord(Request $request)
    {

        // Validation rules
        $request->validate([
            'product_variant' => 'required',
            'warehouse' => 'required',
            'quantity' => 'required|integer|min:1',
            // Add more validation rules as needed
        ]);

        // Extract data from the request
        $productVariant = $request->input('product_variant');
        $warehouse = $request->input('warehouse');
        $quantity = $request->input('quantity');
        $comment = $request->input('comment');
        $seller_id =Auth::user()->id;


        // Save to stock_summary table
        $stockSummary = StockSummary::create([
            'variant_id' => $productVariant,
            'warehouse_id' => $warehouse,
            'current_total_quantity' => $quantity,
            'seller_id' =>$seller_id
        ]);

         // For the first record creation, the before quantity is always 0
        $beforeQuantity = $stockSummary->current_total_quantity - $quantity;

        // Save to stock_details table
        StockDetails::create([
            'operation_type' => 'Stock addition',
            'variant_id' => $productVariant,
            'warehouse_id' => $warehouse,
            // 'before_quantity' => \DB::raw("(SELECT current_total_quantity FROM stock_summaries WHERE variant_id = $productVariant AND warehouse_id = $warehouse) - $quantity"),
            // 'transaction_quantity' => $quantity,
            // 'after_quantity' => \DB::raw("(SELECT current_total_quantity FROM stock_summaries WHERE variant_id = $productVariant AND warehouse_id = $warehouse)"),
            'before_quantity' => $beforeQuantity,
            'transaction_quantity' => $quantity,
            'after_quantity' => $stockSummary->current_total_quantity,
            'user_comment' => $comment,
            'seller_id' =>$seller_id
        ]);

        // Redirect back or return a response
        return redirect()->back()->with('success', __('stock.inventory_record_saved'));
    }


    /**
     * Store Addition or Removal of Stock
     *
     * This function handles the addition or removal of stock for a product variant in a specific warehouse.
     * It updates the stock summary and records each stock transaction in the stock details table.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * Request Params:
     * - product_variant: The ID of the product variant.
     * - warehouse: The ID of the warehouse where the stock is stored.
     * - quantity: The quantity of stock to add or remove.
     * - comment: Additional comments regarding the stock transaction.
     * - addRemove: Specifies whether the operation is an addition ('add') or removal ('remove') of stock.
     */
    public function storeAddRemoveStock(Request $request)
    {

        // Validation rules
        $request->validate([
            'product_variant' => 'required',
            'warehouse' => 'required',
            'quantity' => 'required|integer|min:1',
            // Add more validation rules as needed
        ]);

        // Extract data from the request
        $productVariant = $request->input('product_variant');
        $warehouse = $request->input('warehouse');
        $quantity = $request->input('quantity');
        $comment = $request->input('comment');
        $seller_id =Auth::user()->id;

        // Determine operation type and perform stock addition or removal
        if ($request->addRemove == "add") {
            // Logic for adding stock
            $stockSummary = StockSummary::where([
                'variant_id' => $productVariant,
                'warehouse_id' => $warehouse,
                'seller_id' => $seller_id
            ])->first();

            if ($stockSummary) {
                // Update current_total_quantity
                $originalQuantity = $stockSummary->current_total_quantity;
                $stockSummary->current_total_quantity += $quantity;
                $stockSummary->save();

                // Save to stock_details table
                StockDetails::create([
                    'operation_type' => 'Stock addition',
                    'variant_id' => $productVariant,
                    'warehouse_id' => $warehouse,
                    'before_quantity' => $originalQuantity,
                    'transaction_quantity' => $quantity,
                    'after_quantity' => $stockSummary->current_total_quantity,
                    'user_comment' => $comment,
                    'seller_id' => $seller_id
                ]);
            }
        } else {
             // Logic for removing stock
            $stockSummary = StockSummary::where([
                'variant_id' => $productVariant,
                'warehouse_id' => $warehouse,
                'seller_id' => $seller_id
            ])->first();

            if ($stockSummary) {
                // Calculate before_quantity before the update
                $originalQuantity = $stockSummary->current_total_quantity;

                // Ensure that you're not reducing the quantity below zero
                $newQuantity = max($originalQuantity - $quantity, 0);
                $stockSummary->current_total_quantity = $newQuantity;
                $stockSummary->save();

                // Save to stock_details table
                StockDetails::create([
                    'operation_type' => 'Stock removal',
                    'variant_id' => $productVariant,
                    'warehouse_id' => $warehouse,
                    'before_quantity' => $originalQuantity,
                    'transaction_quantity' => $quantity,
                    'after_quantity' => $newQuantity,
                    'user_comment' => $comment,
                    'seller_id' => $seller_id
                ]);
            }
        }

        // Redirect back or return a response
        return redirect()->route('seller.stocks.index')->with('success', __('stock.inventory_record_saved'));
    }

    /**
     * Check Inventory Availability
     *
     * This function is used to check the availability of a product variant in a specific warehouse.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkInventory(Request $request)
    {
        // Retrieve product variant, warehouse, and seller ID from the request
        $productVariant = $request->input('product_variant');
        $warehouse = $request->input('warehouse');

        $seller_id =Auth::user()->id;
        // Check if the inventory entry exists for the given product variant, warehouse, and seller
        $exists = StockSummary::where('variant_id', $productVariant)
            ->where('warehouse_id', $warehouse)->where('seller_id',$seller_id)
            ->exists();
        // Return the result as a JSON response
        return response()->json(['exists' => $exists]);
    }

    // ExcelExportController.php

    public function export(Request $request)
    {
        // Apply filters and sorting to the query
        $query = $this->index($request, 'excel');
        // Export to Excel using Maatwebsite Excel
        return Excel::download(new StockSummaryExport($query), 'stock_summary.xlsx');
    }
    /**
     * Displays the stock operation report view for a seller.
     *
     * This method is responsible for preparing and showing the stock operation report
     * page for the authenticated seller. It retrieves a list of warehouses associated
     * with the seller to be used for filtering stock operations.
     *
     * @param Request $request The incoming request object.
     * @return \Illuminate\View\View The view for the stock operation report.
     */
    public function stockOperationReport(Request $request)
{
    // $fromDate = $request->input('from_date', now()->subMonths(3));
    // $toDate = $request->input('to_date', now());
    // $productVariants = $request->input('product_variants', []);
    // $warehouses = $request->input('warehouses', []);

    // $query = StockDetails::with(['productVariant', 'warehouse'])
    //                     ->whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);

    // if (!empty($productVariants)) {
    //     $query->whereIn('product_variant_id', $productVariants);
    // }

    // if (!empty($warehouses)) {
    //     $query->whereIn('warehouse_id', $warehouses);
    // }

    // $records = $query->orderBy('created_at', 'desc')->paginate(10);
     // Retrieve the authenticated user (seller).
    $seller =Auth::user() ;
    // Fetch all warehouses associated with the seller.
    $warehouses=Warehouse::where("user_id",$seller->id)->get() ;
    // Return the view for the stock operation report with the warehouses data.
    return view('seller.stock.stock_operation_report',compact('warehouses'));
}

/**
 * Searches and retrieves stock details based on specified criteria.
 *
 * This method allows a seller to search for stock details within a specified date range.
 * It also supports filtering by product variants and warehouses if provided in the request.
 *
 * @param Request $request The request object containing search criteria.
 * @return \Illuminate\View\View The view with the search results.
 */
public function searchStockDetails(Request $request) {
    // Set a date limit to 3 months ago from today.
    $threeMonthsAgo = Carbon::now()->subMonths(3)->startOfDay();

    // Validate request data.
    $request->validate([
        'from_date' => 'required|date|after_or_equal:' . $threeMonthsAgo->toDateString(),
        'to_date' => 'required|date|after_or_equal:from_date',
        'product_variants' => 'sometimes|array',
        'warehouses' => 'sometimes|array'
    ]);
    // Get the authenticated user (seller).
    $seller =Auth::user() ;

    // Initialize arrays to hold unique product variants and warehouses.
    $productVariants = [];
    $warehouses = [];
    // Retrieve stock details within the specified date range for this seller.
    $stockDetails = StockDetails::with('productVariant', 'warehouse')->where('seller_id',$seller->id)
                        ->whereBetween('created_at', [$request->from_date . ' 00:00:00', $request->to_date . ' 23:59:59'])->get();
     // Loop through stock details to collect unique product variants and warehouses.
    foreach ($stockDetails as $detail) {
            if ($detail->productVariant && !in_array($detail->productVariant, $productVariants, true)) {
                $productVariants[] = $detail->productVariant;
            }
            if ($detail->warehouse && !in_array($detail->warehouse, $warehouses, true)) {
                $warehouses[] = $detail->warehouse;
            }
        }
    // Create a query to fetch stock details with potential filters applied.
    $query = StockDetails::with('productVariant', 'warehouse')->where('seller_id',$seller->id)
                        ->whereBetween('created_at', [$request->from_date . ' 00:00:00', $request->to_date . ' 23:59:59']);
    // Apply product variant filter if provided.

    if ($request->has('product_variants')) {
        $query->whereIn('variant_id', $request->product_variants);
    }
    // Apply warehouse filter if provided.

    if ($request->has('warehouses')) {
        $query->whereIn('warehouse_id', $request->warehouses);
    }

    // $records = $query->paginate(8);

     // Fetch the filtered records.
    $records = $query->get();

    // $warehouses=Warehouse::where("user_id",$seller->id)->get() ;

    // Return the view with the data required for the stock operation report.
    return view('seller.stock.stock_operation_report', compact('records',/* 'warehouses', */'productVariants','warehouses'));
}


}
