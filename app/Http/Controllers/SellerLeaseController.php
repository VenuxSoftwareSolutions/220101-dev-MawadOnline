<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use App\Models\Tour;
use App\Models\SellerLease;
use Illuminate\Http\Request;
use App\Models\SellerLeaseDetail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class SellerLeaseController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:seller_view_all_leases'])->only('index');
        $this->middleware(['permission:seller_view_all_sales'])->only('allSales');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        seller_lease_creation($user=Auth::user());
        if($request->has('display_flash') && $request->display_flash == true)
        {
            Session::flash('message', trans('lease.payment_completed_successfully'));
        }
        // ($request->has('display_flash') && $request->display_flash == true)?:Session::flash('message', 'This is a message!');
        $currentDate = Carbon::now();
        $startDay = (clone $currentDate);
        $endDay = (clone $currentDate)->subDay(1);

        // Retrieve the lease where the current date is between start_date and end_date
        $current_lease = SellerLease::where('vendor_id',Auth::user()->owner_id)->where('start_date', '<=', $startDay)
            ->where('end_date', '>=', $endDay)->first();
        $current_details=SellerLeaseDetail::where('lease_id',$current_lease->id)->get();
        $leases = SellerLease::where('vendor_id',Auth::user()->owner_id)
            ->latest() // Order by the latest leases
            ->take(4)  // Take the latest four leases
            ->get();
        // Remove the first element, as it's the current lease
        $leases = $leases->splice(1);
        //$leases=SellerLease::where('vendor_id',Auth::user()->owner_id)->get();
        $tour_steps=Tour::orderBy('step_number')->get();
        return view('seller.lease',compact('current_lease','current_details','leases','tour_steps','user'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SellerLease  $sellerLease
     * @return \Illuminate\Http\Response
     */
    public function show(SellerLease $sellerLease)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SellerLease  $sellerLease
     * @return \Illuminate\Http\Response
     */
    public function edit(SellerLease $sellerLease)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SellerLease  $sellerLease
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SellerLease $sellerLease)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SellerLease  $sellerLease
     * @return \Illuminate\Http\Response
     */
    public function destroy(SellerLease $sellerLease)
    {
        //
    }
    public function allSales()
    {
        seller_lease_creation($user=Auth::user());
        $step=12;
        $tour_steps=Tour::orderBy('step_number')->get();
        return view('seller.coming_soon',compact('step','tour_steps'));
    }
}
