<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => __('locale.product_name_required'),
            'category_id.required' => __('locale.product_category_required'),
            'category_id.exists' => __('locale.product_category_invalid'),
            'price.required' => __('locale.product_price_required'),
            'price.numeric' => __('locale.product_price_numeric'),
            'price.min' => __('locale.product_price_min'),
            'stock.required' => __('locale.product_stock_required'),
            'stock.integer' => __('locale.product_stock_integer'),
            'stock.min' => __('locale.product_stock_min'),
        ];
    }
}
