<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Media\MediaCreateRequest;
use App\Http\Requests\Admin\Media\MediaUpdateRequest;
use App\Http\Requests\Admin\Media\MediaMultipleRestoreDeleteRequest;
use App\Services\Classes\Admin\MediaService;

class MediaController extends Controller
{
    private MediaService $service;

    public function __construct(MediaService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service->index();
    }

    public function manager()
    {
        return $this->service->manager();
    }

    public function store(MediaCreateRequest $request)
    {
        return $this->service->store($request);
    }

    public function upload(MediaCreateRequest $request)
    {
        return $this->service->upload($request);
    }

    public function update(MediaUpdateRequest $request)
    {
        return $this->service->update($request);
    }

    public function restore(int $id)
    {
        return $this->service->restore($id);
    }

    public function restoreMultiple(MediaMultipleRestoreDeleteRequest $request)
    {
        return $this->service->restoreMultiple($request);
    }

    public function destroy(int $id)
    {
        return $this->service->remove($id);
    }

    public function destroyMultiple(MediaMultipleRestoreDeleteRequest $request)
    {
        return $this->service->removeMultiple($request);
    }
}
