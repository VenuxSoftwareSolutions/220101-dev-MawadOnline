<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Auth;

class ReviewController extends Controller
{
    // public function __construct()
    // {
    //     // Staff Permission Check
    //     $this->middleware(['permission:view_product_reviews'])->only('index');
    //     $this->middleware(['permission:publish_product_review'])->only('updatePublished');
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $reviews = Review::query();
        if ($request->rating) {
            $reviews->orderBy('rating', explode(',', $request->rating)[1]);
        }
        $reviews = $reviews->orderBy('created_at', 'desc')->paginate(15);
        return view('backend.product.reviews.index', compact('reviews'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'comment' => 'required|string|max:255',
        ]);
        DB::transaction(function () use ($request) {
            $review = new Review();
            $review->product_id = $request->product_id;
            $review->user_id = Auth::user()->id;
            $review->rating = $request->rating;
            $review->comment = $request->comment;
            $review->name = Auth::user()->name;
            $review->status = 0;
            $review->viewed = '0';
            $review->save();
            $product = Product::where('id', $request->product_id)->lockForUpdate()->firstOrFail();
            $approvedReviewsCount = Review::where('product_id', $product->id)->where('status', 1)->count();
            if ($approvedReviewsCount > 0) {
                $totalRating = Review::where('product_id', $product->id)->where('status', 1)->sum('rating');
                $product->rating = $totalRating / $approvedReviewsCount;
            } else {
                $product->rating = 0;
            }
            $product->save();

            if ($product->added_by == 'seller') {
                $seller = $product->user->shop;
                $approvedSellerReviews = Review::whereIn('product_id', $seller->products->pluck('id'))
                    ->where('status', 1)
                    ->get();

                if ($approvedSellerReviews->count() > 0) {
                    $seller->rating = $approvedSellerReviews->sum('rating') / $approvedSellerReviews->count();
                } else {
                    $seller->rating = 0;
                }
                $seller->num_of_reviews = $approvedSellerReviews->count();
                $seller->save();
            }
        });
        flash(translate('Review has been submitted successfully'))->success();
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function updatePublished(Request $request)
    {
        DB::transaction(function () use ($request) {
   
            $review = Review::findOrFail($request->id);
            $review->status = $request->status;
            $review->save();

            $product = Product::where('id', $review->product_id)->lockForUpdate()->firstOrFail();
            $approvedReviewsCount = Review::where('product_id', $product->id)
            ->where('status', 1)
            ->count();

            if ($approvedReviewsCount > 0) {
                $totalRating = Review::where('product_id', $product->id)
                    ->where('status', 1)
                    ->sum('rating');
                $product->rating = $totalRating / $approvedReviewsCount;
            } else {
                $product->rating = 0;
            }
           $product->save();

            if ($product->added_by == 'seller') {
                $seller = $product->user->shop;
                if ($review->status) {
                    $seller->rating = ($seller->rating * $seller->num_of_reviews + $review->rating) / ($seller->num_of_reviews + 1);
                    $seller->num_of_reviews += 1;
                } else {
                    $seller->rating = ($seller->rating * $seller->num_of_reviews - $review->rating) / max(1, $seller->num_of_reviews - 1);
                    $seller->num_of_reviews -= 1;
                }

                $seller->save();
            }
        });

        return 1;
    }

    public function product_review_modal(Request $request)
    {
        $product = Product::where('id', $request->product_id)->first();
        $review = Review::where('user_id', Auth::user()->id)
            ->where('product_id', $product->id)
            ->first();
        return view('frontend.user.product_review_modal', compact('product', 'review'));
    }
}
