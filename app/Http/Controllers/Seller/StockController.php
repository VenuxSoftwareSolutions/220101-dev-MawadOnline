<?php

namespace App\Http\Controllers\Seller;

use App\Exports\StockSummaryExport;
use App\Http\Requests\SaveRecordRequest;
use App\Http\Requests\SearchStockRequest;
use App\Models\Product;
use App\Models\StockDetails;
use App\Models\StockSummary;
use App\Models\User;
use App\Models\Warehouse;
use Auth;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:seller_add_inventory'])->only('index');
        $this->middleware(['permission:seller_edit_or_remove_inventory'])->only('storeAddRemoveStock');
        $this->middleware(['permission:seller_inventory_history'])->only('stockOperationReport');
    }

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
        seller_lease_creation($user=Auth::user());

        // Filter by product variant and warehouse if provided
        $productVariant = $request->input('productVariant');
        $warehouse = $request->input('warehouse');
        $seller = User::find(Auth::user()->owner_id);
        //$seller = Auth::user();

        // Check if productVariant and warehouse are provided and exist
        // if ($productVariant && !ProductVariant::where('id', $productVariant)->exists()) {
        //     return back()->withErrors(['error' => 'Invalid product variant.']);
        // }

        if ($warehouse && !Warehouse::where('id', $warehouse)->where('user_id', $seller->id)->exists()) {
            return back()->withErrors(['error' => 'Invalid warehouse.']);
        }

        if ($productVariant && $warehouse) {
            $inventoryData =  StockSummary::where('variant_id', $productVariant)
                ->where('warehouse_id', $warehouse)->where('seller_id', $seller->id)->get();
        } else {
            $inventoryData =  StockSummary::where('seller_id', $seller->id)->get();
        }
        $warehouses = Warehouse::where('user_id', $seller->id)->get();
        $products = Product::where('is_parent', 0)   // Filter products where 'is_parent' column is 0
        ->where('is_draft', 0)                   // Filter products where 'is_draft' column is 0
        ->where('approved', 1)                   // Filter products where 'approved' column is 1
        ->where('user_id', $seller->id)          // Filter products where 'user_id' column matches the seller's id
        ->get();


        return view('seller.stock.index', compact('inventoryData', 'warehouses','products'));
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
    public function saveRecord(SaveRecordRequest $request)
    {

        $seller_id = Auth::user()->owner_id;

        // Extract and rename specific data from the request
        $stockSummaryData = $request->only(['product_variant', 'warehouse', 'quantity']);
        $stockSummaryData = [
            'variant_id' => $stockSummaryData['product_variant'],
            'warehouse_id' => $stockSummaryData['warehouse'],
            'current_total_quantity' => $stockSummaryData['quantity'],
            'seller_id' => $seller_id
        ];

        // Save to stock_summary table
        $stockSummary = StockSummary::create($stockSummaryData);

        // Prepare data for stock_details
        $stockDetailsData = [
            'operation_type' => StockDetails::OPERATION_TYPE_ADDITION, // For stock addition
            'variant_id' => $request->product_variant,
            'warehouse_id' => $request->warehouse,
            'before_quantity' => 0,
            'transaction_quantity' => $request->quantity,
            'after_quantity' => $stockSummary->current_total_quantity,
            'user_comment' => $request->comment,
            'seller_id' => $seller_id
        ];

        // Save to stock_details table
        StockDetails::create($stockDetailsData);

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
    public function storeAddRemoveStock(SaveRecordRequest $request)
    {

        // Extract data from the request
        $productVariant = $request->input('product_variant');
        $warehouse = $request->input('warehouse');
        $quantity = $request->input('quantity');
        $comment = $request->input('comment');
        $seller_id = Auth::user()->owner_id;

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
                    'operation_type' => StockDetails::OPERATION_TYPE_ADDITION, // For stock addition
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
                    'operation_type' => StockDetails::OPERATION_TYPE_REMOVAL, // For stock removal
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

        $seller_id = Auth::user()->owner_id;
        // Check if the inventory entry exists for the given product variant, warehouse, and seller
        $exists = StockSummary::where('variant_id', $productVariant)
            ->where('warehouse_id', $warehouse)->where('seller_id', $seller_id)
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
        seller_lease_creation($user=Auth::user());

        // Retrieve the authenticated user (seller).
        $seller = User::find(Auth::user()->owner_id);
        //$seller = Auth::user();
        // Fetch all warehouses associated with the seller.
        $warehouses = Warehouse::where("user_id", $seller->id)->get();
        // Return the view for the stock operation report with the warehouses data.
        return view('seller.stock.stock_operation_report', compact('warehouses'));
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
    public function searchStockDetails(SearchStockRequest $request)
    {

        // Get the authenticated user (seller).
        $seller = User::find(Auth::user()->owner_id);
        //$seller = Auth::user();

        // Retrieve stock details within the specified date range for this seller.
        $stockDetails = StockDetails::with('productVariant', 'warehouse')->where('seller_id', $seller->id)
            ->whereBetween('created_at', [$request->from_date . ' 00:00:00', $request->to_date . ' 23:59:59'])->get();
        // Use collections to get unique product variants and warehouses
        $productVariants= $stockDetails->pluck('productVariant')->unique()->values();
        $warehouses=$stockDetails->pluck('warehouse')->unique()->values() ;

        // Create a query to fetch stock details with potential filters applied.
        $query = StockDetails::with('productVariant', 'warehouse')->where('seller_id', $seller->id)
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
        return view('seller.stock.stock_operation_report', compact('records',/* 'warehouses', */ 'productVariants', 'warehouses'));
    }
}
