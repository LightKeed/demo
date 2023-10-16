<?php

namespace App\Services\Classes\Admin;

use App\Services\BaseService;
use Inertia\Inertia;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\Admin\Employee\EmployeeCreateRequest;
use App\Http\Requests\Admin\Employee\EmployeeUpdateRequest;
use App\Models\Employee;
use App\Models\Department;
use App\Models\DepartmentPosition;
use App\Models\Address;

final class EmployeeService extends BaseService
{
    public function index()
    {
        $employees = Employee::filter(Request::only('title', 'department'))
            ->with('positions.department')
            ->orderBy('surname')
            ->paginate(20)
            ->withQueryString();

        $departments = $this->getDepartments();

        return Inertia::render('Admin/Employee/Index', [
            'filters' => Request::all('title', 'department'),
            'employees' => $employees,
            'departments' => $departments
        ]);
    }

    public function create()
    {
        $departments = $this->getDepartments();
        $addresses = $this->getAvailableAddresses();

        return Inertia::render('Admin/Employee/Create', [
            'departments' => $departments,
            'addresses' => $addresses
        ]);
    }

    public function store(EmployeeCreateRequest $request)
    {
        $employee = new Employee([
            'surname' => $request->surname,
            'name' => $request->name,
            'patronymic' => $request->patronymic,
            'level_education' => $request->level_education,
            'general_experience' => $request->general_experience,
            'scientific_experience' => $request->scientific_experience,
            'photo_id' => $request->photo_id
        ]);

        $employee->save();

        foreach($request['positions'] as $position) {
            $employee->positions()->create([
                'department_id' => $position['department_id'],
                'address_id' => $position['address_id'],
                'subdivision' => $position['subdivision'],
                'cabinet' => $position['cabinet'],
                'title' => $position['title'],
                'experience' => $position['experience'] ?? 0,
                'status' => $position['status']
            ]);
        }

        foreach($request['attributes'] as $attribute) {
            $employee->attributes()->create([
                'title' => $attribute['title'],
                'value' => $attribute['value'] ?? []
            ]);
        }

        $this->log->add('create', 'employee', $employee->id, $employee->fio);

        return to_route('Admin.EmployeeIndex')->with([
            'success' => 'Сотрудник успешно создан.'
        ]);
    }

    public function edit(int $id)
    {
        $departments = $this->getDepartments();
        $addresses = $this->getAvailableAddresses();

        return Inertia::render('Admin/Employee/Edit', [
            'employee' => $this->getEmployeeByID($id),
            'departments' => $departments,
            'addresses' => $addresses
        ]);
    }

    public function update(EmployeeUpdateRequest $request)
    {
        $employee = $this->getEmployeeByID($request->id);

        $employee->update($request->validated());

        $this->log->add('update', 'employee', $employee->id, $employee->fio);

        return redirect()->back()->with([
            'success' => 'Сотрудник успешно обновлен.'
        ]);
    }

    public function remove(int $id)
    {
        try {
            $employee = $this->getEmployeeByID($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(['error' => 'Сотрудник не найден.']);
        }

        $employee->delete();

        $this->log->add('delete', 'employee', $employee->id, $employee->fio);

        return to_route('Admin.EmployeeIndex')->with([
            'success' => 'Сотрудник успешно удален.'
        ]);
    }

    private function getEmployeeByID(int $id)
    {
        return Employee::where('id', '=', $id)
            ->with('positions.department', 'attributes')
            ->firstOrFail();
    }

    private function getDepartments()
    {
        return Department::orderBy('title')->get();
    }

    private function getAvailableAddresses()
    {
        return Address::orderBy('post_code', 'desc')
            ->get();
    }
}
