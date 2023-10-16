<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Tag\TagCreateRequest;
use App\Http\Requests\Admin\Tag\TagUpdateRequest;
use App\Services\Classes\Admin\TagService;

class TagController extends Controller
{
    private TagService $service;

    public function __construct(TagService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service->index();
    }

    public function store(TagCreateRequest $request)
    {
        return $this->service->store($request);
    }

    public function edit(int $id)
    {
        return $this->service->edit($id);
    }

    public function update(TagUpdateRequest $request)
    {
        return $this->service->update($request);
    }

    public function restore(int $id)
    {
        return $this->service->restore($id);
    }

    public function destroy(int $id)
    {
        return $this->service->remove($id);
    }
}
