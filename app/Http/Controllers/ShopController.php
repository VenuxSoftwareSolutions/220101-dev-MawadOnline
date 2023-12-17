<?php

namespace App\Http\Controllers;

use App\Http\Requests\SellerRegistrationRequest;
use App\Mail\VerificationCodeEmail;
use App\Models\Area;
use App\Models\BusinessInformation;
use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\User;
use App\Models\BusinessSetting;
use App\Models\ContactPerson;
use App\Models\PayoutInformation;
use App\Models\VerificationCode;
use App\Models\Warehouse;
use Auth;
use Hash;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Support\Facades\Notification;
use Mail;
use Session;
use Storage;
use Validator;
use Illuminate\Validation\Rule;

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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        if (Auth::check()) {
            if ((Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'customer')) {
                flash(translate('Admin or Customer cannot be a seller'))->error();
                return back();
            }
            if (Auth::user()->user_type == 'seller' && Auth::user()->steps) {
                // flash(translate('This user already a seller'))->error();
                // return back();
                return redirect()->route('dashboard') ;
            }
            if (Auth::user()->user_type == 'seller' && Auth::user()->steps == 0) {
                $user=Auth::user() ;
                $step_number = Auth::user()->step_number ;

                flash(translate('You need to complete all steps to create a vendor account.'))->error();
                return view('frontend.seller_form',compact('step_number',"user"));
            }
        } else {
            return view('frontend.seller_form');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(/* SellerRegistrationRequest */Request $request)
    {

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required','email',
                Rule::unique('users', 'email')->where(function ($query) {
                    $query->whereNotNull('email_verified_at');
                }),
            ],
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $verificationCode = rand(100000, 999999); // Generate a 6-digit code
        $expirationTime = now()->addMinutes(10); // Set expiration time to 10 minutes



        // $user = new User;
        // $user->name = $request->first_name . " " . $request->last_name;
        // $user->email = $request->email;
        // $user->user_type = "seller";
        // $user->password = Hash::make($request->password);
        // $user->save();
        $user = User::updateOrCreate(
            ['email' => $request->email],
            [
                'name' => $request->first_name . " " . $request->last_name,
                'first_name' => $request->first_name ,
                'last_name' => $request->last_name ,
                'user_type' => "seller", // Set the user_type explicitly
                'password' => Hash::make($request->password),
            ]
        );

        if ($user) {
            Session::forget('user_id');

            // Save the verification code and expiration time in the database
            VerificationCode::create([
                'email' => $request->email,
                'code' => $verificationCode,
                'expires_at' => $expirationTime,
            ]);

            $user->step_number= 1 ;
            $user->save() ;


            // Send the verification code to the user's email
            // Mail::to($request->email)->send(new VerificationCodeEmail($verificationCode));
        }
        return response()->json(['success' => true, 'message' => 'Your success message']);



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
    public function storeBusinessInfo(Request $request)
    {
        if (!Auth::check()) {

            return response()->json(['loginFailed' => 'You are not logged in.'], 401);
        }

        // Check if the user's account is not verified (assuming 'verified' is a column in the users table)
        if (!Auth::user()->email_verified_at) {
            return response()->json(['loginFailed' => 'Your account is not verified.'], 403);
        }
        // Validation logic

        // if(Session::get('user_id')== null) {

        // }


        $validatedData = Validator::make($request->all(),[
            'trade_name_english' => 'required|string|max:255',
            'trade_name_arabic' => 'required|string|max:255',
            'trade_license_doc' => !isset( $request->trade_license_doc_old) ? 'required|file|mimes:pdf,doc,docx|max:5120':'',
            'eshop_name_english' => 'required|string|max:255',
            'eshop_name_arabic' => 'required|string|max:255',
            'eshop_desc_en' => 'nullable|string',
            'eshop_desc_ar' => 'nullable|string',
            'license_issue_date' => 'required|date',
            'license_expiry_date' => 'required|date|after:license_issue_date',
            'state' => 'required|exists:emirates,id',
            'area_id' => 'required|exists:areas,id',
            'street' => 'required|string|max:255',
            'building' => 'required|string|max:255',
            'unit' => 'nullable|string|max:255',
            'po_box' => 'nullable|string|max:255',
            'landline' => 'nullable|string|max:20',
            'vat_registered' => 'required|boolean',
            'vat_certificate' => $request->vat_registered == 1 && !isset( $request->vat_certificate_old) ? 'required_if:vat_registered,1|file|mimes:pdf,doc,docx|max:5120' : '',
            'trn' => $request->vat_registered == 1 ? 'required_if:vat_registered,1|string|max:20' : '',
            'tax_waiver' => $request->vat_registered == 0 && !isset( $request->tax_waiver_old)  ? 'required_if:vat_registered,0|file|mimes:pdf,doc,docx|max:5120' : '',
            'civil_defense_approval' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]
        , [
            // Custom error messages
            'vat_certificate.required_if' => 'The VAT certificate is required when VAT is registered.',
            'vat_certificate.file' => 'The VAT certificate must be a file of type: pdf, doc, docx.',
            'vat_certificate.mimes' => 'The VAT certificate must be a file of type: pdf, doc, docx.',
            'trn.required_if' => 'The TRN is required when VAT is registered.',
            'trn.string' => 'The TRN must be a string.',
            'trn.max' => 'The TRN may not be greater than :max characters.',
            'tax_waiver.required_if' => 'The tax waiver certificate is required when VAT is not registered.',
            'tax_waiver.file' => 'The tax waiver certificate must be a file of type: pdf, doc, docx.',
            'tax_waiver.mimes' => 'The tax waiver certificate must be a file of type: pdf, doc, docx.',
            'area_id.required' => 'The area is required.',
            'area_id.exists' => 'Invalid area selected.',
        ]);
        if ($validatedData->fails()) {

            return response()->json(['errors' => $validatedData->errors()], 422);
        }


        if ($request->input('vat_registered') == 1) {

            // If VAT is registered, handle VAT certificate and TRN
            if(isset($request->vat_certificate_old) && ! $request->hasFile('vat_certificate') )
                $vatCertificatePath = $request->vat_certificate_old ;
            else
                $vatCertificatePath = Storage::putFile('vat_certificates', $request->file('vat_certificate'));

            $trn = $request->input('trn');
        } else {
            // If VAT is not registered, handle tax waiver
            if(isset($request->tax_waiver_old) && ! $request->hasFile('tax_waiver'))
                $taxWaiverPath = $request->tax_waiver_old ;
            else
                  $taxWaiverPath = Storage::putFile('tax_waivers', $request->file('tax_waiver'));
        }

        $civil_defense_approval = null ;
        if(isset($request->civil_defense_approval_old))
            $civil_defense_approval=$request->civil_defense_approval_old ;

            $trade_license_doc = null ;
            if(isset($request->trade_license_doc_old))
                $trade_license_doc=$request->trade_license_doc_old ;

        // Store or update BusinessInformation
        BusinessInformation::updateOrCreate(
            [
                'user_id' => Auth::user()->id
            ],
            [
                'trade_name' => ['en' => $request->trade_name_english, 'ar' => $request->trade_name_arabic],
                'eshop_name' => ['en' => $request->eshop_name_english, 'ar' => $request->eshop_name_arabic],
                'eshop_desc' => ['en' => $request->eshop_desc_en, 'ar' => $request->eshop_desc_ar],
                'trade_license_doc' => $request->hasFile('trade_license_doc') ?  $request->file('trade_license_doc')->store('trade_license_doc'):$trade_license_doc/* $request->file('trade_license_doc')->store('trade_license_docs') */,
                'license_issue_date' => $request->license_issue_date,
                'license_expiry_date' => $request->license_expiry_date,
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
                'civil_defense_approval' => $request->hasFile('civil_defense_approval') ?  $request->file('civil_defense_approval')->store('civil_defense_approvals'):$civil_defense_approval,
            ]
        );

        $user = Auth::user() ;
        $user->step_number= 3 ;
        $user->save() ;

        // Return a response
        return response()->json(['success' => true,'message' => 'Business info stored successfully']);


    }

    public function storeContactPerson(Request $request) {
        if (!Auth::check()) {

            return response()->json(['loginFailed' => 'You are not logged in.'], 401);
        }

        // Check if the user's account is not verified (assuming 'verified' is a column in the users table)
        if (!Auth::user()->email_verified_at) {
            return response()->json(['loginFailed' => 'Your account is not verified.'], 403);
        }
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'mobile_phone' => ['required', 'string', 'max:20', new \App\Rules\UaeMobilePhone],
            'additional_mobile_phone' => 'nullable|string|max:20',
            'nationality' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'emirates_id_number' => 'required|string|max:255',
            'emirates_id_expiry_date' => 'required|date',
            'emirates_id_file' => !isset( $request->emirates_id_file_old) ?  'required|file|mimes:pdf,doc,docx|max:5120':'',
            'business_owner' => 'required|boolean',
            'designation' => 'required|string|max:255',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

    // Handle file upload
    if(isset($request->emirates_id_file_old) && ! $request->hasFile('emirates_id_file'))
            $emiratesIdFilePath = $request->emirates_id_file_old ;
    else
            $emiratesIdFilePath = Storage::putFile('emirates_ids', $request->file('emirates_id_file'));

    // Store contact person data
    $contactPerson = ContactPerson::updateOrCreate(
        [
            'user_id' => Auth::user()->id
        ],[
        'user_id' => auth()->user()->id, // Assuming you're using authentication
        'first_name' => $request->input('first_name'),
        'last_name' => $request->input('last_name'),
        'email' => $request->input('email'),
        'mobile_phone' => $request->input('mobile_phone'),
        'additional_mobile_phone' => $request->input('additional_mobile_phone'),
        'nationality' => $request->input('nationality'),
        'date_of_birth' => $request->input('date_of_birth'),
        'emirates_id_number' => $request->input('emirates_id_number'),
        'emirates_id_expiry_date' => $request->input('emirates_id_expiry_date'),
        'emirates_id_file_path' => $emiratesIdFilePath,
        'business_owner' => $request->input('business_owner'),
        'designation' => $request->input('designation'),
        // Add other fields as needed
    ]);
    $user = Auth::user() ;
    $user->step_number= 4 ;
    $user->save() ;

    // Return a response
    return response()->json(['success' => true,'message' => 'Contact person stored successfully']);
    }
    public function verifyCode(Request $request)
    {

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
            return response()->json(['errors' => ['verification_code' => ['Invalid verification code.']]], 422);
        }

        $user = User::where('email',$request->email)->first() ;
        if($user) {
            $user->	email_verified_at = now(); // Assuming you want to set the current timestamp
            $user->save() ;
            Auth::login($user);
            Session::put('user_id', $user->id);
            $user = Auth::user() ;
            $user->step_number= 2 ;
            $user->save() ;

        }


        // Your additional logic for handling the verification
        // ...

        // Return a JSON response as needed
        return response()->json(['verif_login'=>true,'success' => true, 'message' => 'Verification successful']);
    }

    // Add a method to resend the verification code
    public function resendCode(Request $request)
    {

        $email = $request->input('email');

        $newVerificationCode = rand(100000, 999999);
        $expirationTime = now()->addMinutes(10); // Set expiration time to 10 minutes

        if (!$email) {
            return response()->json(['success' => false, 'message' => 'Email not found.']);
        }

        VerificationCode::create([
            'email' => $email,
            'code' => $newVerificationCode,
            'expires_at' => $expirationTime,
        ]);



        return response()->json(['success' => true, 'message' => 'Verification code resent successfully.']);
    }
    public function getArea($emirate_id = 0)
    {

        // Fetch Employees by Departmentid
        $empData['data'] = Area::orderby("name", "asc")
            ->select('id', 'name')
            ->where('emirate_id', $emirate_id)
            ->get();

        return response()->json($empData);
    }

    public function storeWarehouse(Request $request) {
        if (!Auth::check()) {

            return response()->json(['loginFailed' => 'You are not logged in.'], 401);
        }

        // Check if the user's account is not verified (assuming 'verified' is a column in the users table)
        if (!Auth::user()->email_verified_at) {
            return response()->json(['loginFailed' => 'Your account is not verified.'], 403);
        }
        $validator = Validator::make($request->all(), [
            'warehouse_name.*' => 'required',
            'state.*' => 'required',
            'area.*' => 'required',
            'street.*' => 'required',
            'building.*' => 'required',
            'unit.*' => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user =  Auth::user() ;

        try {
            // Loop through the arrays and store each warehouse
            Warehouse::where('user_id',$user->id )->delete() ;
            foreach ($request->warehouse_name as $key => $value) {
                Warehouse::create([
                    'user_id' => $user->id,
                    'warehouse_name' => $request->warehouse_name[$key],
                    'emirate_id' => $request->state[$key],
                    'area_id' => $request->area[$key],
                    'address_street' => $request->street[$key],
                    'address_building' => $request->building[$key],
                    'address_unit' => $request->unit[$key],
                ]);
            }
            $user = Auth::user() ;
            $user->step_number= 5 ;
            $user->save() ;

            return response()->json(['success' => true,'message' => 'Warehouses stored successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function storePayoutInfo(Request $request)
{
    if (!Auth::check()) {

        return response()->json(['loginFailed' => 'You are not logged in.'], 401);
    }

    // Check if the user's account is not verified (assuming 'verified' is a column in the users table)
    if (!Auth::user()->email_verified_at) {
        return response()->json(['loginFailed' => 'Your account is not verified.'], 403);
    }
    $validator = Validator::make($request->all(), [
        'bank_name' => 'required|string|max:255',
        'account_name' => 'required|string|max:255',
        'account_number' => 'required|string|max:255',
        'iban' => 'required|string|max:255',
        'swift_code' => 'required|string|max:255',
        'iban_certificate' =>  !isset( $request->iban_certificate_old) ?  'required|file|mimes:pdf,doc,docx|max:5120':'',
    ], [
        // Custom error messages if needed
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }
    if(isset($request->iban_certificate_old) && ! $request->hasFile('iban_certificate'))
    $ibanCertificatePath = $request->iban_certificate_old ;
else
    $ibanCertificatePath = Storage::putFile('iban_certificates', $request->file('iban_certificate'));
    // Assuming you have a logged-in user


        // Create payout information
        $payoutInformation = PayoutInformation::updateOrCreate(
            [
                'user_id' => Auth::user()->id
            ],[

            'bank_name' => $request->bank_name,
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
            'iban' => $request->iban,
            'swift_code' => $request->swift_code,
            // 'iban_certificate' => $request->file('iban_certificate')->store('iban_certificates'),
            'iban_certificate' => $ibanCertificatePath,

        ]);
        $user = Auth::user() ;
        $user->step_number= 6 ;
        if($user->business_information && $user->contact_people && $user->payout_information && count($user->warehouses)>0  )
            $user->steps= 1 ;


        $user->save() ;

        return response()->json(['finish'=> true ,'success' => true,'message' => 'Payout information stored successfully']);

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
        //
    }

    public function destroy($id)
    {
        //
    }
}
