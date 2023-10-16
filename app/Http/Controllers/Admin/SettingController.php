<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Setting\SettingCreateRequest;
use App\Http\Requests\Admin\Setting\SettingUpdateRequest;
use App\Services\Classes\Admin\SettingService;

class SettingController extends Controller
{
    private SettingService $service;

    public function __construct(SettingService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service->index();
    }

    public function php()
    {
        return phpinfo();
    }

    public function show(string $path)
    {
        return $this->service->show($path);
    }

    public function store(SettingCreateRequest $request)
    {
        return $this->service->store($request);
    }

    public function update(SettingUpdateRequest $request)
    {
        return $this->service->update($request);
    }

    public function destroy(int $id)
    {
        return $this->service->remove($id);
    }
}
