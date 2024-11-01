<?php

namespace App\Http\Controllers\Seller;
use Auth;
use App\Models\Tour;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index(Request $request)
    {
        seller_lease_creation($user=Auth::user());
        $step=3;
        $tour_steps=Tour::orderBy('step_number')->get();
        return view('seller.coming_soon',compact('step','tour_steps'));

    }

}
