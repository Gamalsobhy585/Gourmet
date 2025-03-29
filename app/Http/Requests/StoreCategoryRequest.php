<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:categories,name',
            'B_percentage' => 'required|numeric|min:0|max:100',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The category name is required.',
            'name.unique' => 'This category name already exists.',
            'B_percentage.required' => 'The B percentage is required.',
            'B_percentage.numeric' => 'The B percentage must be a number.',
            'B_percentage.min' => 'The B percentage cannot be negative.',
            'B_percentage.max' => 'The B percentage cannot exceed 100%.',
        ];
    }

    public function attributes()
    {
        return [
            'B_percentage' => 'B percentage',
        ];
    }
}