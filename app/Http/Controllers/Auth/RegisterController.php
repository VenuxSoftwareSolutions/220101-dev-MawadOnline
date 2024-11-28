<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\Models\Cart;
use App\Models\EmailAllowedClient;
use App\Models\User;
use App\Rules\Recaptcha;
use App\Rules\Strict_email as StrictEmail;
use Cookie;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Log;
use Session;
use Throwable;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', new StrictEmail],
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
            'g-recaptcha-response' => [
                Rule::when(get_setting('google_recaptcha') == 1, ['required', new Recaptcha], ['sometimes']),
            ],
        ]);
    }

    protected function create(array $data)
    {
        if (filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
        }

        if (session('temp_user_id') != null) {
            Cart::where('temp_user_id', session('temp_user_id'))
                ->update([
                    'user_id' => $user->id,
                    'temp_user_id' => null,
                ]);

            Session::forget('temp_user_id');
        }

        if (Cookie::has('referral_code')) {
            $referral_code = Cookie::get('referral_code');
            $referred_by_user = User::where('referral_code', $referral_code)->first();
            if ($referred_by_user != null) {
                $user->referred_by = $referred_by_user->id;
                $user->save();
            }
        }

        return $user;
    }

    public function register(Request $request)
    {
        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            if (EmailAllowedClient::where('email', $request->email)->first() == null) {
                return redirect()->route('business');
            }

            if (User::where('email', $request->email)->first() != null) {
                flash(translate('Email or Phone already exists.'));

                return back();
            }
        }

        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        $this->guard()->login($user);

        if ($user->email != null) {
            if (BusinessSetting::where('type', 'email_verification')->first()->value != 1) {
                $user->email_verified_at = date('Y-m-d H:m:s');
                $user->save();
                offerUserWelcomeCoupon();
                flash(translate('Registration successful.'))->success();
            } else {
                try {
                    $user->sendEmailVerificationNotification();
                    flash(translate('Registration successful. Please verify your email.'))->success();
                } catch (Throwable $th) {
                    $user->delete();
                    flash(translate('Registration failed. Please try again later.'))->error();
                    Log::error("Error while register user, with message: {$th->getMessage()}");
                }
            }
        }

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    protected function registered($user)
    {
        if ($user->email == null) {
            return redirect()->route('verification');
        } elseif (session('link') != null) {
            return redirect(session('link'));
        } else {
            return redirect()->route('home');
        }
    }
}
