<?php

namespace App\Http\Controllers\Seller;
use Auth;
use App\Models\Tour;
use App\Models\Discount;
use App\Models\Category;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index(Request $request)
    {
        {
            $query = Discount::where('user_id', Auth::id())->with('product');;
        
            /*if ($request->has('scope')) {
                $scope = $request->input('scope');
                if ($scope == 'category') {
                    $query->where('scope', 'category');
                } elseif ($scope == 'product') {
                    $query->where('scope', 'product');
                } 
            }*/
            $discounts = $query->get();
            $scope = 'product';

            return view('seller.promotions.index', compact('discounts', 'scope'));
        }
        
    }
    public function create()
    {
        $categories = Category::all();
        $products = Product::where('user_id', Auth::id())->get();

        return view('seller.promotions.create', compact('categories', 'products'));
    }
    public function store(Request $request)
    {

        //we will put this validation in a separae form request classes 
        $request->validate([
            'discountType' => 'required|string',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
            'scope' => 'required|string|in:product,category,order_over_amount,all_orders',
            'percent' => 'required|numeric|min:0|max:100',
            'maxDiscount' => 'nullable|numeric|min:0',
            'product_id' => 'required_if:scope,product|exists:products,id',
            'category_id' => 'required_if:scope,category|exists:categories,id',
            'order_amount' => 'required_if:scope,ordersOverAmount|numeric|min:0',
        ]);
        $discount = new Discount();
        $discount->user_id = Auth::id();
        $discount->scope = $request->input('scope');
        $discount->start_date = $request->input('startDate');
        $discount->end_date = $request->input('endDate');
        $discount->discount_percentage = $request->input('percent');
        $discount->max_discount = $request->input('maxDiscount');
        $discount->status = 'active';

        switch ($request->input(key: 'scope')) {
            case 'product':
                $discount->product_id = $request->input('product_id');
                break;
            case 'category':
                $discount->category_id = $request->input('category_id');
                break;
            case 'ordersOverAmount':
                $discount->min_order_amount = $request->input('order_amount');
                break;
            case 'all_orders':
                
                break;
        }

        $discount->save();

        return redirect()->route('seller.discounts.index')->with('success', 'Discount created successfully.');
    }

    public function edit($id)
    {
        $discount = Discount::findOrFail($id);
        return response()->json($discount);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
        ]);

        $discount = Discount::findOrFail($id);
        $discount->start_date = $request->input('startDate');
        $discount->end_date = $request->input('endDate');
        $discount->save();

        return response()->json(['success' => true, 'message' => 'Discount updated successfully.']);
    }
    public function destroy($id)
    {
        $discount = Discount::findOrFail($id);
        $discount->delete();
        return response()->json(data: ['success' => true, 'message' => 'Discount deleted successfully.']);
    }





}
