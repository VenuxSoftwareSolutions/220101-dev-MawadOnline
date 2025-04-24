<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class StoreWarehouseRequest extends FormRequest
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
            'warehouse_name.*' => 'nullable|max:128|regex:/\D/',
            'state_warehouse.*' => 'nullable|max:128',
            'area_warehouse.*' => 'nullable|max:128',
/*             'street_warehouse.*' => 'nullable|max:128|regex:/\D/',
 */            
            'street_warehouse.*' => '|max:128|regex:/\D/',

            'building_warehouse.*' => 'nullable|max:64|regex:/\D/',
            'unit_warehouse.*' => 'nullable|max:64',
        ];
    }
}
