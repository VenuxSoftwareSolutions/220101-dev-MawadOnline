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
use App\Models\VerificationCode;
use Auth;
use Hash;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Support\Facades\Notification;
use Mail;
use Storage;
use Validator;

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
    public function create()
    {
        if (Auth::check()) {
            if ((Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'customer')) {
                flash(translate('Admin or Customer cannot be a seller'))->error();
                return back();
            }
            if (Auth::user()->user_type == 'seller') {
                flash(translate('This user already a seller'))->error();
                return back();
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
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $verificationCode = rand(100000, 999999); // Generate a 6-digit code
        $expirationTime = now()->addMinutes(10); // Set expiration time to 10 minutes



        $user = new User;
        $user->name = $request->first_name . " " . $request->last_name;
        $user->email = $request->email;
        $user->user_type = "seller";
        $user->password = Hash::make($request->password);
        $user->save();

        if ($user) {
            // Save the verification code and expiration time in the database
            VerificationCode::create([
                'email' => $request->email,
                'code' => $verificationCode,
                'expires_at' => $expirationTime,
            ]);

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
        // Validation logic

        $validatedData = Validator::make($request->all(),[
            'trade_name_english' => 'required|string|max:255',
            'trade_name_arabic' => 'required|string|max:255',
            'trade_license_doc' => 'required|file|mimes:pdf,doc,docx|max:5120',
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
            'vat_certificate' => $request->vat_registered == 1 ? 'required_if:vat_registered,1|file|mimes:pdf,doc,docx|max:5120' : '',
            'trn' => $request->vat_registered == 1 ? 'required_if:vat_registered,1|string|max:20' : '',
            'tax_waiver' => $request->vat_registered == 0 ? 'required_if:vat_registered,0|file|mimes:pdf,doc,docx|max:5120' : '',
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
        if ($validatedData['vat_registered'] == 1) {
            // If VAT is registered, handle VAT certificate and TRN
            $vatCertificatePath = Storage::putFile('vat_certificates', $request->file('vat_certificate'));
            $trn = $request->input('trn');
        } else {
            // If VAT is not registered, handle tax waiver
            $taxWaiverPath = Storage::putFile('tax_waivers', $request->file('tax_waiver'));
        }

        // Store or update BusinessInformation
        BusinessInformation::updateOrCreate(
            [
                'user_id' => 39
            ],
            [
                'trade_name' => ['en' => $validatedData['trade_name_english'], 'ar' => $validatedData['trade_name_arabic']],
                'eshop_name' => ['en' => $validatedData['eshop_name_english'], 'ar' => $validatedData['eshop_name_arabic']],
                'eshop_desc' => ['en' => $validatedData['eshop_desc_en'], 'ar' => $validatedData['eshop_desc_ar']],
                'trade_license_doc' => $request->file('trade_license_doc')->store('trade_license_docs'),
                'license_issue_date' => $validatedData['license_issue_date'],
                'license_expiry_date' => $validatedData['license_expiry_date'],
                'state' => $validatedData['state'],
                'area_id' => $validatedData['area_id'],
                'street' => $validatedData['street'],
                'building' => $validatedData['building'],
                'unit' => $validatedData['unit'],
                'po_box' => $validatedData['po_box'],
                'landline' => $validatedData['landline'],
                'vat_registered' => $validatedData['vat_registered'],
                'vat_certificate' => isset($vatCertificatePath) ? $vatCertificatePath : null,
                'trn' => isset($trn) ? $trn : null,
                'tax_waiver' => isset($taxWaiverPath) ? $taxWaiverPath : null,
                'civil_defense_approval' => $request->hasFile('civil_defense_approval') ?  $request->file('civil_defense_approval')->store('civil_defense_approvals'):null,
            ]
        );

        // Return a response
        return response()->json(['message' => 'Business info stored successfully']);


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
            ->first();

        if (!$verificationCode) {
            return response()->json(['errors' => ['verification_code' => ['Invalid verification code.']]], 422);
        }

        // Your additional logic for handling the verification
        // ...

        // Return a JSON response as needed
        return response()->json(['success' => true, 'message' => 'Verification successful']);
    }

    // Add a method to resend the verification code
    public function resendCode(Request $request)
    {
        $email = $request->input('email');

        // Check if the email exists in your database or user records
        // Add your logic to generate and send a new verification code

        // Generate a new verification code
        $newVerificationCode = rand(100000, 999999);

        // Update the code in your database or user records
        // For example, if you have a VerificationCode model, you might do something like this:
        $verification = VerificationCode::where('email', $email)->first();

        if ($verification) {
            $verification->update(['code' => $newVerificationCode]);
        } else {
            // Handle the case where the email is not found
            return response()->json(['success' => false, 'message' => 'Email not found.']);
        }

        // Send the new verification code to the user's email
        // You need to implement your own logic for sending emails here

        // For example, using Laravel's built-in Mail facade:
        // Mail::to($email)->send(new VerificationCodeEmail($newVerificationCode));

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
