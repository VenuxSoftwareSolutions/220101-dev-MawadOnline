<?php

namespace App\Http\Requests\Discounts;

use Illuminate\Foundation\Http\FormRequest;

class DiscountStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'scope' => 'required|string|in:product,category,ordersOverAmount,allOrders',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'max_discount' => 'nullable|numeric|min:0',
            'product_id' => 'required_if:scope,product|exists:products,id',
            'category_id' => 'required_if:scope,category|exists:categories,id',
            'min_order_amount' => 'required_if:scope,order_over_amount|numeric|min:0',
        ];
    }


    public function attributes()
    {
        return [
            'discountType' => 'discount type',
            'start_date' => 'start date',
            'end_date' => 'end date',
            'scope' => 'discount scope',
            'discount_percentage' => 'discount percentage',
            'max_discount' => 'maximum discount',
            'min_order_amount' => 'minimum order amount',
        ];
    }
}
