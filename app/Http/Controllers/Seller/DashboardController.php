<?php

namespace App\Http\Controllers\Seller;

use DB;
use Auth;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Product;
use App\Models\SellerLease;
use App\Models\SellerPackage;

class DashboardController extends Controller
{
    public function index()
    {
        seller_lease_creation($user=Auth::user());

        $data['products'] = filter_products(Product::where('user_id', Auth::user()->owner_id)->orderBy('num_of_sale', 'desc'))->limit(12)->get();
        $data['last_7_days_sales'] = Order::where('created_at', '>=', Carbon::now()->subDays(7))
                                ->where('seller_id', '=', Auth::user()->owner_id)
                                ->where('delivery_status', '=', 'delivered')
                                ->select(DB::raw("sum(grand_total) as total, DATE_FORMAT(created_at, '%d %b') as date"))
                                ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))
                                ->get()->pluck('total', 'date');

        return view('seller.dashboard', $data);
    }
}
