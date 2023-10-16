<?php

namespace App\Services\Classes\Admin;

use App\Services\BaseService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\Admin\Employee\AttributeCreateRequest;
use App\Http\Requests\Admin\Employee\AttributeUpdateRequest;
use App\Models\Employee;
use App\Models\EmployeeAttribute;

final class EmployeeAttributeService extends BaseService
{
    public function store(AttributeCreateRequest $request)
    {
        $employee = $this->getEmployeeByID($request->employee_id);

        $employee->attributes()->create([
            'title' => $request->title,
            'value' => $this->getValue($request->value)
        ]);

        return redirect()->back()->with([
            'success' => 'Атрибут сотрудника успешно добавлен.'
        ]);
    }

    public function update(AttributeUpdateRequest $request)
    {
        $attribute = $this->getAttributeByID($request->id);

        $attribute->update([
            'title' => $request->title,
            'value' => $this->getValue($request->value)
        ]);

        return redirect()->back()->with([
            'success' => 'Атрибут сотрудника успешно обновлен.'
        ]);
    }

    public function remove(int $id)
    {
        try {
            $attribute = $this->getAttributeByID($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(['error' => 'Атрибут сотрудника не найден.']);
        }

        $attribute->delete();

        return redirect()->back()->with([
            'success' => 'Атрибут сотрудника успешно удален.'
        ]);
    }

    private function getEmployeeByID(int $id)
    {
        return Employee::where('id', '=', $id)
            ->firstOrFail();
    }

    private function getAttributeByID(int $id)
    {
        return EmployeeAttribute::where('id', '=', $id)
            ->firstOrFail();
    }

    private function getValue($value)
    {
        if(!$value) return [];

        $arr = explode(',', $value);

        if(count($arr) > 1) {
            return json_encode($arr, JSON_UNESCAPED_UNICODE);
        }

        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}
