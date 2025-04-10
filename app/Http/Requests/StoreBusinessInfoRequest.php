<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class StoreBusinessInfoRequest extends FormRequest
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
            'trade_name_english' => 'nullable|string|max:128|regex:/\D/',
            'trade_name_arabic' => 'nullable|string|max:256|regex:/\D/',
            'eshop_name_english' => 'nullable|string|max:128|regex:/\D/',
            'eshop_name_arabic' => 'nullable|string|max:256|regex:/\D/',
            'eshop_desc_en' => 'nullable|string|regex:/\D/',
            'eshop_desc_ar' => 'nullable|string|regex:/\D/',
            'license_issue_date' => 'nullable|date',
            'license_expiry_date' => 'nullable|date|after_or_equal:today|after_or_equal:license_issue_date',
            'state' => 'nullable|exists:emirates,id',
            'area_id' => 'nullable|exists:areas,id',
/*             'street' => 'nullable|string|max:128|regex:/\D/',
            'building' => 'nullable|string|max:64',
            
 */         
            'street' => 'nullable|string|max:128',
            'building' => 'nullable|string|max:64',
            'unit' => 'nullable|string|max:64',
            'po_box' => 'nullable|string|max:32',
            'landline' => 'nullable|string|max:16',
            'vat_registered' => 'nullable|boolean',
            'trade_license_doc' => 'nullable|file|mimes:pdf,jpeg,jpg,png,webp|max:5120',
            'trn' => $this->vat_registered == 1 ? 'nullable|string|max:20' : '',
            'vat_certificate' => $this->vat_registered == 1 /* && !isset($this->vat_certificate_old) */ ? 'nullable|file|mimes:pdf,jpeg,png|max:5120' : '',
            'tax_waiver' => 'nullable|file|max:5120|mimes:pdf,jpeg,jpg,png,webp',
            'civil_defense_approval' => 'nullable|file|max:5120|mimes:pdf,jpeg,jpg,png,webp',
        ];
    }
}
