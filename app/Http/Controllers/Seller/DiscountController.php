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
    {
        $scope = $request->query('scope', 'product'); 
        $discounts = Discount::where('scope', $scope)->get();
        $columnHeader = '';
        $columnValue = '';
        switch ($scope) {
            case 'product':
                $columnHeader = 'Product Name';
                $columnValue = fn($discount) => $discount->product ? $discount->product->name : 'N/A';
                break;
            case 'category':
                $columnHeader = 'Category';
                $columnValue = fn($discount) => $discount->category ? $discount->category->name : 'N/A';
                break;
            case 'ordersOverAmount':
                $columnHeader = 'Minimum Amount';
                $columnValue = fn($discount) => $discount->min_order_amount ?? 'N/A';
                break;
            case 'allOrders':
                $columnHeader = 'All Orders';
                $columnValue = fn($discount) => '-'; 
                break;
            default:
                $columnHeader = 'Product Name';
                $columnValue = fn($discount) => $discount->product ? $discount->product->name : 'N/A';
                break;
        }
        return view('seller.promotions.index', compact('discounts', 'scope', 'columnHeader', 'columnValue'));
    }

    public function create()
    {
        $categories = Category::all();
        $products = Product::where('user_id', Auth::id())->get();
        $nestedCategories = $this->buildTree($categories);
        $formattedCategories = $this->formatCategories($nestedCategories);
        
        return view('seller.promotions.create', compact('categories', 'products','formattedCategories'));
    }

    public function store(DiscountStoreRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['user_id'] = auth()->id();
        Discount::create($validatedData);
        $scope = $validatedData['scope'];
        return redirect()->route('seller.discounts.index', ['scope' => $scope])
        ->with('success', 'Discount created successfully.');
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
    private function buildTree($categories, $parentId = 0)
    {
        $branch = [];

        foreach ($categories as $category) {
            if ($category->parent_id == $parentId) {
                $children = $this->buildTree($categories, $category->id);
                if ($children) {
                    $category->children = $children;
                }
                $branch[] = $category;
            }
        }

        return $branch;
    }   
    public function formatCategories($categories) {
        return array_map(function ($category) {
            return [
                'id' => $category['id'],
                'text' => $category['name'], 
                'children' => isset($category['children']) ? $this->formatCategories($category['children']) : []
            ];
        }, $categories);
    }
        
}

