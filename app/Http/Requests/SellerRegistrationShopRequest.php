<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

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

            throw new AuthorizationException(__('Your account is not verified. Please create an account and confirm it.'));
            return false;
        }

        // Check if the user's account is not verified (assuming 'verified' is a column in the users table)
        if (!Auth::user()->email_verified_at) {
            throw new AuthorizationException(__('Your account is not verified. Please create an account and confirm it.'));
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
        return [
            'trade_name_english' => 'required|string|max:255',
            'trade_name_arabic' => 'required|string|max:255',
            'trade_license_doc' => !$this->input('trade_license_doc_old') ? 'required|file|mimes:pdf,jpeg,png,gif|max:5120' : '',
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
            'vat_certificate' => $this->input('vat_registered') == 1 && !$this->input('vat_certificate_old') ? 'required_if:vat_registered,1|file|mimes:pdf,jpeg,png,gif|max:5120' : '',
            'trn' => $this->input('vat_registered') == 1 ? 'required_if:vat_registered,1|string|max:20' : '',
            'tax_waiver' => $this->input('vat_registered') == 0 && !$this->input('tax_waiver_old') ? 'required_if:vat_registered,0|file|mimes:pdf,jpeg,png,gif|max:5120' : '',
            'civil_defense_approval' => 'nullable|file|mimes:pdf,jpeg,png,gif|max:5120',
            'first_name' => 'required|string|max:64',
            'last_name' => 'required|string|max:64',
            'email' => 'required|email',
            'mobile_phone' => ['required', 'string', 'max:20', new \App\Rules\UaeMobilePhone],
            'additional_mobile_phone' => ['required', 'string', 'max:20', new \App\Rules\UaeMobilePhone],
            'nationality' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'emirates_id_number' => 'required|string|max:255',
            'emirates_id_expiry_date' => 'required|date',
            'emirates_id_file' => !$this->input('emirates_id_file_old') ? 'required|file|mimes:pdf,jpeg,png|max:5120' : '',
            'business_owner' => 'required|boolean',
            'designation' => 'required|string|max:255',
            'warehouse_name.*' => 'required|max:128',
            'state_warehouse.*' => 'required|max:128',
            'area_warehouse.*' => 'required|max:128',
            'street_warehouse.*' => 'required|max:128',
            'building_warehouse.*' => 'required|max:128',
            'unit_warehouse.*' => 'required|max:128',
            'bank_name' => 'required|string|max:128',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'iban' => 'required|string|max:255',
            'swift_code' => 'required|string|max:255',
            'iban_certificate' => !$this->input('iban_certificate_old') ? 'required|file|mimes:pdf,jpeg,png|max:5120' : '',
        ];
    }






}
