<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class SearchStockRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Ensure to handle authorization appropriately
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $threeMonthsAgo = Carbon::now()->subMonths(3)->startOfDay();

        return [
            'from_date' => 'required|date|after_or_equal:' . $threeMonthsAgo->toDateString(),
            'to_date' => 'required|date|after_or_equal:from_date',
            'product_variants' => 'sometimes|array',
            'warehouses' => 'sometimes|array',
        ];
    }
}
