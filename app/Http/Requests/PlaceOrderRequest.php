<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlaceOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authroization done in middleware class
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
            'products' => 'required|array',
            'products.*.quantity' => 'required|integer|min:1|max:100',
        ];
    }

    public function messages()
    {
        return [
            'products.required' => 'Products are required.',
            'products.*.id.required' => 'Product ID is required.',
            'products.*.quantity.required' => 'Quantity is required.',
            'products.*.quantity.min' => 'Quantity must be at least 1.',
        ];
    }
}
