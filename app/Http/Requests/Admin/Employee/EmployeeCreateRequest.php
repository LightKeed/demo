<?php

namespace App\Http\Requests\Admin\Employee;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeCreateRequest extends FormRequest
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
            'surname' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'patronymic' => 'nullable|string|max:255',
            'level_education' => 'nullable|string|max:255',
            'general_experience' => 'nullable|integer|min:0',
            'scientific_experience' => 'nullable|integer|min:0',
            'photo_id' => 'nullable|integer|exists:media,id',
            'positions' => 'nullable|array',
            'positions.*.department_id' => 'required|integer|exists:departments,id',
            'positions.*.address_id' => 'nullable|integer|exists:addresses,id',
            'positions.*.subdivision' => 'nullable|string|max:255',
            'positions.*.title' => 'required|string|max:255',
            'positions.*.experience' => 'nullable|integer|min:0',
            'positions.*.status' => 'required|integer|min:0|max:1',
            'attributes' => 'nullable|array',
            'attributes.*.title' => 'required|string|max:255',
            'attributes.*.value' => 'nullable|string'
        ];
    }
}
