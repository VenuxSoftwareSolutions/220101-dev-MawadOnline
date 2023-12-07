<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomerPackageController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\SellerPackageController;
use App\Models\CombinedOrder;
use App\Models\CustomerPackage;
use App\Models\SellerPackage;
use Illuminate\Http\Request;
use Redirect;
use Session;

class KhaltiController extends Controller
{
    public function pay()
    {
        if (Session::has('payment_type')) {

            if (Session::get('payment_type') == 'cart_payment') {
                $combined_order = CombinedOrder::findOrFail(Session::get('combined_order_id'));
                $purchase_order_id = $combined_order->id;
                $amount = round($combined_order->grand_total);
            } elseif (Session::get('payment_type') == 'wallet_payment') {
                $amount = round(Session::get('payment_data')['amount']);
                $purchase_order_id = auth()->id();
            } elseif (Session::get('payment_type') == 'customer_package_payment') {
                $customer_package = CustomerPackage::findOrFail(Session::get('payment_data')['customer_package_id']);
                $amount = round($customer_package->amount);
                $purchase_order_id = $customer_package->id;
            } elseif (Session::get('payment_type') == 'seller_package_payment') {
                $seller_package = SellerPackage::findOrFail(Session::get('payment_data')['seller_package_id']);
                $amount = round($seller_package->amount);
                $purchase_order_id = $seller_package->id;
            }
        }
        $return_url = route('khalti.success');
        $args = http_build_query([
            'return_url' => $return_url,
            'website_url' => route('home'),
            'amount' => $amount * 100,
            "modes"=>[
                "KHALTI",
                "EBANKING",
                "MOBILE_BANKING",
                "CONNECT_IPS",
                "SCT"
            ],
            'purchase_order_id' => $purchase_order_id,            
            'purchase_order_name' => Session::get('payment_type'),            
        ]);
        if (get_setting('khalti_sandbox') == 1) {
            $url = 'https://a.khalti.com/api/v2/epayment/initiate/';
        } else {
            $url = 'https://khalti.com/api/v2/epayment/initiate/';
        }
        # Make the call using API.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $headers = ['Authorization: Key ' . env('KHALTI_SECRET_KEY')];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Response
        $response = json_decode(curl_exec($ch), true);
        curl_close($ch);
        return Redirect::to($response['payment_url']);
    }

    public function paymentDone(Request $request)
    {
        $args = http_build_query([
            'pidx' => $request->pidx,
        ]);
        if (get_setting('khalti_sandbox') == 1) {
            $url = 'https://a.khalti.com/api/v2/epayment/lookup/';
        } else {
            $url = 'https://khalti.com/api/v2/epayment/lookup/';
        }

        # Make the call using API.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $headers = ['Authorization: Key ' . env('KHALTI_SECRET_KEY')];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Response
        $response = json_decode(curl_exec($ch));
        curl_close($ch);

        if ($response->status == 'Completed') {
            // If call returns body in response, you can get the deserialized version from the result attribute of the response
            if ($request->session()->get('payment_type') == 'cart_payment') {
                return (new CheckoutController())->checkout_done($request->session()->get('combined_order_id'), json_encode($response));
            } elseif ($request->session()->get('payment_type') == 'wallet_payment') {
                return (new WalletController())->wallet_payment_done($request->session()->get('payment_data'), json_encode($response));
            } elseif ($request->session()->get('payment_type') == 'customer_package_payment') {
                return (new CustomerPackageController())->purchase_payment_done($request->session()->get('payment_data'), json_encode($response));
            } elseif ($request->session()->get('payment_type') == 'seller_package_payment') {
                return (new SellerPackageController())->purchase_payment_done($request->session()->get('payment_data'), json_encode($response));
            }
        } else {
            flash(translate('Something went wrong and payment failed!'))->error();
            return redirect()->route('home');
        }
    }
}
