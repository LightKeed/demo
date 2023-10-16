<?php

namespace App\Services\Classes\Admin;

use App\Services\BaseService;
use Inertia\Inertia;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Rights\RoleCreateRequest;
use App\Http\Requests\Admin\Rights\RoleUpdateRequest;
use Spatie\Permission\Models\Role;
use App\Models\User;

final class RoleService extends BaseService
{
    public function store(RoleCreateRequest $request)
    {
        $role = new Role($request->validated());

        $role->save();

        $this->log->add('create', 'role', null, $role->name);

        return to_route('Admin.RightsIndex')->with([
            'success' => 'Роль успешно создана.'
        ]);
    }

    public function attach(Request $request)
    {
        $user = User::where('id', '=', $request->user_id)->firstOrFail();
        $role = Role::where('name', '=', $request->role)->firstOrFail();

        if($request->status) {
            $user->assignRole($role);
        } else {
            $user->removeRole($role);
        }

        $this->log->add($request->status ? 'attach' : 'detach', 'role', null, "Role: $role->name | User: $request->user_id");

        return response(200);
    }

    public function edit(int $id)
    {
        return Inertia::render('Admin/Rights/EditRole', [
            'role' => $this->getRoleByID($id),
            'users' => User::select('id', 'surname', 'name', 'patronymic', 'email')->with('roles:id,name')->orderBy('surname')->get()
        ]);
    }

    public function update(RoleUpdateRequest $request)
    {
        $role = $this->getRoleByID($request->id);

        $role->update($request->validated());

        $this->log->add('update', 'role', null, $role->name);

        return redirect()->back()->with([
            'success' => 'Роль успешно обновлена.'
        ]);
    }

    public function remove(int $id)
    {
        try {
            $role = $this->getRoleByID($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(['error' => 'Роль не найдена.']);
        }

        $role->delete();

        $this->log->add('delete', 'role', null, $role->name);

        return to_route('Admin.RightsIndex')->with([
            'success' => 'Роль успешно удалена.'
        ]);
    }

    private function getRoleByID(int $id)
    {
        return Role::where('id', '=', $id)
            ->firstOrFail();
    }
}