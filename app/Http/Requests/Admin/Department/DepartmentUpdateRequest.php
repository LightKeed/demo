<?php

namespace App\Http\Requests\Admin\Department;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentUpdateRequest extends FormRequest
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
            'title_short' => 'nullable|string|max:255',
            'email' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'cabinet' => 'nullable|string|max:255',
            'department_id' => 'nullable|integer|exists:departments,id',
            'photo_id' => 'nullable|integer|exists:media,id',
            'address_id' => 'nullable|integer|exists:addresses,id',
            'type_id' => 'nullable|integer|exists:department_types,id'
        ];
    }
}
