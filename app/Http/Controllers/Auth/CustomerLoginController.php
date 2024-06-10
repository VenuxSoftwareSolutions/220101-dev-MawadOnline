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

    public function login(Request $request)
    {

        $this->validateLogin($request);

        if (session('temp_user_id') != null) {
            Cart::where('temp_user_id', session('temp_user_id'))
                ->update([
                    'user_id' => auth()->user()->id,
                    'temp_user_id' => null
                ]);

            Session::forget('temp_user_id');
        }
        // if(EmailAllowedClient::where('email', $request->email)->first() == null){
        //     return redirect()->route('business');
        // }
        // Attempt to log the user in
        if ($this->attemptLogin($request, 'customer') && EmailAllowedClient::where('email', $request->email)->exists()) {
            // Redirect to admin dashboard
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
