<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PayoutNotification;
use App\Models\SellerWithdrawRequest;
use App\Models\User;
use Auth;

class SellerWithdrawRequestController extends Controller
{
    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:seller_view_withdraw_requests'])->only('index');
        $this->middleware(['permission:seller_money_withdraw_request'])->only('store');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $seller_withdraw_requests = SellerWithdrawRequest::where('user_id', Auth::user()->owner_id)->latest()->paginate(9);
        return view('seller.money_withdraw_requests.index', compact('seller_withdraw_requests'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $seller_withdraw_request = new SellerWithdrawRequest;
        $seller_withdraw_request->user_id = Auth::user()->owner_id;
        $seller_withdraw_request->amount = $request->amount;
        $seller_withdraw_request->message = $request->message;
        $seller_withdraw_request->status = '0';
        $seller_withdraw_request->viewed = '0';
        if ($seller_withdraw_request->save()) {

            $users = User::findMany([auth()->user()->id, User::where('user_type', 'admin')->first()->id]);
            $vendor = User::find(Auth::user()->owner_id);
            Notification::send($users, new PayoutNotification($vendor, $request->amount, 'pending'));

            flash(translate('Request has been sent successfully'))->success();
            return redirect()->route('seller.money_withdraw_requests.index');
        }
        else{
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }
}
