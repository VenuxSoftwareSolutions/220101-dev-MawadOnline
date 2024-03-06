<?php

namespace App\Http\Controllers;

use App\Models\Emirate;
use Illuminate\Http\Request;
use App\Models\Seller;
use App\Models\User;
use App\Models\Shop;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\VendorStatusHistory;
use App\Notifications\CustomStatusNotification;
use Illuminate\Support\Facades\Hash;
use App\Notifications\EmailVerificationNotification;
use App\Notifications\ShopVerificationNotification;
use App\Notifications\VendorStatusChangedNotification;
use Cache;
use File;
use Illuminate\Support\Facades\Notification;

class SellerController extends Controller
{
    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:view_all_seller'])->only('index');
        $this->middleware(['permission:view_seller_profile'])->only('profile_modal');
        $this->middleware(['permission:login_as_seller'])->only('login');
        $this->middleware(['permission:pay_to_seller'])->only('payment_modal');
        $this->middleware(['permission:edit_seller'])->only('edit');
        $this->middleware(['permission:delete_seller'])->only('destroy');
        $this->middleware(['permission:ban_seller'])->only('ban');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $sort_search = null;
        // $approved = null;
        // $shops = Shop::whereIn('user_id', function ($query) {
        //     $query->select('id')
        //         ->from(with(new User)->getTable());
        // })->latest();

        // if ($request->has('search')) {
        //     $sort_search = $request->search;
        //     $user_ids = User::where('user_type', 'seller')->where(function ($user) use ($sort_search) {
        //         $user->where('name', 'like', '%' . $sort_search . '%')->orWhere('email', 'like', '%' . $sort_search . '%');
        //     })->pluck('id')->toArray();
        //     $shops = $shops->where(function ($shops) use ($user_ids) {
        //         $shops->whereIn('user_id', $user_ids);
        //     });
        // }
        // if ($request->approved_status != null) {
        //     $approved = $request->approved_status;
        //     $shops = $shops->where('verification_status', $approved);
        // }
        // $shops = $shops->paginate(15);
        // return view('backend.sellers.index', compact('shops', 'sort_search', 'approved'));
        $sellers = User::where('user_type', 'seller')->whereColumn('id','owner_id')->get() ;
        return view('backend.sellers.index', compact('sellers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.sellers.create');
    }

    public function showStaff(User $seller) {
        $staff = $seller->getStaff;
        return view('backend.sellers.staff', compact('seller', 'staff'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (User::where('email', $request->email)->first() != null) {
            flash(translate('Email already exists!'))->error();
            return back();
        }
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->user_type = "seller";
        $user->password = Hash::make($request->password);

        if ($user->save()) {
            if (get_setting('email_verification') != 1) {
                $user->email_verified_at = date('Y-m-d H:m:s');
            } else {
                $user->notify(new EmailVerificationNotification());
            }
            $user->save();

            $seller = new Seller;
            $seller->user_id = $user->id;

            if ($seller->save()) {
                $shop = new Shop;
                $shop->user_id = $user->id;
                $shop->slug = 'demo-shop-' . $user->id;
                $shop->save();

                flash(translate('Seller has been inserted successfully'))->success();
                return redirect()->route('sellers.index');
            }
        }
        flash(translate('Something went wrong'))->error();
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
        $shop = Shop::findOrFail(decrypt($id));
        return view('backend.sellers.edit', compact('shop'));
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
        $shop = Shop::findOrFail($id);
        $user = $shop->user;
        $user->name = $request->name;
        $user->email = $request->email;
        if (strlen($request->password) > 0) {
            $user->password = Hash::make($request->password);
        }
        if ($user->save()) {
            if ($shop->save()) {
                flash(translate('Seller has been updated successfully'))->success();
                return redirect()->route('sellers.index');
            }
        }

        flash(translate('Something went wrong'))->error();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $shop = Shop::findOrFail($id);
        Product::where('user_id', $shop->user_id)->delete();
        $orders = Order::where('user_id', $shop->user_id)->get();

        foreach ($orders as $key => $order) {
            OrderDetail::where('order_id', $order->id)->delete();
        }
        Order::where('user_id', $shop->user_id)->delete();

        User::destroy($shop->user->id);

        if (Shop::destroy($id)) {
            flash(translate('Seller has been deleted successfully'))->success();
            return redirect()->route('sellers.index');
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

    public function bulk_seller_delete(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $shop_id) {
                $this->destroy($shop_id);
            }
        }

        return 1;
    }

    public function show_verification_request($id)
    {
        $shop = Shop::findOrFail($id);
        return view('backend.sellers.verification', compact('shop'));
    }

    public function approve_seller($id)
    {
        $shop = Shop::findOrFail($id);
        $shop->verification_status = 1;
        $shop->save();
        Cache::forget('verified_sellers_id');

        $users = User::findMany([$shop->user->id, User::where('user_type', 'admin')->first()->id]);
        Notification::send($users, new ShopVerificationNotification($shop, 'approved'));

        flash(translate('Seller has been approved successfully'))->success();
        return redirect()->route('sellers.index');
    }

    public function reject_seller($id)
    {
        $shop = Shop::findOrFail($id);
        $shop->verification_status = 0;
        $shop->verification_info = null;
        $shop->save();
        Cache::forget('verified_sellers_id');

        $users = User::findMany([$shop->user->id, User::where('user_type', 'admin')->first()->id]);
        Notification::send($users, new ShopVerificationNotification($shop, 'rejected'));

        flash(translate('Seller verification request has been rejected successfully'))->success();
        return redirect()->route('sellers.index');
    }


    public function payment_modal(Request $request)
    {
        $shop = shop::findOrFail($request->id);
        return view('backend.sellers.payment_modal', compact('shop'));
    }

    public function profile_modal(Request $request)
    {
        $shop = Shop::findOrFail($request->id);
        return view('backend.sellers.profile_modal', compact('shop'));
    }

    public function updateApproved(Request $request)
    {
        $shop = Shop::findOrFail($request->id);
        $shop->verification_status = $request->status;
        $shop->save();
        Cache::forget('verified_sellers_id');

        $status = $request->status == 1 ? 'approved' : 'rejected';
        $users = User::findMany([$shop->user->id, User::where('user_type', 'admin')->first()->id]);
        Notification::send($users, new ShopVerificationNotification($shop, $status));
        return 1;
    }

    public function login($id)
    {
        $shop = Shop::findOrFail(decrypt($id));
        $user  = $shop->user;
        auth()->login($user, true);

        return redirect()->route('seller.dashboard');
    }

    public function ban($id)
    {
        $shop = Shop::findOrFail($id);

        if ($shop->user->banned == 1) {
            $shop->user->banned = 0;
            if ($shop->verification_info) {
                $shop->verification_status = 1;
            }
            flash(translate('Seller has been unbanned successfully'))->success();
        } else {
            $shop->user->banned = 1;
            $shop->verification_status = 0;
            flash(translate('Seller has been banned successfully'))->success();
        }
        $shop->save();
        $shop->user->save();
        return back();
    }
    public function approve($id,Request $request)
    {
        $seller = User::findOrFail($id);
        $oldStatus = $seller->status;
        if($request->value == "Enabled")
            $seller->status = 'Enabled';
        else
             $seller->status = 'Pending Approval';

        $seller->save();

        // Log the status change
        if($request->value == "Enabled")
            $this->logStatusChange($seller, 'Enabled');
        else
            $this->logStatusChange($seller, 'Pending Approval');

    // Send an email notification to the seller with old and new status
    $seller->notify(new VendorStatusChangedNotification($oldStatus, $seller->status));
    Notification::send($seller, new CustomStatusNotification($oldStatus, $seller->status));

        // Check if it's an AJAX request
        return response()->json([
            'success' => true,
            'message' => __('messages.vendor_approved_successfully'),
            'status' => $seller->status
        ]);

    }

    public function enable($id) {
        $seller = User::findOrFail($id);
        $oldStatus = $seller->status;
        $seller->status = 'Enabled';


        $seller->save();


        $this->logStatusChange($seller, 'Enabled');


    // Send an email notification to the seller with old and new status
    $seller->notify(new VendorStatusChangedNotification($oldStatus, $seller->status));
    Notification::send($seller, new CustomStatusNotification($oldStatus, $seller->status));

    return redirect()->route('sellers.index')->with('success', 'Vendor approved successfully');

    }

    public function upload(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust as per your requirements
        ]);
        // Retrieve the total size of all uploaded images from the session
        $totalSize = $request->session()->get('totalSize', 0);

        // Validate and process the uploaded file
        $image = $request->file('image');
        $imageSize = $image->getSize(); // Get the size of the image in bytes

        // Check if adding the size of this image would exceed the limit
        if ($totalSize + $imageSize > 5 * 1024 * 1024) {

            // Store the updated total size in the session as flash data
            $request->session()->flash('totalSize', $totalSize);
            // Return an error response if the total size exceeds the limit
            return response()->json(['errors' => [
                'message' => 'Total image size exceeds the maximum limit of 5 MB. Please reduce the size of the images.'],'totalSize'=>$totalSize], 400);
        }
        // Store the image in the storage directory
        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('reject_reason'), $imageName);

        // Return the URL of the uploaded image
        $imageUrl = static_asset('reject_reason/'.$imageName);
        // Increment the total size with the size of the uploaded image
        $totalSize += $imageSize;

        // Store the updated total size in the session as flash data
        $request->session()->flash('totalSize', $totalSize);
        return response()->json(['imageUrl' => $imageUrl,'totalSize'=>$totalSize]);
    }
    public function delete(Request $request)
    {
        $imageSrc = $request->input('src');

        // Get the filename from the image URL
        $filename = basename($imageSrc);

        // Delete the image from storage
        $filePath = public_path('reject_reason/' . $filename);
        if (File::exists($filePath)) {
            // Get the size of the image
            $imageSize = filesize($filePath);

            // Get the total size from the session
            $totalSize = $request->session()->get('totalSize', 0);

            // Subtract the image size from the total size
            $totalSize -= $imageSize;

            // Update the total size in the the session as flash data
            $request->session()->flash('totalSize', $totalSize);

            // Delete the image from storage
            File::delete($filePath);
            return response()->json(['message' => 'Image deleted successfully','totalSize'=>$totalSize], 200);
        }

        return response()->json(['message' => 'Image not found'], 404);
    }

    public function resubmitRegistration($id,Request $request)
    {

        $seller = User::findOrFail($id);
        $oldStatus = $seller->status;

        // Update status and other necessary fields
        $seller->status = 'Rejected'; // Change status to "Pending Approval"
        $seller->save();

        // Optionally, you can also log this status change in the status history table
        $this->logStatusChange($seller, 'Rejected');
        // Get the reject reason and image URL from the request
        $rejectReason = $request->input('reject_reason');

        // Send an email notification to the seller with old and new status
        $seller->notify(new VendorStatusChangedNotification($oldStatus, $seller->status,$rejectReason,$seller->email));
        Notification::send($seller, new CustomStatusNotification($oldStatus, $seller->status));

        // // Redirect the user back or to any other page
        // return response()->json(['success' => true]);
        // Redirect the user back to sellers.index with a success message
        return redirect()->route('sellers.index')->with('success', 'Vendor has been rejected successfully.');

    }

       // Helper method to log status change
       private function logStatusChange($seller, $status)
       {
           $statusHistory = new VendorStatusHistory();
           $statusHistory->vendor_id = $seller->id;
           $statusHistory->status = $status;
           $statusHistory->save();
       }

       public function suspend($id, Request $request)
        {

            $vendor = User::findOrFail($id);
            $oldStatus = $vendor->status;
            // Update vendor's status to "Suspended"
            $vendor->status = 'Suspended';
            $vendor->save();

            // Store suspension details in the database
            $suspension = new VendorStatusHistory();
            $suspension->vendor_id = $vendor->id;
            $suspension->reason = $request->input('reason');
            $suspension->suspension_reason = $request->input('reason_title');
            $suspension->details = $request->input('reason_details');
            $suspension->status = "Suspended";
            $suspension->save();
            // Send an email notification to the seller with old and new status
            $vendor->notify(new VendorStatusChangedNotification($oldStatus, $vendor->status,$request->input('reason_details'),null,$request->input('reason_title')));
            Notification::send($vendor, new CustomStatusNotification($oldStatus, $vendor->status,$suspension->suspension_reason));

            return redirect()->route('sellers.index')->with('success', 'Vendor has been suspended successfully.');

        }

        public function pendingClosure($id)
        {
            $vendor = User::findOrFail($id);
            $oldStatus = $vendor->status;

            // Update vendor's status to "Pending Closure"
            $vendor->status = 'Pending Closure';
            $vendor->save();
            // Optionally, you can also log this status change in the status history table
            $this->logStatusChange($vendor, 'Pending Closure');
            // Send an email notification to the seller with old and new status
            $vendor->notify(new VendorStatusChangedNotification($oldStatus, $vendor->status));
            Notification::send($vendor, new CustomStatusNotification($oldStatus, $vendor->status));

            // Return success response
            return response()->json(['success' => true]);
        }
        public function close($id)
        {
            $vendor = User::findOrFail($id);
            $oldStatus = $vendor->status;

            // Update vendor's status to "Closed"
            $vendor->status = 'Closed';
            $vendor->save();
            $this->logStatusChange($vendor, 'Closed');
            // Send an email notification to the seller with old and new status
            $vendor->notify(new VendorStatusChangedNotification($oldStatus, $vendor->status));
            Notification::send($vendor, new CustomStatusNotification($oldStatus, $vendor->status));

            // Return success response
            return response()->json(['success' => true]);
        }

        public function getStatusHistory(Request $request, $vendorId)
        {
            // Fetch the vendor status history from the database
            $history = VendorStatusHistory::where('vendor_id', $vendorId)
                ->orderBy('created_at', 'desc')
                ->get();
            $vendor_email= User::find($vendorId) ? User::find($vendorId)->email : "" ;
            // Return the history as JSON response
            // return response()->json(['history' => $history]);
            return view('backend.sellers.status_history',compact('history','vendor_email'));

        }

        public function view($id)
    {
        $user = User::findOrFail($id);
        $emirates=Emirate::all() ;
        return view('backend.sellers.view', compact('user','emirates'));
    }

    public function reject($id) {
        $user = User::find($id) ;
        return view('backend.sellers.reject_seller_registration',compact('user'));
    }

    public function suspendView($id) {
        $user = User::find($id) ;
        return view('backend.sellers.suspend_seller',compact('user'));
    }




}
