<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Category;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $category = $this->route('category') ?? $this->route('id');
        
        return [
            'name' => 'sometimes|string|max:255|unique:categories,name,'.$category,
            'B_percentage' => 'sometimes|numeric|min:0|max:100',
        ];
    }

    public function messages()
    {
        return [
            'name.string' => 'The category name must be a string.',
            'name.max' => 'The category name cannot exceed 255 characters.',
            'name.unique' => 'This category name already exists.',
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