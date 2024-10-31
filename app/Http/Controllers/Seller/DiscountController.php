<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index(Request $request)
    {
        return view('seller.coming_soon',compact('step','tour_steps'));

    }

}
