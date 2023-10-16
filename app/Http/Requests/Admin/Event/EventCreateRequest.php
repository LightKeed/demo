<?php

namespace App\Http\Requests\Admin\Event;

use Illuminate\Foundation\Http\FormRequest;

class EventCreateRequest extends FormRequest
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
            'recording' => 'required|boolean',
            'event_start_at' => 'required|date',
            'event_end_at' => 'nullable|date|after_or_equal:event_start_at',
            'record_start_at' => 'nullable|date|before_or_equal:record_end_at',
            'record_end_at' => 'nullable|date|after_or_equal:record_start_at'
        ];
    }
}
