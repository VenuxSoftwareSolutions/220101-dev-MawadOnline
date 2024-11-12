<?php

namespace App\Http\Controllers\Seller;

use Auth;
use App\Models\Tour;
use App\Models\Coupon;
use App\Models\Category;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Coupons\CouponStoreRequest;
use App\Http\Requests\Coupons\CouponUpdateRequest;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $scope = $request->query('scope', 'product');
        $isCoupon = $request->route()->uri === 'vendor/coupons';
        $coupons = Coupon::where('scope', $scope)->get();
        $columnHeader = '';
        $columnValue = '';

        switch ($scope) {
            case 'product':
                $columnHeader = 'Product Name';
                $columnValue = fn($coupon) => $coupon->product ? $coupon->product->name : 'N/A';
                break;
            case 'category':
                $columnHeader = 'Category';
                $columnValue = fn($coupon) => $coupon->category ? $coupon->category->name : 'N/A';
                break;
            case 'ordersOverAmount':
                $columnHeader = 'Minimum Amount';
                $columnValue = fn($coupon) => $coupon->min_order_amount ?? 'N/A';
                break;
            case 'allOrders':
                $columnHeader = 'All Orders';
                $columnValue = fn($coupon) => '-';
                break;
            default:
                $columnHeader = 'Product Name';
                $columnValue = fn($coupon) => $coupon->product ? $coupon->product->name : 'N/A';
                break;
        }

        return view('seller.promotions.index', compact('coupons', 'scope', 'columnHeader', 'columnValue','isCoupon'));
    }

    public function create()
    {
        $categories = Category::all();
        $products = Product::where('user_id', Auth::id())->get();
        $nestedCategories = $this->buildTree($categories);
        return view('seller.promotions.create', compact('categories', 'products', 'nestedCategories'));
    }

    public function store(CouponStoreRequest $request) 
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
    
        if (!$request->has('ignore_overlap') || !$request->ignore_overlap) {
            $overlappingCoupons = Coupon::checkForOverlappingCoupons($validatedData);
    
            if (count($overlappingCoupons) > 0) {
                return response()->json([
                    'status' => 'overlap',
                    'overlappingCoupons' => $overlappingCoupons,
                ]);
            }
        }
    
        Coupon::create($validatedData);
        $scope = $validatedData['scope'];
    
        return response()->json([
            'status' => 'success',
            'redirectUrl' => route('seller.coupons.index', ['scope' => $scope]),
            'message' => 'Coupon created successfully.'
        ]);
    }
    

    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return response()->json($coupon);
    }

    public function update(CouponUpdateRequest  $request, $id)
    {
        $validatedData = $request->validated();
        $coupon = Coupon::findOrFail($id);
        $coupon->update($validatedData);
        return response()->json(['success' => true, 'message' => 'Coupon updated successfully.']);
    }

    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();
        return response()->json(['success' => true, 'message' => 'Coupon deleted successfully.']);
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
