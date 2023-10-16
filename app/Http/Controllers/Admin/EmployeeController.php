<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Employee\EmployeeCreateRequest;
use App\Http\Requests\Admin\Employee\EmployeeUpdateRequest;
use App\Services\Classes\Admin\EmployeeService;

class EmployeeController extends Controller
{
    private EmployeeService $service;

    public function __construct(EmployeeService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service->index();
    }
    
    public function create()
    {
        return $this->service->create();
    }

    public function store(EmployeeCreateRequest $request)
    {
        return $this->service->store($request);
    }

    public function edit(int $id)
    {
        return $this->service->edit($id);
    }

    public function update(EmployeeUpdateRequest $request)
    {
        return $this->service->update($request);
    }

    public function destroy(int $id)
    {
        return $this->service->remove($id);
    }
}
