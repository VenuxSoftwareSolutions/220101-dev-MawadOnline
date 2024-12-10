<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use GeneaLabs\LaravelSocialiter\Facades\Socialiter;
use Socialite;
use App\Models\User;
use App\Models\AccountAuthenticator;
use App\Models\Customer;
use App\Models\Cart;
use App\Services\SocialRevoke;
use Session;
use Illuminate\Http\Request;
use CoreComponentRepository;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use Auth;
use Storage;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    /*protected $redirectTo = '/';*/


    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {

        if (request()->get('query') == 'mobile_app') {
            request()->session()->put('login_from', 'mobile_app');
        }
        if ($provider == 'apple') {
            return Socialite::driver("apple")
                ->scopes(["name"])
                ->enablePKCE()
                ->redirect();
        }
        if ($provider == 'twitter') {
            return Socialite::driver('twitter-oauth-2')
            ->scopes(["users.read"])
            ->redirect();
        }
        if ($provider == 'facebook') {
            return Socialite::driver('facebook')
            ->scopes(['email'])
            ->enablePKCE()
            ->redirect();
        }

        return Socialite::driver($provider)
        ->with(['prompt' => 'consent'])
        ->enablePKCE()
        ->redirect();

       
    }

    public function handleAppleCallback(Request $request)
    {
        try {
            $user = Socialite::driver("apple")->user();
        } catch (\Exception $e) {
            flash(translate("Something Went wrong. Please try again."))->error();
            return redirect()->route('user.login');
        }
    
        $appleAuthenticatorId = AccountAuthenticator::where('name', 'Apple')->value('id');
    
        $existingUserByProviderId = User::where('provider_id', $user->id)->where('authenticator_id', $appleAuthenticatorId)->first();
    
        if ($existingUserByProviderId) {
            $existingUserByProviderId->access_token = $user->token;
            $existingUserByProviderId->refresh_token = $user->refreshToken;
    
            if (!isset($user->user['is_private_email'])) {
                $existingUserByProviderId->email = $user->email;
            }
    
            $existingUserByProviderId->save();
    
            auth()->login($existingUserByProviderId, true);
        } else {
            $existingOrNewUser = User::firstOrNew(['email' => $user->email]);
    
            // Update user details
            $existingOrNewUser->provider_id = $user->id;
            $existingOrNewUser->access_token = $user->token;
            $existingOrNewUser->refresh_token = $user->refreshToken;
            $existingOrNewUser->authenticator_id = $appleAuthenticatorId;
    
            if (!$existingOrNewUser->exists) {
                $existingOrNewUser->name = $user->name ?? 'Apple User';
                $existingOrNewUser->email = $user->email;
                $existingOrNewUser->email_verified_at = now();
            }
    
            $existingOrNewUser->save();
    
            auth()->login($existingOrNewUser, true);
        }
    
        if (session('temp_user_id') != null) {
            Cart::where('temp_user_id', session('temp_user_id'))
                ->update([
                    'user_id' => auth()->user()->id,
                    'temp_user_id' => null,
                ]);
    
            Session::forget('temp_user_id');
        }
    
        // Redirect the user to the appropriate location
        if (session('link') != null) {
            return redirect(session('link'));
        } else {
            if (auth()->user()->user_type == 'seller') {
                return redirect()->route('seller.dashboard');
            }
            return redirect()->route('dashboard');
        }
    }
    
    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback(Request $request, $provider)
    {
        if (session('login_from') == 'mobile_app') {
            return $this->mobileHandleProviderCallback($request, $provider);
        }
    
        try {
            $socialUser = $this->getSocialUser($provider);
        } catch (\Exception $e) {
            flash(translate("Something went wrong. Please try again."))->error();
            return redirect()->route('user.login');
        }
    
        $user = $this->findOrCreateUser($socialUser, $provider);
    
        auth()->login($user, true);
    
        $this->mergeCartWithUser();
    
        return $this->redirectUser();
    }
    
    /**
     * Retrieve the social user using the provider.
     */
    private function getSocialUser($provider)
    {
        if ($provider === 'twitter') {
            return Socialite::driver('twitter-oauth-2')->user();
        }
        return Socialite::driver($provider)->enablePKCE()->user();
    }
    
    /**
     * Find or create a user from the social user details.
     */
    private function findOrCreateUser($socialUser, $provider)
    {
        // Retrieve the account authenticator ID from the new table
        $authenticatorId = AccountAuthenticator::where('name', $provider)->value('id');
    
        // If the provider is not in the account_authenticators table, fallback to MawadOnline (ID 1)
        $authenticatorId = $authenticatorId ?? 1;
    
        return User::updateOrCreate(
            ['email' => $socialUser->email],
            [
                'name' => $socialUser->name,
                'email_verified_at' => now(),
                'provider_id' => $socialUser->id,
                'authenticator_id' => $authenticatorId,
                'access_token' => $socialUser->token,
            ]
        );
    }
        /**
     * Merge temporary cart with the authenticated user.
     */
    private function mergeCartWithUser()
    {
        if (session('temp_user_id')) {
            Cart::where('temp_user_id', session('temp_user_id'))
                ->update([
                    'user_id' => auth()->id(),
                    'temp_user_id' => null,
                ]);
            Session::forget('temp_user_id');
        }
    }

    /**
     * Redirect the user to the appropriate dashboard or link.
     */
    private function redirectUser()
    {
        if (session('link')) {
            return redirect(session('link'));
        }

        return auth()->user()->user_type === 'seller'
            ? redirect()->route('seller.dashboard')
            : redirect()->route('dashboard');
    }


    public function mobileHandleProviderCallback($request, $provider)
    {
        $return_provider = '';
        $result = false;
        if ($provider) {
            $return_provider = $provider;
            $result = true;
        }
        return response()->json([
            'result' => $result,
            'provider' => $return_provider
        ]);
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required_without:phone',
            'phone' => 'required_without:email',
            'password' => 'required|string',
        ]);
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        if ($request->get('phone') != null) {
            return ['phone' => "+{$request['country_code']}{$request['phone']}", 'password' => $request->get('password')];
        } elseif ($request->get('email') != null) {
            return $request->only($this->username(), 'password');
        }
    }

    /**
     * Check user's role and redirect user based on their role
     * @return
     */
    // public function authenticated()
    // {
    //     if (session('temp_user_id') != null) {
    //         Cart::where('temp_user_id', session('temp_user_id'))
    //             ->update(
    //                 [
    //                     'user_id' => auth()->user()->id,
    //                     'temp_user_id' => null
    //                 ]
    //             );

    //         Session::forget('temp_user_id');
    //     }

    //     if (auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'staff') {
    //         CoreComponentRepository::instantiateShopRepository();
    //         return redirect()->route('admin.dashboard');
    //     } elseif (auth()->user()->user_type == 'seller') {
    //         return redirect()->route('seller.dashboard');
    //     } else {

    //         if (session('link') != null) {
    //             return redirect(session('link'));
    //         } else {
    //             return redirect()->route('dashboard');
    //         }
    //     }
    // }


    public function authenticated(Request $request)
    {
        // Handling Temporary User IDs for Cart
        if (session('temp_user_id') != null) {
            Cart::where('temp_user_id', session('temp_user_id'))
                ->update([
                    'user_id' => auth()->user()->id,
                    'temp_user_id' => null
                ]);

            Session::forget('temp_user_id');
        }

        // Redirecting Admins and Staff
        if (auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'staff') {
            //CoreComponentRepository::instantiateShopRepository();
            return redirect()->route('admin.dashboard');
        }

        // Return an error for non-admin and non-staff users
        else {
            Auth::logout(); // Optional: log the user out
            flash(translate('Invalid login credentials'))->error();

            // Redirect back with an error message, or to a specific route
            return redirect()->back();
            // or return redirect('some_route')->withErrors(['error' => 'You do not have permission to access this area.']);
        }
    }


    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        flash(translate('Invalid login credentials'))->error();
        return back();
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        if (auth()->user() != null && (auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'staff')) {
            $redirect_route = 'login';
        } else {
            $redirect_route = 'home';
        }

        //User's Cart Delete
        // if (auth()->user()) {
        //     Cart::where('user_id', auth()->user()->id)->delete();
        // }

        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect()->route($redirect_route);
    }

    public function account_deletion(Request $request)
    {

        $redirect_route = 'home';

        if (auth()->user()) {
            Cart::where('user_id', auth()->user()->id)->delete();
        }

        // if (auth()->user()->provider) {
        //     $social_revoke =  new SocialRevoke;
        //     $revoke_output = $social_revoke->apply(auth()->user()->provider);

        //     if ($revoke_output) {
        //     }
        // }

        $auth_user = auth()->user();

        // user images delete from database and file storage
        $uploads = $auth_user->uploads;
        if ($uploads) {
            foreach ($uploads as $upload) {
                if (env('FILESYSTEM_DRIVER') == 's3') {
                    Storage::disk('s3')->delete($upload->file_name);
                    if (file_exists(public_path() . '/' . $upload->file_name)) {
                        unlink(public_path() . '/' . $upload->file_name);
                        $upload->delete();
                    }
                } else {
                    unlink(public_path() . '/' . $upload->file_name);
                    $upload->delete();
                }
            }
        }

        $auth_user->customer_products()->delete();

        User::destroy(auth()->user()->id);

        auth()->guard()->logout();
        $request->session()->invalidate();

        flash(translate("Your account deletion successfully done."))->success();
        return redirect()->route($redirect_route);
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except(['logout', 'account_deletion']);
    }

    public function handle_demo_login()
    {
        return view('frontend.handle_demo_login');
    }
}
