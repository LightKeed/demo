<?php

namespace App\Http\Requests\Admin\ThematicSection;

use Illuminate\Foundation\Http\FormRequest;

class SectionCreateRequest extends FormRequest
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
            'slug' => 'nullable|string|max:255|unique:tags,slug',
            'rating' => 'nullable|integer|min:0|max:100000',
            'parent_id' => 'nullable|integer|exists:thematic_sections,id'
        ];
    }
}
