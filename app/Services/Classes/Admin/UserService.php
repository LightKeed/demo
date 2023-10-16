<?php

namespace App\Services\Classes\Admin;

use App\Services\BaseService;
use Inertia\Inertia;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Admin\User\UserCreateRequest;
use App\Http\Requests\Admin\User\UserUpdateRequest;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Department;
use App\Models\DepartmentType;

final class UserService extends BaseService
{
    public function index()
    {
        $users = User::filter(Request::only('name', 'email', 'archive'))
            ->orderBy('surname')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Admin/User/Index', [
            'filters' => Request::all('name', 'email', 'archive'),
            'users' => $users
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/User/Create');
    }

    public function store(UserCreateRequest $request)
    {
        if(explode('@', $request->email)[1] !== 'tyuiu.ru') {
            return back()->with(['error' => 'Некорректное имя домена.']);
        }

        $user = new User([
            'surname' => $request->surname,
            'name' => $request->name,
            'patronymic' => $request->patronymic,
            'email' => $request->email,
            'password' => Hash::make(Str::random(32))
        ]);

        $user->save();

        $this->log->add('create', 'user', $user->id, $user->fio);

        return to_route('Admin.UserIndex')->with([
            'success' => 'Пользователь успешно создан.'
        ]);
    }

    public function edit(int $id)
    {
        $roles = Role::orderBy('name')->get();
        $permissions = Permission::orderBy('group')->orderBy('display_name')->get();
        $groups = Permission::select('group')->whereNotNull('group')->distinct()->get();

        return Inertia::render('Admin/User/Edit', [
            'user' => $this->getUserByID($id),
            'roles' => $roles,
            'permissions' => $permissions,
            'groups' => $groups
        ]);
    }

    public function update(UserUpdateRequest $request)
    {
        if(explode('@', $request->email)[1] !== 'tyuiu.ru') {
            return back()->with(['error' => 'Некорректное имя домена.']);
        }

        $user = $this->getUserByID($request->id);

        $user->update($request->validated());

        $this->log->add('update', 'user', $user->id, $user->fio);

        return redirect()->back()->with([
            'success' => 'Пользователь успешно обновлен.'
        ]);
    }

    public function restore(int $id)
    {
        try {
            $user = $this->getUserByID($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(['error' => 'Пользователь не найден.']);
        }

        $user->restore();

        $this->log->add('restore', 'user', $user->id, $user->fio);

        return redirect()->back()->with([
            'success' => 'Пользователь успешно восстановлен.'
        ]);
    }

    public function remove(int $id)
    {
        try {
            $user = $this->getUserByID($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(['error' => 'Пользователь не найден.']);
        }

        $trashed = $user->trashed();
        $trashed ? $user->forceDelete() : $user->delete();

        $this->log->add($trashed ? 'force_delete' : 'delete', 'user', $user->id, $user->fio);

        if($trashed) {
            return to_route('Admin.UserIndex')->with([
                'success' => 'Пользователь успешно удален.'
            ]);
        } else {
            return redirect()->back()->with([
                'success' => 'Пользователь успешно удален.'
            ]);
        }
    }

    private function getUserByID(int $id)
    {
        return User::withTrashed()
            ->with([
                'roles:id,name',
                'permissions:id,name,display_name,group',
                'articleRights:id,title',
                'newsRights:id,title',
                'eventRights:id,title'
            ])
            ->where('id', '=', $id)
            ->firstOrFail();
    }
}
