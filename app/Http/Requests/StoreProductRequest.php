<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'SKU' => 'required|string|max:100|unique:products,SKU',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The product name is required.',
            'price.required' => 'The price is required.',
            'price.numeric' => 'The price must be a number.',
            'price.min' => 'The price cannot be negative.',
            'category_id.required' => 'A category must be selected.',
            'category_id.exists' => 'The selected category does not exist.',
            'SKU.required' => 'The SKU is required.',
            'SKU.unique' => 'This SKU already exists.',
        ];
    }
}