<?php

namespace App\Http\Requests\Admin\Category;

use Illuminate\Foundation\Http\FormRequest;

class CategoryCreateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'slug_alternative' => 'nullable|string|max:255|unique:categories,slug_alternative',
            'parent_id' => 'nullable|integer|exists:categories,id',
            'background_id' => 'nullable|integer|exists:media,id',
            'sort_order' => 'required|integer|min:1|max:255',
            'enabled' => 'required|boolean'
        ];
    }
}
