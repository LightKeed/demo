<?php

namespace App\Http\Requests\Admin\News;

use Illuminate\Foundation\Http\FormRequest;

class NewsCreateRequest extends FormRequest
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
            'tags' => 'nullable|array',
            'tags.*.id' => 'required|exists:tags,id',
            'poster_id' => 'nullable|integer|exists:media,id',
            'background_id' => 'nullable|integer|exists:media,id',
            'description' => 'nullable|string|max:255',
            'text' => 'nullable|string',
            'enabled' => 'required|boolean',
            'section_id' => 'nullable|integer|exists:thematic_sections,id'
        ];
    }
}
