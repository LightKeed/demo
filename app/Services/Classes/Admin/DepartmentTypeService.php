<?php

namespace App\Services\Classes\Admin;

use App\Services\BaseService;
use Inertia\Inertia;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\Admin\DepartmentType\TypeCreateRequest;
use App\Http\Requests\Admin\DepartmentType\TypeUpdateRequest;
use App\Models\DepartmentType;

final class DepartmentTypeService extends BaseService
{
    public function index()
    {
        $types = DepartmentType::with('departments')
            ->filter(Request::only('title'))
            ->orderBy('title')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Admin/DepartmentType/Index', [
            'filters' => Request::all('title'),
            'types' => $types
        ]);
    }

    public function store(TypeCreateRequest $request)
    {
        $type = new DepartmentType($request->validated());

        $type->save();

        $this->log->add('create', 'department-type', $type->id, $type->title);

        return redirect()->back()->with([
            'success' => 'Тип подразделения успешно создан.'
        ]);
    }

    public function edit(int $id)
    {
        return Inertia::render('Admin/DepartmentType/Edit', [
            'type' => $this->getTypeByID($id)
        ]);
    }

    public function update(TypeUpdateRequest $request)
    {
        $type = $this->getTypeByID($request->id);

        $type->update($request->validated());

        $this->log->add('update', 'department-type', $type->id, $type->title);

        return redirect()->back()->with([
            'success' => 'Тип подразделения успешно обновлен.'
        ]);
    }

    public function remove(int $id)
    {
        try {
            $type = $this->getTypeByID($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(['error' => 'Тип подразделения не найден.']);
        }

        $type->delete();

        $this->log->add('delete', 'department-type', $type->id, $type->title);

        return to_route('Admin.DepartmentTypeIndex')->with([
            'success' => 'Тип подразделения успешно удален.'
        ]);
    }

    private function getTypeByID(int $id)
    {
        return DepartmentType::where('id', '=', $id)
            ->firstOrFail();
    }
}
