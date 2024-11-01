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
        
        return view('seller.promotions.index');

    }
    public function create()
    {
        return view('seller.promotions.create');
    }


}
