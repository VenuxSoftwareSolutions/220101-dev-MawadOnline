<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\EmailAllowedClient;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Session;

class CustomerLoginController extends Controller
{
    use AuthenticatesUsers;

    // public function login(Request $request)
    // {

    //     $this->validateLogin($request);

    //     if (session('temp_user_id') != null) {
    //         Cart::where('temp_user_id', session('temp_user_id'))
    //             ->update([
    //                 'user_id' => auth()->user()->id,
    //                 'temp_user_id' => null
    //             ]);

    //         Session::forget('temp_user_id');
    //     }
    //     // if(EmailAllowedClient::where('email', $request->email)->first() == null){
    //     //     return redirect()->route('business');
    //     // }
    //     // Attempt to log the user in
    //     if ($this->attemptLogin($request, 'customer') && EmailAllowedClient::where('email', $request->email)->exists()) {
    //         // Redirect to admin dashboard
    //         return redirect()->intended('dashboard');
    //     }

    //     // If login attempt was unsuccessful
    //     return $this->sendFailedLoginResponse($request);
    // }
    public function login(Request $request)
    {

        $this->validateLogin($request);

        // if(EmailAllowedClient::where('email', $request->email)->first() == null){
        //     return redirect()->route('business');
        // }
        // Attempt to log the user in
        if ($this->attemptLogin($request, 'customer') && EmailAllowedClient::where('email', $request->email)->exists()) {
            // Redirect to admin dashboard

        if (session('temp_user_id') != null) {
            Cart::where('temp_user_id', session('temp_user_id'))
                ->update([
                    'user_id' => auth()->user()->id,
                    'temp_user_id' => null
                ]);

            Session::forget('temp_user_id');
            // Check for duplicate products in the cart for the logged-in user
            $cartItems = Cart::where('user_id', auth()->user()->id)
            ->select('product_id')
            ->groupBy('product_id')
            ->havingRaw('COUNT(product_id) > 1')
            ->get();
             // Delete duplicate products from the cart
             foreach ($cartItems as $item) {
                // Get all cart entries for this product, except the first one
                $duplicates = Cart::where('user_id', auth()->user()->id)
                    ->where('product_id', $item->product_id)
                    ->orderBy('id') // Assuming you want to keep the first added product
                    ->skip(1)
                    ->take(PHP_INT_MAX)
                    ->get();

                foreach ($duplicates as $duplicate) {
                    $duplicate->delete();
                }
            }
        }
            return redirect()->intended('dashboard');
        }

        // If login attempt was unsuccessful
        return $this->sendFailedLoginResponse($request);
    }


    protected function attemptLogin(Request $request, $type)
    {
        return $this->guard()->attempt(
            ['email' => $request->email, 'password' => $request->password, 'user_type' => $type],
            $request->filled('remember')
        );
    }
}
