<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Employee\PositionCreateRequest;
use App\Http\Requests\Admin\Employee\PositionUpdateRequest;
use App\Services\Classes\Admin\EmployeePositionService;

class EmployeePositionController extends Controller
{
    private EmployeePositionService $service;

    public function __construct(EmployeePositionService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service->index();
    }

    public function show()
    {
        return $this->service->show();
    }

    public function store(PositionCreateRequest $request)
    {
        return $this->service->store($request);
    }

    public function update(PositionUpdateRequest $request)
    {
        return $this->service->update($request);
    }

    public function destroy(int $id)
    {
        return $this->service->remove($id);
    }
}
