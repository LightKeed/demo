<?php

namespace App\Services\Classes\Admin;

use App\Services\BaseService;
use Inertia\Inertia;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\Admin\Department\DepartmentCreateRequest;
use App\Http\Requests\Admin\Department\DepartmentUpdateRequest;
use App\Models\Department;
use App\Models\DepartmentType;
use App\Models\Address;

final class DepartmentService extends BaseService
{
    public function index()
    {
        $departments = Department::filter(Request::only('title', 'type'))
            ->with('type', 'address', 'positions')
            ->orderBy('title')
            ->paginate(20)
            ->withQueryString();

        $types = $this->getAvailableTypes();

        return Inertia::render('Admin/Department/Index', [
            'filters' => Request::all('title', 'type'),
            'departments' => $departments,
            'types' => $types
        ]);
    }

    public function create()
    {
        $types = $this->getAvailableTypes();
        $addresses = $this->getAvailableAddresses();
        $departments = $this->getAvailableDepartments();

        return Inertia::render('Admin/Department/Create', [
            'types' => $types,
            'addresses' => $addresses,
            'departments' => $departments
        ]);
    }

    public function store(DepartmentCreateRequest $request)
    {
        $department = new Department($request->validated());

        $department->save();

        $this->log->add('create', 'department', $department->id, $department->title);

        return to_route('Admin.DepartmentIndex')->with([
            'success' => 'Подразделение успешно создано.'
        ]);
    }

    public function edit(int $id)
    {
        $types = $this->getAvailableTypes();
        $addresses = $this->getAvailableAddresses();
        $departments = $this->getAvailableDepartments($id);

        return Inertia::render('Admin/Department/Edit', [
            'department' => $this->getDepartmentByID($id),
            'types' => $types,
            'addresses' => $addresses,
            'departments' => $departments
        ]);
    }

    public function update(DepartmentUpdateRequest $request)
    {
        $department = $this->getDepartmentByID($request->id);

        $department->update($request->validated());

        $this->log->add('update', 'department', $department->id, $department->title);

        return redirect()->back()->with([
            'success' => 'Подразделение успешно обновлено.'
        ]);
    }

    public function remove(int $id)
    {
        try {
            $department = $this->getDepartmentByID($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(['error' => 'Подразделение не найдено.']);
        }

        $department->delete();

        $this->log->add('delete', 'department', $department->id, $department->title);

        return to_route('Admin.DepartmentIndex')->with([
            'success' => 'Подразделение успешно удалено.'
        ]);
    }

    private function getDepartmentByID(int $id)
    {
        return Department::where('id', '=', $id)
            ->with('parent', 'type', 'address', 'positions.employee')
            ->firstOrFail();
    }

    private function getAvailableTypes()
    {
        return DepartmentType::get();
    }

    private function getAvailableAddresses()
    {
        return Address::orderBy('post_code', 'desc')
            ->get();
    }

    private function getAvailableDepartments($id = null)
    {
        return Department::where('id', '!=', $id)
            ->orderBy('title', 'desc')
            ->get();
    }
}
