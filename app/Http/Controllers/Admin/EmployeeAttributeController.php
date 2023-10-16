<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Employee\AttributeCreateRequest;
use App\Http\Requests\Admin\Employee\AttributeUpdateRequest;
use App\Services\Classes\Admin\EmployeeAttributeService;

class EmployeeAttributeController extends Controller
{
    private EmployeeAttributeService $service;

    public function __construct(EmployeeAttributeService $service)
    {
        $this->service = $service;
    }

    public function store(AttributeCreateRequest $request)
    {
        return $this->service->store($request);
    }

    public function update(AttributeUpdateRequest $request)
    {
        return $this->service->update($request);
    }

    public function destroy(int $id)
    {
        return $this->service->remove($id);
    }
}
