<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Rights\RoleCreateRequest;
use App\Http\Requests\Admin\Rights\RoleUpdateRequest;
use App\Http\Requests\Admin\Rights\PermissionCreateRequest;
use App\Http\Requests\Admin\Rights\PermissionUpdateRequest;
use App\Services\Classes\Admin\RightsService;
use App\Services\Classes\Admin\RoleService;
use App\Services\Classes\Admin\PermissionService;
use App\Services\Classes\Admin\IndividualRightsService;

class RightsController extends Controller
{
    private RightsService $service;
    private RoleService $serviceRole;
    private PermissionService $servicePermission;
    private IndividualRightsService $serviceIndividualRights;

    public function __construct(RightsService $service, RoleService $serviceRole, PermissionService $servicePermission, IndividualRightsService $serviceIndividualRights)
    {
        $this->service = $service;
        $this->serviceRole = $serviceRole;
        $this->servicePermission = $servicePermission;
        $this->serviceIndividualRights = $serviceIndividualRights;
    }

    public function index()
    {
        return $this->service->index();
    }

    public function storeRole(RoleCreateRequest $request)
    {
        return $this->serviceRole->store($request);
    }

    public function attachRole(Request $request)
    {
        return $this->serviceRole->attach($request);
    }

    public function editRole(int $id)
    {
        return $this->serviceRole->edit($id);
    }

    public function updateRole(RoleUpdateRequest $request)
    {
        return $this->serviceRole->update($request);
    }

    public function destroyRole(int $id)
    {
        return $this->serviceRole->remove($id);
    }

    public function storePermission(PermissionCreateRequest $request)
    {
        return $this->servicePermission->store($request);
    }

    public function attachPermission(Request $request)
    {
        return $this->servicePermission->attach($request);
    }

    public function editPermission(int $id)
    {
        return $this->servicePermission->edit($id);
    }

    public function updatePermission(PermissionUpdateRequest $request)
    {
        return $this->servicePermission->update($request);
    }

    public function destroyPermission(int $id)
    {
        return $this->servicePermission->remove($id);
    }

    public function individualAttach(Request $request)
    {
        return $this->serviceIndividualRights->attach($request);
    }

    public function individualDetach(Request $request)
    {
        return $this->serviceIndividualRights->detach($request);
    }

    public function individualModels(Request $request)
    {
        return $this->serviceIndividualRights->models($request);
    }

    public function individualUsers()
    {
        return $this->serviceIndividualRights->users();
    }
}
