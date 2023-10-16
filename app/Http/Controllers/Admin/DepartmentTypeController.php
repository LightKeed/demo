<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DepartmentType\TypeCreateRequest;
use App\Http\Requests\Admin\DepartmentType\TypeUpdateRequest;
use App\Services\Classes\Admin\DepartmentTypeService;

class DepartmentTypeController extends Controller
{
    private DepartmentTypeService $service;

    public function __construct(DepartmentTypeService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service->index();
    }

    public function store(TypeCreateRequest $request)
    {
        return $this->service->store($request);
    }

    public function edit(int $id)
    {
        return $this->service->edit($id);
    }

    public function update(TypeUpdateRequest $request)
    {
        return $this->service->update($request);
    }

    public function destroy(int $id)
    {
        return $this->service->remove($id);
    }
}
