<?php

namespace App\Services\Classes\Admin;

use App\Services\BaseService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\Admin\Employee\PositionCreateRequest;
use App\Http\Requests\Admin\Employee\PositionUpdateRequest;
use App\Models\Employee;
use App\Models\DepartmentPosition;

final class EmployeePositionService extends BaseService
{
    public function index()
    {
        $positions = DepartmentPosition::filter(Request::only('fio', 'department'))
            ->with('department.type', 'department.address', 'employee', 'address')
            ->paginate(20);

        return response()->json($positions);
    }

    public function show()
    {
        $result = [];
        $type = Request::only('type')['type'];
        $ids = Request::only('ids')['ids'];

        foreach($ids as $id) {
            $position = DepartmentPosition::where('id', '=', $id)
                ->with('department', 'employee', 'address')
                ->first();

            if($position) {
                $phone = $position->employee->phone;
                $email = $position->employee->email;

                if($type == 1) {
                    $result[] = [
                        'photo_id' => $position->employee->photo_id,
                        'title' => $position->department ? $position->department->title : '',
                        'subtitle' => "{$position->employee->surname} {$position->employee->name} {$position->employee->patronymic}, {$position->title}",
//                        'address' => $position->address ? $position->address->fullTitle : ($position->department->address ? $position->department->address->fullTitle : null),
                        'phone' => $phone,
                        'email' => $email
                    ];
                } else if($type == 2) {
                    $result[] = [
                        'photo_id' => $position->employee->photo_id,
                        'title' => $position->title,
                        'subtitle' => "{$position->employee->surname} {$position->employee->name} {$position->employee->patronymic}",
                        'address' => $position->address ? $position->address->fullTitleCard : ($position->department->address ? $position->department->address->fullTitleCard : null),
                        'phone' => $phone,
                        'email' => $email
                    ];
                } else if($type == 3) {
                    $themes = $position->employee->attributes()->filter(['title' => 'themes'])->first()->value ?? [];

                    $result[] = [
                        'photo_id' => $position->employee->photo_id,
                        'title' => "{$position->employee->surname} {$position->employee->name} {$position->employee->patronymic}",
                        'subtitle' => "{$position->title}<br>{$position->department->title}",
                        'description' => $position->employee->level_education,
                        'address' => $position->address ? "{$position->address->fullTitleCard} $position->cabinet" : ($position->department->address ? "{$position->department->address->fullTitleCard} $position->cabinet" : null),
                        'phone' => $phone,
                        'email' => $email,
                        'themes' => $themes
                    ];
                }
            }
        }

        return response()->json($result);
    }

    public function store(PositionCreateRequest $request)
    {
        $employee = $this->getEmployeeByID($request->employee_id);

        $employee->positions()->create([
            'department_id' => $request->department_id,
            'address_id' => $request->address_id,
            'subdivision' => $request->subdivision,
            'cabinet' => $request->cabinet,
            'title' => $request->title,
            'experience' => $request->experience ?? 0,
            'status' => $request->status
        ]);

        return redirect()->back()->with([
            'success' => 'Должность успешно добавлена.'
        ]);
    }

    public function update(PositionUpdateRequest $request)
    {
        $position = $this->getPositionByID($request->id);

        $position->update([
            'department_id' => $request->department_id,
            'address_id' => $request->address_id,
            'subdivision' => $request->subdivision,
            'cabinet' => $request->cabinet,
            'title' => $request->title,
            'experience' => $request->experience ?? 0,
            'status' => $request->status
        ]);

        return redirect()->back()->with([
            'success' => 'Должность успешно обновлена.'
        ]);
    }

    public function remove(int $id)
    {
        try {
            $position = $this->getPositionByID($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(['error' => 'Должность не найдена.']);
        }

        $position->delete();

        return redirect()->back()->with([
            'success' => 'Должность сотрудника успешно удалена.'
        ]);
    }

    private function getEmployeeByID(int $id)
    {
        return Employee::where('id', '=', $id)
            ->firstOrFail();
    }

    private function getPositionByID(int $id)
    {
        return DepartmentPosition::where('id', '=', $id)
            ->firstOrFail();
    }
}
