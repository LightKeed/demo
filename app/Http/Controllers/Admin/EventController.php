<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Event\EventCreateRequest;
use App\Http\Requests\Admin\Event\EventUpdateRequest;
use App\Services\Classes\Admin\EventService;

class EventController extends Controller
{
    private EventService $service;

    public function __construct(EventService $service)
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

    public function store(EventCreateRequest $request)
    {
        return $this->service->store($request);
    }

    public function edit(int $id)
    {
        return $this->service->edit($id);
    }

    public function update(EventUpdateRequest $request)
    {
        return $this->service->update($request);
    }

    public function restore(int $id)
    {
        return $this->service->restore($id);
    }

    public function visible(int $id)
    {
        return $this->service->visible($id);
    }

    public function destroy(int $id)
    {
        return $this->service->remove($id);
    }
}
