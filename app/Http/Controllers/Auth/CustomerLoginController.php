<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cart;
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

        // Attempt to log the user in
        if ($this->attemptLogin($request, 'customer')) {
            // Redirect to admin dashboard
            return redirect()->intended('dashboard');
        }

        // If login attempt was unsuccessful
        return $this->sendFailedLoginResponse($request);
    }


    protected function attemptLogin(Request $request, $type)
    {
        // Determine the credentials based on the input.
        if ($request->get('phone') != null) {
            // Assume 'country_code' is also part of the request and is required.
            $credentials = [
                'phone' => "+{$request['country_code']}{$request['phone']}",
                'password' => $request->get('password'),
                'user_type' => $type,
            ];
        } elseif ($request->get('email') != null) {
            // Use the email for authentication.
            $credentials = [
                'email' => $request->get('email'),
                'password' => $request->get('password'),
                'user_type' => $type,
            ];
        }

        // Attempt to authenticate with the constructed credentials.
        return $this->guard()->attempt($credentials, $request->filled('remember'));
    }


    protected function validateLogin(Request $request)
    {
        // Validation rules
        $rules = [
            'password' => 'required|string|min:6', // Adjust password rules as needed
        ];

        // Conditional validation for email or phone
        if ($request->filled('email')) {
            $rules['email'] = 'required|string|email|max:255';
        } elseif ($request->filled('phone') && $request->filled('country_code')) {
            $rules['phone'] = 'required|string|max:20'; // Adjust based on your phone number format requirements
            $rules['country_code'] = 'required|string|max:5'; // Adjust based on your needs
        } else {
            // If neither email nor phone is provided, add a custom rule that always fails
            $rules['email_or_phone'] = 'required|string';
        }

        // Perform validation
        $request->validate($rules, [
            'email_or_phone.required' => 'You must provide either an email address or a phone number.',
        ]);
    }
}
