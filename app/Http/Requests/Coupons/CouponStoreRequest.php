<?php

namespace App\Http\Requests\Coupons;

use Illuminate\Foundation\Http\FormRequest;

class CouponStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Allow all authorized users, modify as needed
    }

    public function rules()
    {
        return [
            'code' => 'required|string|unique:coupons,code',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'max_discount' => 'nullable|numeric|min:0',
            'scope' => 'required|string|in:product,category,ordersOverAmount,allOrders',
            'product_id' => 'required_if:scope,product|exists:products,id',
            'category_id' => 'required_if:scope,category|exists:categories,id',
            'min_order_amount' => 'required_if:scope,ordersOverAmount|numeric|min:0',
        ];
    }

    public function attributes()
    {
        return [
            'code' => 'coupon code',
            'start_date' => 'start date',
            'end_date' => 'end date',
            'discount_percentage' => 'discount percentage',
            'max_discount' => 'maximum discount',
            'scope' => 'coupon scope',
            'product_id' => 'product ID',
            'category_id' => 'category ID',
            'min_order_amount' => 'minimum order amount',
        ];
    }
}