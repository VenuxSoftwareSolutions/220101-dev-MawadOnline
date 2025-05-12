<?php

namespace App\Http\Controllers\Seller;

use Auth;
use Excel;
use Validator;
use App\Models\Tour;
use App\Models\User;
use App\Models\Emirate;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\StockDetails;
use App\Models\StockSummary;
use Illuminate\Http\Request;
use App\Exports\StockSummaryExport;
use App\Http\Requests\SaveRecordRequest;
use App\Http\Requests\SearchStockRequest;

class StockController extends Controller
{
    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:seller_add_inventory'])->only('index');
        $this->middleware(['permission:seller_edit_or_remove_inventory'])->only('storeAddRemoveStock');
        $this->middleware(['permission:seller_inventory_history'])->only('stockOperationReport');
    }

    public function index(Request $request, $type = null)
    {
        seller_lease_creation($user = Auth::user());

        // Filter by product variant and warehouse if provided
        $productVariant = $request->input('productVariant');
        $warehouse_id = $request->input('warehouse');

        $seller = User::find(Auth::user()->owner_id);

        $is_vendor_warehouse_exists = Warehouse::where('id', $warehouse_id)
            ->where('user_id', $seller->id)
            ->exists();

        if ($warehouse_id && !$is_vendor_warehouse_exists) {
            return back()->withErrors(['error' => __('Invalid warehouse.')]);
        }

        if ($productVariant && $warehouse_id) {
            $inventoryData = StockSummary::where('variant_id', $productVariant)
                ->where('warehouse_id', $warehouse_id)
                ->where('seller_id', $seller->id)
                ->where("current_total_quantity", ">", 0)
                ->orderBy('updated_at', 'desc')
                ->get();
        } else {
            $inventoryData = StockSummary::where('seller_id', $seller->id)
                ->where("current_total_quantity", ">", 0)
                ->orderBy('updated_at', 'desc')
                ->get();
        }

        $warehouses = Warehouse::where('user_id', $seller->id)->get();
        $products = Product::where('is_parent', 0)
            ->where('is_draft', 0)
            ->where('user_id', $seller->id)
            ->get();

        $tour_steps = Tour::orderBy('step_number')->get();

        return view('seller.stock.index', compact('inventoryData', 'warehouses', 'products', 'tour_steps'));
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
        seller_lease_creation($user = Auth::user());

        // Retrieve the authenticated user (seller).
        $seller = User::find(Auth::user()->owner_id);
        //$seller = Auth::user();
        // Fetch all warehouses associated with the seller.
        $warehouses = Warehouse::where("user_id", $seller->id)->get();
        // Return the view for the stock operation report with the warehouses data.
        $tour_steps = Tour::orderBy('step_number')->get();
        return view('seller.stock.stock_operation_report', compact('warehouses', 'tour_steps'));
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
        $productVariants = $stockDetails->pluck('productVariant')->unique()->values();
        $warehouses = $stockDetails->pluck('warehouse')->unique()->values();

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
        $tour_steps = Tour::orderBy('step_number')->get();
        // Return the view with the data required for the stock operation report.
        return view('seller.stock.stock_operation_report', compact('records', /* 'warehouses', */ 'productVariants', 'warehouses', 'tour_steps'));
    }

    public function warehouses()
    {
        $seller = User::find(Auth::user()->owner_id);
        $warehouses = Warehouse::where('user_id', $seller->id)->get();
        $emirates = Emirate::all();
        $tour_steps = Tour::orderBy('step_number')->get();
        return view('seller.warehouses.index', compact('warehouses', 'emirates', 'tour_steps'));
    }

    public function removeWarehouse(Request $request)
    {

        $warehouseId = $request->input('warehouse_id');

        // Find and delete the warehouse
        $warehouse = Warehouse::findOrFail($warehouseId);
        // Check if the warehouse has associated products
        if ($warehouse->checkWhHasProducts()) {
            return response()->json(['error' => __('stock.Warehouse has associated products. Cannot delete')]);
        }
        $warehouse->delete();

        return response()->json(['message' => __('stock.Warehouse removed successfully')]);
    }

    public function storeWarehouses(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'warehouse_name.*' => 'required|max:128|regex:/\D/',
            'state_warehouse.*' => 'required|max:128',
            'area_warehouse.*' => 'required|max:128',
            'street_warehouse.*' => 'required|max:128|regex:/\D/',
            'building_warehouse.*' => 'required|max:64|regex:/\D/',
            'unit_warehouse.*' => 'nullable|max:64',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();

        foreach ($request->warehouse_name as $key => $warehouse_name) {
            if (!empty($request->warehouse_id[$key])) {
                $warehouse = Warehouse::findOrFail($request->warehouse_id[$key]);

                $warehouse->update([
                    'warehouse_name' => $warehouse_name,
                    'emirate_id' => $request->state_warehouse[$key],
                    'area_id' => $request->area_warehouse[$key],
                    'address_street' => $request->street_warehouse[$key],
                    'address_building' => $request->building_warehouse[$key],
                    'address_unit' => $request->unit_warehouse[$key],
                    'saveasdraft' => isset($request->saveasdraft[$key]) ? true : false,
                ]);
            } else {
                Warehouse::create([
                    'user_id' => $user->id,
                    'warehouse_name' => $warehouse_name,
                    'emirate_id' => $request->state_warehouse[$key],
                    'area_id' => $request->area_warehouse[$key],
                    'address_street' => $request->street_warehouse[$key],
                    'address_building' => $request->building_warehouse[$key],
                    'address_unit' => $request->unit_warehouse[$key],
                    'saveasdraft' => isset($request->saveasdraft[$key]) ? true : false,
                ]);
            }
        }

        return redirect()->back()
            ->with('success', __('Warehouses saved successfully'));
    }


}
