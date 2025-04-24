<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SellerRegistrationShopRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (!Auth::check()) {

            throw new AuthorizationException(translate('Your account is not verified. Please create an account and confirm it.'));
            return false;
        }

        // Check if the user's account is not verified (assuming 'verified' is a column in the users table)
        if (!Auth::user()->email_verified_at) {
            throw new AuthorizationException(translate('Your account is not verified. Please create an account and confirm it.'));
            return false;
        }
        if (!isset($this->warehouse_name)) {
            throw new AuthorizationException(translate('Please add at least one warehouse.'));
            return false;
       }
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $user_id = Auth::id(); // Retrieve the authenticated user's ID

        return [
            'trade_name_english' => 'required|string|max:128|regex:/\D/',
            'trade_name_arabic' => 'required|string|max:256|regex:/\D/',
            'trade_license_doc' => !$this->input('trade_license_doc_old') ? 'required|file|mimes:pdf,jpeg,png|max:5120' : 'nullable|file|mimes:pdf,jpeg,png|max:5120',
            'eshop_name_english' => 'required|string|max:128|regex:/\D/',
            'eshop_name_arabic' => 'required|string|max:256|regex:/\D/',
            'eshop_desc_en' => 'nullable|string|regex:/\D/',
            'eshop_desc_ar' => 'nullable|string|regex:/\D/',
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
            'vat_certificate' => $this->input('vat_registered') == 1 && (!$this->input('vat_certificate_old') || $this->hasFile('vat_certificate') ) ? 'required_if:vat_registered,1|file|mimes:pdf,jpeg,png|max:5120' : '',
            'trn' => $this->input('vat_registered') == 1 ? 'required_if:vat_registered,1|string|max:20' : '',
            'tax_waiver' => 'nullable|file|max:5120|mimes:pdf,jpg,jpeg,png,webp,gif,avif,bmp,tiff,heic',
            'civil_defense_approval' => 'nullable|file|mimes:pdf,jpeg,png|max:5120',
            'first_name' => 'required|string|max:64|regex:/\D/',
            'last_name' => 'required|string|max:64|regex:/\D/',
            'email' => [
                'required',
                'email',
                Rule::unique('contact_people')->where(function ($query) use ($user_id) {
                    return $query->where('user_id', '<>', $user_id);
                }),
            ],
            'mobile_phone' => ['required', 'string', 'max:16', new \App\Rules\UaeMobilePhone],
            'additional_mobile_phone' =>$this->input('additional_mobile_phone') != '+971' ? ['nullable', 'string', 'max:16', new \App\Rules\UaeMobilePhone]:'',
            'nationality' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:-18 years',
            'emirates_id_number' => ['required', 'string', 'max:15', 'regex:/^[0-9]{15}$/'],
            'emirates_id_expiry_date' => 'required|date|after_or_equal:today',
            'emirates_id_file' => !$this->input('emirates_id_file_old') ? 'required|file|mimes:pdf,jpeg,png|max:5120' : 'nullable|file|mimes:pdf,jpeg,png|max:5120',
            'business_owner' => 'required|boolean',
            'designation' => 'required|string|max:64|regex:/\D/',
            'warehouse_name.*' => 'required|max:128|regex:/\D/',
            'state_warehouse.*' => 'required|max:128',
            'area_warehouse.*' => 'required|max:128',
            'street_warehouse.*' => 'required|max:128|regex:/\D/',
            'building_warehouse.*' => 'required|max:64|regex:/\D/',
            'unit_warehouse.*' => 'nullable|max:64',
            'bank_name' => 'required|string|max:128|regex:/\D/',
            'account_name' => 'required|string|max:128|regex:/\D/',
            'account_number' => 'required|string|max:30',
            'iban' => 'required|string|max:34',
            'swift_code' => 'required|string|max:16',
            'iban_certificate' => !$this->input('iban_certificate_old') ? 'required|file|mimes:pdf,jpeg,png|max:5120' : 'nullable|file|mimes:pdf,jpeg,png|max:5120',
        ];
    }






}
