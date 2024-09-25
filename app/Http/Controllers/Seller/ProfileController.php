<?php

namespace App\Http\Controllers\Seller;

use Auth;
use Hash;
use Notification;
use Carbon\Carbon;
use App\Models\Tour;
use App\Models\User;
use App\Models\Emirate;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\UploadedFile;
use App\Models\ProposedPayoutChange;
use App\Http\Requests\SellerProfileRequest;
use App\Notifications\VendorProfileChangesNotification;
use App\Notifications\VendorProfileChangesWebNotification;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $user = User::find(Auth::user()->owner_id);
        $user = Auth::user();

        /*         $addresses = $user->addresses; */
        $emirates = Emirate::all();
        $proposedPayoutChange = ProposedPayoutChange::where('user_id', $user->id)->latest()->first();

        if ($proposedPayoutChange && ($proposedPayoutChange->status=="approved" || $proposedPayoutChange->status=="rejected" )  ) {
            $proposedPayoutChange = null ;
        }

        $tour_steps=Tour::orderBy('step_number')->get();
        // dd($proposedPayoutChange->modified_fields->getNewValue('bank_name')) ;
        return view('seller.profile.index', compact('user', 'emirates', 'proposedPayoutChange' , 'tour_steps'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SellerProfileRequest $request, $id)
    {
        if (env('DEMO_MODE') == 'On') {
            flash(translate('Sorry! the action is not permitted in demo '))->error();
            return back();
        }
        $staff = User::findOrFail($id);
        $user = User::findOrFail($staff->owner_id);
        $user->name = $request->name;
        $user->phone = $request->phone;

        if ($request->new_password != null && ($request->new_password == $request->confirm_password)) {
            $user->password = Hash::make($request->new_password);
        }

        $user->avatar_original = $request->photo;

        $shop = $user->shop;

        if ($shop) {
            $shop->cash_on_delivery_status = $request->cash_on_delivery_status;
            $shop->bank_payment_status = $request->bank_payment_status;
            $shop->bank_name = $request->bank_name;
            $shop->bank_acc_name = $request->bank_acc_name;
            $shop->bank_acc_no = $request->bank_acc_no;
            $shop->bank_routing_no = $request->bank_routing_no;

            $shop->save();
        }

        $user->save();

        flash(translate('Your Profile has been updated successfully!'))->success();
        return back();
    }

    public function updatePersonalInfo(Request $request) {

        // Validate the incoming request data
    $request->validate([
        'first_name_personal' => [
            'required',
            'string',
            'max:255',
            'regex:/^[^\d]+$/' // Allow only alphabetic characters (no numbers)
        ],
        'last_name_personal' => [
            'required',
            'string',
            'max:255',
            'regex:/^[^\d]+$/' // Allow only alphabetic characters (no numbers)
        ],

    ]);
       $user=Auth::user() ;

        // Update the user model with the provided data
        $user->name = $request->first_name_personal . " " . $request->last_name_personal;
        $user->first_name = $request->first_name_personal;
        $user->last_name = $request->last_name_personal;

        // Save the updated user model
        $user->save();

        // Optionally, you can return a response or redirect somewhere
        return redirect()->back()->with('success', 'User information updated successfully');
    }

    public function updateProfile(Request $request)
    {
        // Get the existing payout information
        $user = Auth::user();
        $user_id = $user->id ;
        // Validate the incoming request data
        $request->validate([
            'bank_name' => 'required|string|max:128|regex:/\D/',
            'account_name' => 'required|string|max:128|regex:/\D/',
            'account_number' => 'required|string|max:30',
            'iban' => 'required|string|max:34',
            'swift_code' => 'required|string|max:16',
            'iban_certificate' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (!empty($value) && $value instanceof UploadedFile) {
                        // If the input value is a file (UploadedFile), validate file upload
                        $allowedMimeTypes = ['application/pdf', 'image/jpeg', 'image/png'];
                        if (!in_array($value->getMimeType(), $allowedMimeTypes)) {
                            $fail('The '.$attribute.' must be a PDF, JPEG, or PNG file.');
                        }
                        if ($value->getSize() > 5120 * 1024) {
                            $fail('The '.$attribute.' must be less than or equal to 5120 KB.');
                        }
                    }
                },
            ],
            'first_name' => 'required|string|max:64|regex:/\D/',
            'last_name' => 'required|string|max:64|regex:/\D/',
            'email' => [
                'required',
                'email',
                Rule::unique('contact_people')->where(function ($query) use ($user_id) {
                    return $query->where('user_id', '<>', $user_id);
                }),
            ],
            'mobile_phone' =>  ['required', 'string', 'max:16', new \App\Rules\UaeMobilePhone],
            'additional_mobile_phone' => ['nullable', 'string', 'max:16', new \App\Rules\UaeMobilePhone],
            'nationality' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:-18 years',
            'emirates_id_number' => ['required', 'string', 'max:15', 'regex:/^[0-9]{15}$/'],
            'emirates_id_expiry_date' => 'required|date|after_or_equal:today',
            'emirates_id_file_path' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!empty($value) && $value instanceof UploadedFile) {
                        // If the input value is a file (UploadedFile), validate file upload
                        $allowedMimeTypes = ['application/pdf', 'image/jpeg', 'image/png'];
                        if (!in_array($value->getMimeType(), $allowedMimeTypes)) {
                            $fail('The Emirates ID must be a PDF, JPEG, or PNG file.');
                        }
                        if ($value->getSize() > 5120 * 1024) {
                            $fail('The Emirates ID must be less than or equal to 5120 KB.');
                        }
                    }
                },
            ],
            'business_owner' => 'required|boolean',
            'designation' => 'required|string|max:64|regex:/\D/',
            'trade_name_english' => 'required|string|max:128|regex:/\D/',
            'trade_name_arabic' => 'required|string|max:256|regex:/\D/',
            'eshop_name_english' => 'required|string|max:128|regex:/\D/',
            'eshop_name_arabic' => 'required|string|max:256|regex:/\D/',
            'eshop_desc_english' => 'nullable|string|regex:/\D/',
            'eshop_desc_arabic' => 'nullable|string|regex:/\D/',
            'license_issue_date' => 'required|date',
            'license_expiry_date' => 'required|date|after_or_equal:today|after_or_equal:license_issue_date',
            'state' => 'required|exists:emirates,id',
            'area_id' => 'required|exists:areas,id',
            'street' => 'required|string|max:128|regex:/\D/',
            'building' => 'required|string|max:64|regex:/\D/',
            'unit' => 'nullable|string|max:64',
            'po_box' => 'nullable|string|max:32',
            'landline' => 'nullable|string|max:16',
            'vat_registered' => 'required|boolean',

            'trade_license_doc' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (!empty($value) && $value instanceof UploadedFile) {
                        // If the input value is a file (UploadedFile), validate file upload
                        $allowedMimeTypes = ['application/pdf', 'image/jpeg', 'image/png'];
                        if (!in_array($value->getMimeType(), $allowedMimeTypes)) {
                            $fail('The '.$attribute.' must be a PDF, JPEG, or PNG file.');
                        }
                        if ($value->getSize() > 5120 * 1024) {
                            $fail('The '.$attribute.' must be less than or equal to 5120 KB.');
                        }
                    }
                },
            ],
            'trn' => $request->vat_registered == 1 ? 'required|string|max:20' : '',
            'vat_certificate' => $request->vat_registered == 1 ? [
                'required',
                function ($attribute, $value, $fail) {
                    if (!empty($value) && $value instanceof UploadedFile) {
                        // If the input value is a file (UploadedFile), validate file upload
                        $allowedMimeTypes = ['application/pdf', 'image/jpeg', 'image/png'];
                        if (!in_array($value->getMimeType(), $allowedMimeTypes)) {
                            $fail('The '.$attribute.' must be a PDF, JPEG, or PNG file.');
                        }
                        if ($value->getSize() > 5120 * 1024) {
                            $fail('The '.$attribute.' must be less than or equal to 5120 KB.');
                        }
                    }
                },
            ] :'',
            // 'tax_waiver' => $request->vat_registered == 0 ? [
            //     'required',
            //     function ($attribute, $value, $fail) {
            //         if (!empty($value) && $value instanceof UploadedFile) {
            //             // If the input value is a file (UploadedFile), validate file upload
            //             $allowedMimeTypes = ['application/pdf', 'image/jpeg', 'image/png'];
            //             if (!in_array($value->getMimeType(), $allowedMimeTypes)) {
            //                 $fail('The '.$attribute.' must be a PDF, JPEG, or PNG file.');
            //             }
            //             if ($value->getSize() > 5120 * 1024) {
            //                 $fail('The '.$attribute.' must be less than or equal to 5120 KB.');
            //             }
            //         }
            //     },
            // ] :'',
            'civil_defense_approval' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (!empty($value) && $value instanceof UploadedFile) {
                        // If the input value is a file (UploadedFile), validate file upload
                        $allowedMimeTypes = ['application/pdf', 'image/jpeg', 'image/png'];
                        if (!in_array($value->getMimeType(), $allowedMimeTypes)) {
                            $fail('The '.$attribute.' must be a PDF, JPEG, or PNG file.');
                        }
                        if ($value->getSize() > 5120 * 1024) {
                            $fail('The '.$attribute.' must be less than or equal to 5120 KB.');
                        }
                    }
                },
            ]
        ]);

        $existingPayoutInformation = $user->payout_information;
        $existingBusinessInformation =  $user->business_information;
        $existingContactInformation =  $user->contact_people;

        // List of specific keys to check
        $keysToCheckPayout = ['bank_name', 'account_name', 'account_number', 'iban', 'swift_code', 'iban_certificate'];
        $keysToCheckBusiness = [
            'trade_name_english', 'trade_name_arabic', 'trade_license_doc', 'eshop_name_english', 'eshop_name_arabic', 'eshop_desc_english', 'eshop_desc_arabic', 'license_issue_date',
            'license_expiry_date', 'state', 'area_id', 'street', 'building', 'unit', 'po_box', 'landline', 'vat_registered', 'vat_certificate', 'trn'/* , 'tax_waiver' */, 'civil_defense_approval'
        ];
        $keysToCheckContact = ['first_name', 'last_name', 'email', 'mobile_phone', 'additional_mobile_phone', 'nationality','date_of_birth','emirates_id_number','emirates_id_expiry_date',
                               'emirates_id_file_path','business_owner','designation'];

        // Initialize array to store modified fields
        $modifiedFields = [];

        // Iterate through the request data and check each key against the list
        foreach ($request->all() as $key => $value) {
            if (in_array($key, $keysToCheckPayout)) {
                // Check if the value is a file
                if ($request->hasFile($key)) {

                    // Handle file upload
                    $file = $request->file($key);
                    $file_path = $file->store('iban_certificates');

                    // For the purpose of this example, assume 'path_to_store' is the directory where you want to store the file
                    $modifiedFields[] = [
                        'field' => $key,
                        'old_value' => $existingPayoutInformation->{$key},
                        'new_value' => $file_path, // Save the file path instead of the file contents
                    ];
                } else {
                    // Compare the value with the existing value if it exists
                    if ($existingPayoutInformation && $value && $existingPayoutInformation->{$key} != $value) {
                        $modifiedFields[] = [
                            'field' => $key,
                            'old_value' => $existingPayoutInformation->{$key},
                            'new_value' => $value,
                        ];
                    }
                }
            } elseif (in_array($key, $keysToCheckBusiness)) {
                // Check if the value is a file
                if ($request->hasFile($key)) {

                    // Handle file upload
                    $file = $request->file($key);
                    $file_path = $file->store($key);

                    // For the purpose of this example, assume 'path_to_store' is the directory where you want to store the file
                    $modifiedFields[] = [
                        'field' => $key,
                        'old_value' => $existingBusinessInformation->{$key},
                        'new_value' => $file_path, // Save the file path instead of the file contents
                    ];
                } else {
                    if (strpos($key, '_english') !== false || strpos($key, '_arabic') !== false) {
                        if (strpos($key, '_english') !== false) {
                            // English translation
                            $language = 'en';
                            // Remove the '_english' suffix from $key
                            $translationKey = str_replace('_english', '', $key);
                        } elseif (strpos($key, '_arabic') !== false) {
                            // Arabic translation
                            $language = 'ar';
                            // Remove the '_arabic' suffix from $key
                            $translationKey = str_replace('_arabic', '', $key);
                        }
                        // Get the translation
                        $existingTranslateKeyName = $existingBusinessInformation->getTranslation($translationKey, $language, false);

                        // Compare the value with the existing value if it exists
                        if ($existingBusinessInformation && ($existingTranslateKeyName != $value || is_null($value) )) {
                            if($value != null && $value !== '') {
                                $modifiedFields[] = [
                                    'field' => $key,
                                    'old_value' => $existingTranslateKeyName,
                                    'new_value' => $value,
                                ];
                            }
                            else {
                                $existingBusinessInformation->{$translationKey} = [$language=>null] ;
                                $existingBusinessInformation->save() ;
                            }

                        }
                    } else {
                        if(strpos($key, '_date'))
                            $value=Carbon::createFromFormat('d M Y',$value)->format('Y-m-d') ;
                        // Compare the value with the existing value if it exists
                        if ($existingBusinessInformation && ($existingBusinessInformation->{$key} != $value || is_null($value )) ) {
                            if($value != null && $value !== '') {
                                $modifiedFields[] = [
                                    'field' => $key,
                                    'old_value' => $existingBusinessInformation->{$key},
                                    'new_value' => $value,
                                ];
                            }
                            else {
                                    $existingBusinessInformation->{$key} = null ;
                                    $existingBusinessInformation->save();
                            }
                        }
                    }
                }
            } elseif (in_array($key, $keysToCheckContact)) {

                // Check if the value is a file
                if ($request->hasFile($key)) {

                    // Handle file upload
                    $file = $request->file($key);
                    $file_path = $file->store('emirates_ids');

                    // For the purpose of this example, assume 'path_to_store' is the directory where you want to store the file
                    $modifiedFields[] = [
                        'field' => $key,
                        'old_value' => $existingContactInformation->{$key},
                        'new_value' => $file_path, // Save the file path instead of the file contents
                    ];
                } else {

                        if(strpos($key, 'date') !== false) {

                            $value=Carbon::createFromFormat('d M Y',$value)->format('Y-m-d') ;
                        }
                        // Compare the value with the existing value if it exists
                        if ($existingContactInformation && ($existingContactInformation->{$key} != $value|| is_null($value))) {

                            if($value != null && $value !== '') {
                                $modifiedFields[] = [
                                    'field' => $key,
                                    'old_value' => $existingContactInformation->{$key},
                                    'new_value' => $value,
                                ];
                            }
                            else {
                                $existingContactInformation->{$key} = null ;
                                $existingContactInformation->save();
                            }


                        }
                    }
                }

        }

        if (count($modifiedFields) > 0) {
            // Store modified fields in the database or an array
            ProposedPayoutChange::create([
                'user_id' => $user->id,
                'modified_fields' => json_encode($modifiedFields),
                'status' => 'pending',
            ]);
        // Trigger the notification
        $admins = User::where('user_type','admin')->get(); // Fetch the first admin
        if ($admins->isNotEmpty()) {
            // Notify each admin user via Laravel notifications
            foreach ($admins as $admin) {
                $admin->notify(new VendorProfileChangesNotification($user,$modifiedFields));
                Notification::send($admin, new VendorProfileChangesWebNotification($user));
            }
         }
         return back()->with('success', trans('profile.changes_proposed'));

        }



        // Notify the user that their changes have been proposed for review
        return back();
    }

    public function helpCenter(Request $request){
        $user = Auth::user();

        /*         $addresses = $user->addresses; */
        $emirates = Emirate::all();
        $proposedPayoutChange = ProposedPayoutChange::where('user_id', $user->id)->latest()->first();

        if ($proposedPayoutChange && ($proposedPayoutChange->status=="approved" || $proposedPayoutChange->status=="rejected" )  ) {
            $proposedPayoutChange = null ;
        }

        $tour_steps=Tour::orderBy('step_number')->get();
        return view('seller.help_centre.help-center', compact('user', 'emirates', 'proposedPayoutChange' , 'tour_steps'));
    }


    public function AccountRegistration(Request $request){
        $user = Auth::user();

        /*         $addresses = $user->addresses; */
        $emirates = Emirate::all();
        $proposedPayoutChange = ProposedPayoutChange::where('user_id', $user->id)->latest()->first();

        if ($proposedPayoutChange && ($proposedPayoutChange->status=="approved" || $proposedPayoutChange->status=="rejected" )  ) {
            $proposedPayoutChange = null ;
        }

        $tour_steps=Tour::orderBy('step_number')->get();
        return view('seller.help_centre.AccountRegistration', compact('user', 'emirates', 'proposedPayoutChange' , 'tour_steps'));
    }

    public function ProductManagement(Request $request){
        $user = Auth::user();

        /*         $addresses = $user->addresses; */
        $emirates = Emirate::all();
        $proposedPayoutChange = ProposedPayoutChange::where('user_id', $user->id)->latest()->first();

        if ($proposedPayoutChange && ($proposedPayoutChange->status=="approved" || $proposedPayoutChange->status=="rejected" )  ) {
            $proposedPayoutChange = null ;
        }

        $tour_steps=Tour::orderBy('step_number')->get();
        return view('seller.help_centre.ProductManagement', compact('user', 'emirates', 'proposedPayoutChange' , 'tour_steps'));
    }


    public function InventoryManagement(Request $request){
        $user = Auth::user();

        /*         $addresses = $user->addresses; */
        $emirates = Emirate::all();
        $proposedPayoutChange = ProposedPayoutChange::where('user_id', $user->id)->latest()->first();

        if ($proposedPayoutChange && ($proposedPayoutChange->status=="approved" || $proposedPayoutChange->status=="rejected" )  ) {
            $proposedPayoutChange = null ;
        }

        $tour_steps=Tour::orderBy('step_number')->get();
        return view('seller.help_centre.InventoryManagement', compact('user', 'emirates', 'proposedPayoutChange' , 'tour_steps'));
    }



    public function OrderManagement(Request $request){
        $user = Auth::user();

        /*         $addresses = $user->addresses; */
        $emirates = Emirate::all();
        $proposedPayoutChange = ProposedPayoutChange::where('user_id', $user->id)->latest()->first();

        if ($proposedPayoutChange && ($proposedPayoutChange->status=="approved" || $proposedPayoutChange->status=="rejected" )  ) {
            $proposedPayoutChange = null ;
        }

        $tour_steps=Tour::orderBy('step_number')->get();
        return view('seller.help_centre.OrderManagement', compact('user', 'emirates', 'proposedPayoutChange' , 'tour_steps'));
    }

    public function eshop(Request $request){
        $user = Auth::user();

        /*         $addresses = $user->addresses; */
        $emirates = Emirate::all();
        $proposedPayoutChange = ProposedPayoutChange::where('user_id', $user->id)->latest()->first();

        if ($proposedPayoutChange && ($proposedPayoutChange->status=="approved" || $proposedPayoutChange->status=="rejected" )  ) {
            $proposedPayoutChange = null ;
        }

        $tour_steps=Tour::orderBy('step_number')->get();
        return view('seller.help_centre.eshop', compact('user', 'emirates', 'proposedPayoutChange' , 'tour_steps'));
    }



    public function eshopProfile(Request $request){
        $user = Auth::user();

        /*         $addresses = $user->addresses; */
        $emirates = Emirate::all();
        $proposedPayoutChange = ProposedPayoutChange::where('user_id', $user->id)->latest()->first();

        if ($proposedPayoutChange && ($proposedPayoutChange->status=="approved" || $proposedPayoutChange->status=="rejected" )  ) {
            $proposedPayoutChange = null ;
        }

        $tour_steps=Tour::orderBy('step_number')->get();
        return view('seller.help_centre.eshopProfile', compact('user', 'emirates', 'proposedPayoutChange' , 'tour_steps'));
    }

    public function FAQS(Request $request){
        $user = Auth::user();

        /*         $addresses = $user->addresses; */
        $emirates = Emirate::all();
        $proposedPayoutChange = ProposedPayoutChange::where('user_id', $user->id)->latest()->first();

        if ($proposedPayoutChange && ($proposedPayoutChange->status=="approved" || $proposedPayoutChange->status=="rejected" )  ) {
            $proposedPayoutChange = null ;
        }

        $tour_steps=Tour::orderBy('step_number')->get();
        return view('seller.help_centre.FAQS', compact('user', 'emirates', 'proposedPayoutChange' , 'tour_steps'));
    }


    public function SupportTicket(Request $request){
        $user = Auth::user();

        /*         $addresses = $user->addresses; */
        $emirates = Emirate::all();
        $proposedPayoutChange = ProposedPayoutChange::where('user_id', $user->id)->latest()->first();

        if ($proposedPayoutChange && ($proposedPayoutChange->status=="approved" || $proposedPayoutChange->status=="rejected" )  ) {
            $proposedPayoutChange = null ;
        }

        $tour_steps=Tour::orderBy('step_number')->get();
        return view('seller.help_centre.SupportTicket', compact('user', 'emirates', 'proposedPayoutChange' , 'tour_steps'));
    }

    public function billing(Request $request){
        $user = Auth::user();

        /*         $addresses = $user->addresses; */
        $emirates = Emirate::all();
        $proposedPayoutChange = ProposedPayoutChange::where('user_id', $user->id)->latest()->first();

        if ($proposedPayoutChange && ($proposedPayoutChange->status=="approved" || $proposedPayoutChange->status=="rejected" )  ) {
            $proposedPayoutChange = null ;
        }

        $tour_steps=Tour::orderBy('step_number')->get();
        return view('seller.help_centre.billing', compact('user', 'emirates', 'proposedPayoutChange' , 'tour_steps'));
    }





}
