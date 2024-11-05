<?php

namespace App\Http\Controllers\Seller;
use Auth;
use App\Models\Tour;
use App\Models\Discount;
use App\Models\Category;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Discounts\DiscountStoreRequest;
use App\Http\Requests\Discounts\DiscountUpdateRequest;


class DiscountController extends Controller
{
    public function index(Request $request)
    { {
            $query = Discount::where('user_id', Auth::id())->with('product');
            ;

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

    public function store(DiscountStoreRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['user_id'] = auth()->id();
        Discount::create($validatedData);
        return redirect()->route('seller.discounts.index')->with('success', 'Discount created successfully.');
    }

    public function edit($id)
    {
        $discount = Discount::findOrFail($id);
        return response()->json($discount);
    }
    public function update(DiscountUpdateRequest $request, $id)
    {
        $validatedData = $request->validated();
        $discount = Discount::findOrFail($id);
        $discount->update($validatedData);
        return response()->json(['success' => true, 'message' => 'Discount updated successfully.']);
    }

    public function destroy($id)
    {
        $discount = Discount::findOrFail($id);
        $discount->delete();
        return response()->json(data: ['success' => true, 'message' => 'Discount deleted successfully.']);
    }

}
