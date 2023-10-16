<?php

namespace App\Services\Classes\Admin;

use App\Services\BaseService;
use Inertia\Inertia;
use Illuminate\Support\Facades\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

final class RightsService extends BaseService
{
    public function index()
    {
        $filters = Request::only('name', 'group');
        $sections = json_decode(config('settings.settings_sections'), true) ?? [];

        $roles = Role::orderBy('name')->get();
        $permissions = Permission::when($filters['name'] ?? null, function ($query, $name) {
                $query->where(function ($query) use ($name) {
                    $query->where('name', 'LIKE', "%$name%")
                        ->orWhere('display_name', 'LIKE', "%$name%");
                });
            })->when($filters['group'] ?? null, function ($query, $group) {
                if($group === 'empty') {
                    $query->whereNull('group');
                } else {
                    $query->where('group', '=', $group);
                }
            })
            ->orderBy('group')
            ->orderBy('display_name')
            ->get();
        
        $groups = Permission::select('group')->whereNotNull('group')->distinct()->get();

        return Inertia::render('Admin/Rights/Index', [
            'filters' => Request::all('name', 'group'),
            'sections' => $sections,
            'roles' => $roles,
            'permissions' => $permissions,
            'groups' => $groups
        ]);
    }
}