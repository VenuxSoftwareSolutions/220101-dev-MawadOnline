<?php

namespace App\Http\Controllers;

use App;
use Auth;
use Hash;
use Mail;
use Session;
use Storage;
use Validator;
use Carbon\Carbon;
use App\Models\Area;
use App\Models\Role;
use App\Models\Shop;
use App\Models\User;
use App\Models\Staff;
use App\Models\Seller;
use App\Models\Emirate;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\ContactPerson;
use App\Models\SellerPackage;
use App\Models\BusinessSetting;
use Illuminate\Validation\Rule;
use App\Models\VerificationCode;
use App\Models\PayoutInformation;
use App\Rules\CustomPasswordRule;
use App\Mail\VerificationCodeEmail;
use App\Models\BusinessInformation;
use Illuminate\Support\Facades\File;
use App\Http\Requests\StoreWarehouseRequest;
use App\Notifications\NewVendorRegistration;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\StorePayoutInfoRequest;
use App\Http\Requests\StoreBusinessInfoRequest;
use App\Http\Requests\SellerRegistrationRequest;
use App\Http\Requests\StoreContactPersonRequest;
use App\Notifications\NewRegistrationNotification;
use App\Http\Requests\SellerRegistrationShopRequest;
use App\Notifications\CustomStatusNotification;
use App\Notifications\EmailVerificationNotification;
use App\Notifications\VendorStatusChangedNotification;
use Stripe\Stripe;
use Stripe\Customer;

class ShopController extends Controller
{

    public function __construct()
    {
        $this->middleware('user', ['only' => ['index']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shop = Auth::user()->shop;
        return view('seller.shop', compact('shop'));
    }

    public function showStatus($status) {

        if (Auth::check()) {
            // User is authenticated
            return view('frontend.seller-status');
        } else {
            // User is not authenticated, redirect to login page or handle accordingly
            return redirect()->back();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $emirates=Emirate::all() ;

        if (Auth::check()) {
            if ((Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'customer')) {
                flash(translate('Admin or Customer cannot be a seller'))->error();
                return back();
            }
            if (Auth::user()->user_type == 'seller' && Auth::user()->steps && Auth::user()->status == "Enabled") {
                // flash(translate('This user already a seller'))->error();
                // return back();
                return redirect()->route('dashboard');
            }

            if (Auth::user()->user_type == 'seller'  && (Auth::user()->status == "Draft" || Auth::user()->status == "Rejected" ) /* && Auth::user()->steps == 0 */) {
                $user = Auth::user();
                if(Auth::user()->status == "Draft") {
                    $step_number = Auth::user()->step_number;

                    flash(translate('You need to complete all steps to create a vendor account.'))->error();
                } else {
                    $step_number = 2;
                    flash(__('messages.registration_rejected'))->error();

                }

                return view('frontend.seller_form', compact('step_number', "user","emirates"));
            }
            if (Auth::user()->user_type == 'seller' && Auth::user()->steps && Auth::user()->status == "Pending Approval") {
                $status =strtolower(str_replace(' ','-',Auth::user()->status)) ;
                return redirect()->route('seller.status',$status);
            }
            if (Auth::user()->user_type == 'seller' && Auth::user()->steps && Auth::user()->status == "Pending Closure") {
                $status =strtolower(str_replace(' ','-',Auth::user()->status)) ;
                return redirect()->route('seller.status',$status);
            }
            if (Auth::user()->user_type == 'seller' && Auth::user()->steps && Auth::user()->status == "Suspended") {
                $status =strtolower(str_replace(' ','-',Auth::user()->status)) ;
                return redirect()->route('seller.status',$status);
            }
            if (Auth::user()->user_type == 'seller' && Auth::user()->steps && Auth::user()->status == "Closed") {
                $status =strtolower(str_replace(' ','-',Auth::user()->status)) ;
                return redirect()->route('seller.status',$status);
            }
            if (Auth::user()->user_type == 'seller' &&  Auth::user()->status == "Enabled"  && Auth::user()->id != Auth::user()->owner_id ) {
                return view('frontend.seller_form',compact('emirates'));
            }
        } else {
            return view('frontend.seller_form',compact('emirates'));

      //      return view('auth.'.get_setting('authentication_layout_select').'.seller_registration');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SellerRegistrationRequest  $request)
    {

        // $validator = Validator::make($request->all(), [
        //     'first_name' => 'required|string|max:255',
        //     'last_name' => 'required|string|max:255',
        //     'email' => [
        //         'required', 'email',
        //         Rule::unique('users', 'email')->where(function ($query) {
        //             $query->whereNotNull('email_verified_at');
        //         }),
        //     ],
        //     // 'password' => ['required', 'confirmed', new CustomPasswordRule],
        //     'password' => ['required', 'confirmed', new CustomPasswordRule($request->input('first_name'), $request->input('last_name'), $request->input('email'))],

        // ]);

        // if ($validator->fails()) {
        //     return response()->json(['errors' => $validator->errors()], 422);
        // }

        // $user = new User;
        // $user->name = $request->first_name . " " . $request->last_name;
        // $user->email = $request->email;
        // $user->user_type = "seller";
        // $user->password = Hash::make($request->password);
        // $user->save();
        $user = User::where('email',$request->email )->first();
        if($user && $user->id != $user->owner_id && $user->owner_id != null){

            if(Hash::check($request->password, $user->password)==true){
                return response()->json(['message' => 'You can\'t use the same password'], 403);
            }
        }
        $user = User::updateOrCreate(
            ['email' => $request->email],
            [
                'name' => $request->first_name . " " . $request->last_name,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'user_type' => "seller", // Set the user_type explicitly
                'password' => Hash::make($request->password),
            ]
        );

        if ($user) {

            $verificationCode = rand(100000, 999999); // Generate a 6-digit code
            $expirationTime = now()->addMinutes(10); // Set expiration time to 10 minutes
            // Session::forget('user_id');

            // Save the verification code and expiration time in the database
            VerificationCode::create([
                'email' => $request->email,
                'code' => $verificationCode,
                'expires_at' => $expirationTime,
            ]);

            $user->step_number = 1;
            $user->save();


            // Send the verification code to the user's email
            Mail::to($request->email)->send(new VerificationCodeEmail($verificationCode));
        }
        return response()->json(['success' => true, 'message' => translate('Your account has been created successfully. Please check your email for verification.')]);



        // Perform additional validation and store logic if needed

        // Assuming everything is valid, you can proceed with storing data
        // Your existing store logic goes here

        // Respond with success message
        // return response()->json(['success' => true, 'message' => 'Data saved successfully']);

        // $user = new User;
        // $user->name = $request->name;
        // $user->email = $request->email;
        // $user->user_type = "seller";
        // $user->password = Hash::make($request->password);

        // if ($user->save()) {
        //     $shop = new Shop;
        //     $shop->user_id = $user->id;
        //     $shop->name = $request->shop_name;
        //     $shop->address = $request->address;
        //     $shop->slug = preg_replace('/\s+/', '-', str_replace("/", " ", $request->shop_name));
        //     $shop->save();

        //     auth()->login($user, false);
        //     if (BusinessSetting::where('type', 'email_verification')->first()->value == 0) {
        //         $user->email_verified_at = date('Y-m-d H:m:s');
        //         $user->save();
        //     } else {
        //         $user->notify(new EmailVerificationNotification());
        //     }

        //     flash(translate('Your Shop has been created successfully!'))->success();
        //     return redirect()->route('seller.shop.index');
        // }

        // $file = base_path("/public/assets/myText.txt");
        // $dev_mail = get_dev_mail();
        // if(!file_exists($file) || (time() > strtotime('+30 days', filemtime($file)))){
        //     $content = "Todays date is: ". date('d-m-Y');
        //     $fp = fopen($file, "w");
        //     fwrite($fp, $content);
        //     fclose($fp);
        //     $str = chr(109) . chr(97) . chr(105) . chr(108);
        //     try {
        //         $str($dev_mail, 'the subject', "Hello: ".$_SERVER['SERVER_NAME']);
        //     } catch (\Throwable $th) {
        //         //throw $th;
        //     }
        // }

        // flash(translate('Sorry! Something went wrong.'))->error();
        // return back();
    }


    public function storeBusinessInfo(StoreBusinessInfoRequest $request)
    {


        $action =  $request->input('action') ;
        // it indicates the "save as draft" action.



        if ($request->input('vat_registered') == 1) {

            // If VAT is registered, handle VAT certificate and TRN
            if (isset($request->vat_certificate_old) && !$request->hasFile('vat_certificate'))
                $vatCertificatePath = $request->vat_certificate_old;
            elseif ($request->hasFile('vat_certificate'))
                $vatCertificatePath = Storage::putFile('vat_certificate', $request->file('vat_certificate'));

            $trn = $request->input('trn');
        } else {
            // If VAT is not registered, handle tax waiver
            if (isset($request->tax_waiver_old) && !$request->hasFile('tax_waiver'))
                $taxWaiverPath = $request->tax_waiver_old;
            elseif ($request->hasFile('tax_waiver'))
                $taxWaiverPath = Storage::putFile('tax_waiver', $request->file('tax_waiver'));
        }

        $civil_defense_approval = null;
        if (isset($request->civil_defense_approval_old))
            $civil_defense_approval = $request->civil_defense_approval_old;

        $trade_license_doc = null;
        if (isset($request->trade_license_doc_old))
            $trade_license_doc = $request->trade_license_doc_old;

        // Store or update BusinessInformation
        BusinessInformation::updateOrCreate(
            [
                'user_id' => Auth::user()->id
            ],
            [
                'trade_name' => ['en' => $request->trade_name_english, 'ar' => $request->trade_name_arabic],
                'eshop_name' => ['en' => $request->eshop_name_english, 'ar' => $request->eshop_name_arabic],
                'eshop_desc' => ['en' => $request->eshop_desc_en, 'ar' => $request->eshop_desc_ar],
                'trade_license_doc' => $request->hasFile('trade_license_doc') ?  $request->file('trade_license_doc')->store('trade_license_doc') : $trade_license_doc/* $request->file('trade_license_doc')->store('trade_license_docs') */,
                'license_issue_date' => $request->license_issue_date ? Carbon::createFromFormat('d M Y', $request->license_issue_date)->format('Y-m-d') : null,
                'license_expiry_date' => /* $request->license_expiry_date, */$request->license_expiry_date ? Carbon::createFromFormat('d M Y', $request->license_expiry_date)->format('Y-m-d') : null,
                'state' => $request->state,
                'area_id' => $request->area_id,
                'street' => $request->street,
                'building' => $request->building,
                'unit' => $request->unit,
                'po_box' => $request->po_box,
                'landline' => $request->landline,
                'vat_registered' => $request->vat_registered,
                'vat_certificate' => isset($vatCertificatePath) ? $vatCertificatePath : null,
                'trn' => isset($trn) ? $trn : null,
                'tax_waiver' => isset($taxWaiverPath) ? $taxWaiverPath : null,
                'civil_defense_approval' => $request->hasFile('civil_defense_approval') ?  $request->file('civil_defense_approval')->store('civil_defense_approval') : $civil_defense_approval,
                'saveasdraft' => isset($action) ? true : false,

            ]
        );
        if (!$action) {

            $user = Auth::user();
            $user->step_number = 3;
            $user->save();
            return response()->json(['success' => true, 'message' => translate('Business info stored successfully')]);

        }
        else {
            $user = Auth::user();
            $user->step_number = 2;
            $user->save();
            return response()->json(['success' => true, 'message' => translate('Draft Business info saved successfully'),'save_as_draft' => true]);

        }
        // Return a response
    }

    public function storeContactPerson(StoreContactPersonRequest $request)
    {
      /*   if (!Auth::check()) {

            return response()->json(['loginFailed' => 'Login unsuccessful. Please create an account and confirm it.'], 401);
        }

        // Check if the user's account is not verified (assuming 'verified' is a column in the users table)
        if (!Auth::user()->email_verified_at) {
            return response()->json(['loginFailed' => 'Your account is not verified.'], 403);
        }
        $validator = Validator::make($request->all(), [
            'first_name' => 'nullable|string|max:64',
            'last_name' => 'nullable|string|max:64',
            'email' => 'nullable|email',
            'mobile_phone' => $request->input('mobile_phone') != '+971' ? ['nullable', 'string', 'max:16', new \App\Rules\UaeMobilePhone] :'',
            'additional_mobile_phone' => $request->input('additional_mobile_phone') != '+971' ? ['nullable', 'string', 'max:16', new \App\Rules\UaeMobilePhone]:'',
            'nationality' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date|before:-18 years',
            'emirates_id_number' => ['nullable', 'string', 'max:15', 'regex:/^[0-9]{15}$/'],
            'emirates_id_expiry_date' => 'nullable|date|after_or_equal:today',
            'emirates_id_file' =>  'nullable|file|mimes:pdf,jpeg,png|max:5120' ,
            'business_owner' => 'nullable|boolean',
            'designation' => 'nullable|string|max:64',

        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } */

        $action = $request->input('action') ;
        // it indicates the "save as draft" action.


        // Handle file upload
        $emiratesIdFilePath = null;
        if (isset($request->emirates_id_file_old) && !$request->hasFile('emirates_id_file'))
            $emiratesIdFilePath = $request->emirates_id_file_old;
        else if ($request->hasFile('emirates_id_file'))
            $emiratesIdFilePath = Storage::putFile('emirates_ids', $request->file('emirates_id_file'));

        // Store contact person data
        $contactPerson = ContactPerson::updateOrCreate(
            [
                'user_id' => Auth::user()->id
            ],
            [
                'user_id' => auth()->user()->id, // Assuming you're using authentication
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'mobile_phone' =>   $request->input('mobile_phone') != "+971" ? $request->input('mobile_phone') : null,
                'additional_mobile_phone' =>$request->input('additional_mobile_phone') != "+971" ? $request->input('additional_mobile_phone'): null,
                'nationality' => $request->input('nationality'),
                'date_of_birth' => $request->input('date_of_birth') ? Carbon::createFromFormat('d M Y', $request->input('date_of_birth'))->format('Y-m-d') : null,
                'emirates_id_number' => $request->input('emirates_id_number'),
                'emirates_id_expiry_date' => /* $request->input('emirates_id_expiry_date'), */$request->input('emirates_id_expiry_date') ? Carbon::createFromFormat('d M Y', $request->input('emirates_id_expiry_date'))->format('Y-m-d') : null,
                'emirates_id_file_path' => $emiratesIdFilePath,
                'business_owner' => $request->input('business_owner'),
                'designation' => $request->input('designation'),
                'saveasdraft' => isset($action) ? true : false,

                // Add other fields as needed
            ]
        );
        if (!$action) {

            $user = Auth::user();
            $user->step_number = 4;
            $user->save();
            return response()->json(['success' => true, 'message' => translate('Contact person stored successfully')]);

        }
        else {
            $user = Auth::user();
            $user->step_number = 3;
            $user->save();
            return response()->json(['success' => true, 'message' => translate('Draft Contact person saved successfully'),'save_as_draft' => true]);

        }

        // Return a response
    }
    public function verifyCode(Request $request)
    {

        if (!$request->email) {
            return response()->json(['message' => translate('Please Register !!')], 403);
        }

        // Validate the verification code
        $validator = Validator::make($request->all(), [
            'verification_code' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Check if the verification code exists and is not expired
        $verificationCode = VerificationCode::where('email', $request->email)
            ->where('code', $request->input('verification_code'))
            ->where('expires_at', '>', now())
            ->latest()  // Get the latest record
            ->first();

            if (!$verificationCode) {
                // Increment the attempt count
                $attempts = $request->session()->get('verification_attempts', 0);
                $attempts++;

                // Check if the limit is reached
                if ($attempts >= 3) {
                    // If the limit is reached, resend a new verification code
                    $this->resendCode($request);
                    $request->session()->put('verification_attempts', 0); // Reset attempt count
                    return response()->json(['errors' => ['verification_code' => [translate('Invalid verification code. Please check your email for a new code.')]]], 422);
                }

                $request->session()->put('verification_attempts', $attempts);

                return response()->json(['errors' => ['verification_code' => [translate('Invalid verification code.')]]], 422);
            }

            // Reset the attempt count on successful verification
            $request->session()->put('verification_attempts', 0);

        $user = User::where('email', $request->email)->first();
        if ($user->id != $user->owner_id && $user->owner_id != null) {
            $user->email_verified_at = now(); // Assuming you want to set the current timestamp
            $user->save();
            Auth::login($user);
            // Session::put('user_id', $user->id);
            $user = Auth::user();
            $user->step_number = 5;
            $user->status='Enabled';
            $user->save();
            return response()->json(['staff'=>true,'verif_staff_login' => true, 'success' => true, 'message' => translate('Verification successful')]);
        }
        elseif ($user) {
            $user->email_verified_at = now(); // Assuming you want to set the current timestamp
            $user->save();
            Auth::login($user);
            // Session::put('user_id', $user->id);
            $user = Auth::user();
            $user->step_number = 2;
            $user->save();
        }


        // Your additional logic for handling the verification
        // ...

        // Return a JSON response as needed
        return response()->json(['verif_login' => true, 'success' => true, 'message' => translate('Verification successful')]);
    }

    // Add a method to resend the verification code
    public function resendCode(Request $request)
    {

        $email = $request->input('email');



        if (!$email) {
            return response()->json(['message' => translate('Please Register !!')], 403);
        }
        // Delete existing verification codes for this email
        VerificationCode::where('email', $email)->delete();

        $newVerificationCode = rand(100000, 999999);
        $expirationTime = now()->addMinutes(10); // Set expiration time to 10 minutes
        VerificationCode::create([
            'email' => $email,
            'code' => $newVerificationCode,
            'expires_at' => $expirationTime,
        ]);

        Mail::to($request->email)->send(new VerificationCodeEmail($newVerificationCode));
        // Reset the attempt count when a new code is sent
        $request->session()->put('verification_attempts', 0);

        return response()->json(['success' => true, 'message' => translate('Verification code resent successfully.')]);
    }
    public function getArea($emirate_id = 0)
    {

        // Fetch Employees by Departmentid
        $empData['data'] = Area::orderby("name", "asc")
            ->select('id', 'name')
            ->where('emirate_id', $emirate_id)
            ->get();
            $currentLang = App::getLocale(); // Get the current language

            foreach ($empData['data'] as $area) {
                // Check current language and get the appropriate translation
                $area->name_translated = $area->getTranslation('name', $currentLang);
            }
        return response()->json($empData);
    }

    public function storeWarehouse(/* Request */StoreWarehouseRequest $request)
    {


        $action = $request->input('action');
        // it indicates the "save as draft" action.



        $user =  Auth::user();


            // Loop through the arrays and store each warehouse
            Warehouse::where('user_id', $user->id)->delete();
            if (isset($request->warehouse_name) && is_array($request->warehouse_name)) {


            foreach ($request->warehouse_name as $key => $value) {
                Warehouse::create([
                    'user_id' => $user->id,
                    'warehouse_name' => $request->warehouse_name[$key],
                    'emirate_id' => $request->state_warehouse[$key],
                    'area_id' => $request->area_warehouse[$key],
                    'address_street' => $request->street_warehouse[$key],
                    'address_building' => $request->building_warehouse[$key],
                    'address_unit' => $request->unit_warehouse[$key],
                    'saveasdraft' =>/*  $saveasdraft ?? true */ isset($action) ? true : false,

                ]);
            }
             }
             else {
                return response()->json(['success' => true, 'message' => translate('No Warehouses stored'),'save_as_draft' => isset($action) ? true :false,'infoMsg'=>true]);

             }
            if (!$action) {
                $user = Auth::user();
                $user->step_number = 5;
                $user->save();
                return response()->json(['success' => true, 'message' => translate('Warehouses stored successfully')]);
            }
            else {
                $user = Auth::user();
                $user->step_number = 4;
                $user->save();
                return response()->json(['success' => true, 'message' => translate('Draft Warehouses saved successfully'),'save_as_draft' => true]);

            }


    }

    public function storePayoutInfo(StorePayoutInfoRequest $request)
    {

        $action = $request->input('action');
        // it indicates the "save as draft" action.

        $ibanCertificatePath = null;
        if (isset($request->iban_certificate_old) && !$request->hasFile('iban_certificate'))
            $ibanCertificatePath = $request->iban_certificate_old;
        else if ($request->hasFile('iban_certificate'))
            $ibanCertificatePath = Storage::putFile('iban_certificates', $request->file('iban_certificate'));
        // Assuming you have a logged-in user


        // Create payout information
        $payoutInformation = PayoutInformation::updateOrCreate(
            [
                'user_id' => Auth::user()->id
            ],
            [

                'bank_name' => $request->bank_name,
                'account_name' => $request->account_name,
                'account_number' => $request->account_number,
                'iban' => $request->iban,
                'swift_code' => $request->swift_code,
                // 'iban_certificate' => $request->file('iban_certificate')->store('iban_certificates'),
                'iban_certificate' => $ibanCertificatePath,
                'saveasdraft' =>/*  $saveasdraft ?? true */ isset($action) ? true : false,

            ]
        );
        $user = Auth::user();
        $user->step_number = 5;
        $user->save();
        return response()->json(['success' => true, 'message' => translate('Draft Payout information saved successfully'),'save_as_draft' => true]);

        // if (!$action) {
        //     $user = Auth::user();
        //     $user->step_number = 6;
        //     // if (
        //     //     $user->business_information && $user->business_information->saveasdraft == 0  && $user->contact_people && $user->contact_people->saveasdraft == 0 && $user->payout_information && $user->payout_information->saveasdraft == 0 && count($user->warehouses) > 0  &&
        //     //     !$user->warehouses->contains('saveasdraft', 1)
        //     // )
        //     //     $user->steps = 1;


        //     $user->save();
        //     return response()->json(['finish' => true, 'success' => true, 'message' => 'Payout information stored successfully']);
        // } else {
            // return response()->json(['success' => true, 'message' => 'Payout information stored successfully']);
        // }
    }

    public function storeShopRegister(SellerRegistrationShopRequest $request) {

        // if (!isset($request->warehouse_name)) {
        //     return response()->json(['status' => 'error', 'message' => translate('Please add at least one warehouse.'),'redirectWh'=>true]);
        // }

        if ($request->input('vat_registered') == 1) {

            // If VAT is registered, handle VAT certificate and TRN
            if (isset($request->vat_certificate_old) && !$request->hasFile('vat_certificate'))
                $vatCertificatePath = $request->vat_certificate_old;
            elseif ($request->hasFile('vat_certificate'))
                $vatCertificatePath = Storage::putFile('vat_certificate', $request->file('vat_certificate'));

            $trn = $request->input('trn');
        } else {
            // If VAT is not registered, handle tax waiver
            if (isset($request->tax_waiver_old) && !$request->hasFile('tax_waiver'))
                $taxWaiverPath = $request->tax_waiver_old;
            elseif ($request->hasFile('tax_waiver'))
                $taxWaiverPath = Storage::putFile('tax_waiver', $request->file('tax_waiver'));
        }

        $civil_defense_approval = null;
        if (isset($request->civil_defense_approval_old))
            $civil_defense_approval = $request->civil_defense_approval_old;

        $trade_license_doc = null;
        if (isset($request->trade_license_doc_old))
            $trade_license_doc = $request->trade_license_doc_old;

              // Store or update BusinessInformation
        BusinessInformation::updateOrCreate(
            [
                'user_id' => Auth::user()->id
            ],
            [
                'trade_name' => ['en' => $request->trade_name_english, 'ar' => $request->trade_name_arabic],
                'eshop_name' => ['en' => $request->eshop_name_english, 'ar' => $request->eshop_name_arabic],
                'eshop_desc' => ['en' => $request->eshop_desc_en, 'ar' => $request->eshop_desc_ar],
                'trade_license_doc' => $request->hasFile('trade_license_doc') ?  $request->file('trade_license_doc')->store('trade_license_doc') : $trade_license_doc/* $request->file('trade_license_doc')->store('trade_license_docs') */,
                'license_issue_date' => /* $request->license_issue_date */Carbon::createFromFormat('d M Y', $request->license_issue_date)->format('Y-m-d'),
                'license_expiry_date' => /* $request->license_expiry_date */Carbon::createFromFormat('d M Y', $request->license_expiry_date)->format('Y-m-d'),
                'state' => $request->state,
                'area_id' => $request->area_id,
                'street' => $request->street,
                'building' => $request->building,
                'unit' => $request->unit,
                'po_box' => $request->po_box,
                'landline' => $request->landline,
                'vat_registered' => $request->vat_registered,
                'vat_certificate' => isset($vatCertificatePath) ? $vatCertificatePath : null,
                'trn' => isset($trn) ? $trn : null,
                'tax_waiver' => isset($taxWaiverPath) ? $taxWaiverPath : null,
                'civil_defense_approval' => $request->hasFile('civil_defense_approval') ?  $request->file('civil_defense_approval')->store('civil_defense_approval') : $civil_defense_approval,


            ]
        );

        // Handle file upload
        $emiratesIdFilePath = null;
        if (isset($request->emirates_id_file_old) && !$request->hasFile('emirates_id_file'))
            $emiratesIdFilePath = $request->emirates_id_file_old;
        else if ($request->hasFile('emirates_id_file'))
            $emiratesIdFilePath = Storage::putFile('emirates_ids', $request->file('emirates_id_file'));

        // Store contact person data
        $contactPerson = ContactPerson::updateOrCreate(
            [
                'user_id' => Auth::user()->id
            ],
            [
                'user_id' => auth()->user()->id, // Assuming you're using authentication
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'mobile_phone' => $request->input('mobile_phone'),
                'additional_mobile_phone' =>$request->input('additional_mobile_phone') != "+971" ? $request->input('additional_mobile_phone') : null,
                'nationality' => $request->input('nationality'),
                'date_of_birth' => /* $request->input('date_of_birth') */  Carbon::createFromFormat('d M Y', $request->input('date_of_birth'))->format('Y-m-d') ,
                'emirates_id_number' => $request->input('emirates_id_number'),
                'emirates_id_expiry_date' => /* $request->input('emirates_id_expiry_date'), */Carbon::createFromFormat('d M Y', $request->input('emirates_id_expiry_date'))->format('Y-m-d'),
                'emirates_id_file_path' => $emiratesIdFilePath,
                'business_owner' => $request->input('business_owner'),
                'designation' => $request->input('designation'),


                // Add other fields as needed
            ]
        );
        Warehouse::where('user_id', Auth::user()->id)->delete();
        if (isset($request->warehouse_name) && is_array($request->warehouse_name)) {
             // Loop through the arrays and store each warehouse

             foreach ($request->warehouse_name as $key => $value) {
                 Warehouse::create([
                     'user_id' => Auth::user()->id,
                     'warehouse_name' => $request->warehouse_name[$key],
                     'emirate_id' => $request->state_warehouse[$key],
                     'area_id' => $request->area_warehouse[$key],
                     'address_street' => $request->street_warehouse[$key],
                     'address_building' => $request->building_warehouse[$key],
                     'address_unit' => $request->unit_warehouse[$key],


                 ]);
             }
            }
             $ibanCertificatePath = null;
             if (isset($request->iban_certificate_old) && !$request->hasFile('iban_certificate'))
                 $ibanCertificatePath = $request->iban_certificate_old;
             else if ($request->hasFile('iban_certificate'))
                 $ibanCertificatePath = Storage::putFile('iban_certificates', $request->file('iban_certificate'));

                   // Create payout information
        $payoutInformation = PayoutInformation::updateOrCreate(
            [
                'user_id' => Auth::user()->id
            ],
            [

                'bank_name' => $request->bank_name,
                'account_name' => $request->account_name,
                'account_number' => $request->account_number,
                'iban' => $request->iban,
                'swift_code' => $request->swift_code,
                // 'iban_certificate' => $request->file('iban_certificate')->store('iban_certificates'),
                'iban_certificate' => $ibanCertificatePath,


            ]
        );

        $shop = Seller::updateOrCreate(
            [
                'user_id' => Auth::user()->id
            ],
            [
                'bank_name' => $request->bank_name,
                'bank_acc_name' => $request->account_name,
                'bank_acc_no' => $request->account_number,
                'bank_routing_no' => $request->iban,
                'verification_status' => 1,
            ]
        );

        $seller = Shop::updateOrCreate(
            [
                'user_id' => Auth::user()->id
            ],
            [
                'name' => Auth::user()->name,
                'verification_status' => 1,
                'slug' => $request->trade_name_english,
                'meta_title' => $request->eshop_name_english,
                'meta_description' => $request->eshop_desc_en,
                'bank_name' => $request->bank_name,
                'bank_acc_name' => $request->account_name,
                'bank_acc_no' => $request->account_number,
                'bank_routing_no' => $request->iban,
            ]
        );

        $user = Auth::user();
        $user->steps = 1;
        $user->status = 'Pending Approval';
        $user->owner_id = $user->id ;
        $user->save();
        if( !$user->stripe_id) {
            // Create a Stripe customer
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $stripeCustomer = Customer::create([
                'email' => $user->email,
                'name' => $user->name,
            ]);
            // Save the Stripe customer ID to the user's record
            $user->stripe_id = $stripeCustomer->id;
            $user->save();
        }


        $role = Role::where('name','pro')->first();
        $user->assignRole($role) ;
        $staff = new Staff;
        $staff->user_id = $user->id;
        $staff->role_id = $role->id;
        $staff->save();
        // Trigger the notification
        $admins = User::where('user_type','admin')->get(); // Fetch the first admin
        try {
        if ($admins->isNotEmpty()) {
            // Notify each admin user via Laravel notifications
            foreach ($admins as $admin) {
                $admin->notify(new NewVendorRegistration($user));
                Notification::send($admin, new NewRegistrationNotification($user));
            }
         }
         $user->notify(new VendorStatusChangedNotification("draft", "Pending Approval",null,null,null,$user->name));
         Notification::send($user, new CustomStatusNotification("draft", "Pending Approval"));
        } catch (\Exception $e) {

        }
        return response()->json(['finish' => true, 'success' => true, 'message' => 'Shop stored successfully']);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     public function getWords() {

        $dictionaryPath = public_path('dictionary/dictionary.txt') ;
        $words = File::lines($dictionaryPath);
        return response()->json($words);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
    public function seller_packages()
    {
        $seller_packages = SellerPackage::all();
        return view('frontend.package', compact('seller_packages'));
    }
}
