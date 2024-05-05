<?php

namespace App\Http\Controllers\Seller;

use DB;
use Auth;
use Carbon\Carbon;
use App\Models\Tour;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\SellerLease;
use App\Models\SellerPackage;
use App\Models\SellerLeaseDetail;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->first_login == false && Auth::user()->id == Auth::user()->owner_id) {
            $user=Auth::user();
            $user->first_login=true;
            $user->save();
            return view('seller.welcome');
        }
        else {
        seller_lease_creation($user=Auth::user());


        $tour_steps=Tour::orderBy('step_number')->get();
        $data['products'] = Product::where('user_id', Auth::user()->owner_id)->orderBy('num_of_sale', 'desc')->limit(12)->get();
        $data['last_7_days_sales'] = Order::where('created_at', '>=', Carbon::now()->subDays(7))
                                ->where('seller_id', '=', Auth::user()->owner_id)
                                ->where('delivery_status', '=', 'delivered')
                                ->select(DB::raw("sum(grand_total) as total, DATE_FORMAT(created_at, '%d %b') as date"))
                                ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))
                                ->get()->pluck('total', 'date');

        return view('seller.dashboard', $data , compact('tour_steps'));
        }

    }
    public function updateTour()
    {
        $user=Auth::user();
        $user->tour = 1 ;
        $user->save();
        return response()->json(['message' => 'done']);;

    }
}
