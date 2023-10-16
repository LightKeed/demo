<?php

namespace App\Services\Classes\Admin;

use App\Services\BaseService;
use Inertia\Inertia;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Rights\PermissionCreateRequest;
use App\Http\Requests\Admin\Rights\PermissionUpdateRequest;
use Spatie\Permission\Models\Permission;
use App\Models\User;

final class PermissionService extends BaseService
{
    public function store(PermissionCreateRequest $request)
    {
        $permission = new Permission($request->validated());

        $permission->save();

        $this->log->add('create', 'permission', null, $permission->display_name);

        return to_route('Admin.RightsIndex')->with([
            'success' => 'Право успешно создано.'
        ]);
    }

    public function attach(Request $request)
    {
        $user = User::where('id', '=', $request->user_id)->firstOrFail();
        $permission = Permission::where('name', '=', $request->permission)->firstOrFail();

        if($request->status) {
            $user->givePermissionTo($permission);
        } else {
            $user->revokePermissionTo($permission);
        }

        $this->log->add($request->status ? 'attach' : 'detach', 'permission', null, "Permission: $permission->display_name | User: $request->user_id");

        return response(200);
    }

    public function edit(int $id)
    {
        return Inertia::render('Admin/Rights/EditPermission', [
            'permission' => $this->getPermissionByID($id),
            'users' => User::select('id', 'surname', 'name', 'patronymic', 'email')->with('permissions:id,name')->orderBy('surname')->get()
        ]);
    }

    public function update(PermissionUpdateRequest $request)
    {
        $permission = $this->getPermissionByID($request->id);

        $permission->update($request->validated());

        $this->log->add('update', 'permission', null, $permission->display_name);

        return redirect()->back()->with([
            'success' => 'Право успешно обновлено.'
        ]);
    }

    public function remove(int $id)
    {
        try {
            $permission = $this->getPermissionByID($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(['error' => 'Право не найдено.']);
        }

        $permission->delete();

        $this->log->add('delete', 'permission', null, $permission->display_name);

        return to_route('Admin.RightsIndex')->with([
            'success' => 'Право успешно удалено.'
        ]);
    }

    private function getPermissionByID(int $id)
    {
        return Permission::where('id', '=', $id)
            ->firstOrFail();
    }
}