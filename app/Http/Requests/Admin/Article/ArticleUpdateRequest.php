<?php

namespace App\Http\Requests\Admin\Article;

use Illuminate\Foundation\Http\FormRequest;

class ArticleUpdateRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'slug_alternative' => 'nullable|string|max:255',
            'category_id' => 'nullable|integer|exists:categories,id',
            'poster_id' => 'nullable|integer|exists:media,id',
            'background_id' => 'nullable|integer|exists:media,id',
            'slider_id' => 'nullable|integer|exists:sliders,id',
            'description' => 'nullable|string|max:255',
            'text' => 'nullable|string',
            'sort_order' => 'required|integer|min:1|max:255',
            'enabled' => 'required|boolean',
            'enabled_menu' => 'required|boolean'
        ];
    }
}
