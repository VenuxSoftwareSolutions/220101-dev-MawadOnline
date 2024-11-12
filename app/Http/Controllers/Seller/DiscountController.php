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
        $isCoupon = $request->route()->uri === 'vendor/coupons';
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
        return view('seller.promotions.index', compact('discounts', 'scope', 'columnHeader', 'columnValue', 'isCoupon'));
    }
    public function create()
    {
        $nestedCategories = Category::all();

        $products = Product::where('user_id', Auth::id())->with('categories')->get();

        $productCategoryIds = $products->map(function ($product) {
            return $product->categories->pluck('id');
        })->flatten()->unique();

        $categories = Category::whereIn('id', $productCategoryIds)
            ->whereNotIn('id', function ($query) {
                $query->select('parent_id')->from('categories')->whereNotNull('parent_id');
            })
            ->get();

        $nestedCategories = $this->buildTree($nestedCategories);

        return view('seller.promotions.create', compact('categories', 'products', 'nestedCategories'));
    }

    public function store(DiscountStoreRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['user_id'] = auth()->id();

        if ($validatedData['scope'] === 'product' && empty($validatedData['product_id'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product ID is required when scope is product.'
            ], 422);
        }

        if ($validatedData['scope'] === 'category' && empty($validatedData['category_id'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Category ID is required when scope is category.'
            ], 422);
        }

        $overlappingDiscounts = Discount::checkForOverlappingDiscounts($validatedData);

        if (!$request->has('ignore_overlap') || !$request->ignore_overlap) {
            if (count($overlappingDiscounts) > 0) {
                return response()->json([
                    'status' => 'overlap',
                    'overlappingDiscounts' => $overlappingDiscounts,
                ]);
            }
        }

        Discount::create($validatedData);
        $scope = $validatedData['scope'];

        return response()->json([
            'status' => 'success',
            'redirectUrl' => route('seller.discounts.index', ['scope' => $scope]),
            'message' => 'Discount created successfully.'
        ]);
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

    private function buildTree($categories, $parentId = 0, $path = "")
    {
        $branch = [];

        foreach ($categories as $category) {
            if ($category->parent_id == $parentId) {
                $newPath = $path ? "$path/{$category->name}" : $category->name;
                $category->path = $newPath;

                $children = $this->buildTree($categories, $category->id, $newPath);
                $category->isLeaf = !is_array($children) || count($children) === 0;
                if ($children) {
                    $category->children = $children;
                }

                $branch[] = $category;
            }
        }

        return $branch;
    }
    public function getProductsByCategory(Request $request)
    {
        $categoryId = $request->query('category_id');

        $products = Product::where('user_id', Auth::id())
            ->whereHas('categories', function ($query) use ($categoryId) {
                $query->where('id', $categoryId);
            })
            ->get();

        return response()->json(['products' => $products]);
    }

    public function getCategoriesForProductScope()
    {
        $products = Product::where('user_id', Auth::id())->get();
        $productCategoryIds = $products->pluck('categories')->flatten()->pluck('id')->unique();

        $categories = Category::whereIn('id', $productCategoryIds)
            ->orWhereIn('id', function ($query) use ($productCategoryIds) {
                $query->select('parent_id')
                    ->from('categories')
                    ->whereIn('id', $productCategoryIds);
            })
            ->get();

        return response()->json(['categories' => $categories]);
    }

}

