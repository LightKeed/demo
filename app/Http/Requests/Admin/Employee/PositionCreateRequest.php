<?php

namespace App\Http\Requests\Admin\Employee;

use Illuminate\Foundation\Http\FormRequest;

class PositionCreateRequest extends FormRequest
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
            'employee_id' => 'required|integer|exists:employees,id',
            'department_id' => 'required|integer|exists:departments,id',
            'address_id' => 'nullable|integer|exists:addresses,id',
            'subdivision' => 'nullable|string|max:255',
            'cabinet' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'experience' => 'nullable|integer|min:0',
            'status' => 'required|integer|min:0|max:1'
        ];
    }
}
