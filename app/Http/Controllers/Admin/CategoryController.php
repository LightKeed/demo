<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\CategoryCreateRequest;
use App\Http\Requests\Admin\Category\CategoryUpdateRequest;
use App\Services\Classes\Admin\CategoryService;

class CategoryController extends Controller
{
    private CategoryService $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service->index();
    }

    public function store(CategoryCreateRequest $request)
    {
        return $this->service->store($request);
    }

    public function edit(int $id)
    {
        return $this->service->edit($id);
    }

    public function update(CategoryUpdateRequest $request)
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
