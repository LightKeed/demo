<?php

namespace App\Http\Requests\Admin\Slider;

use Illuminate\Foundation\Http\FormRequest;

class SlideUpdateRequest extends FormRequest
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
            'description' => 'nullable|string|max:255',
            'text_button' => 'nullable|string|max:255',
            'link_button' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0|max:255',
            'enabled' => 'nullable|boolean',
            'slider_id' => 'required|integer|exists:sliders,id',
            'media_id' => 'nullable|integer|exists:media,id'
        ];
    }
}
