<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Article\ArticleCreateRequest;
use App\Http\Requests\Admin\Article\ArticleUpdateRequest;
use App\Services\Classes\Admin\ArticleService;

class ArticleController extends Controller
{
    private ArticleService $service;

    public function __construct(ArticleService $service)
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

    public function store(ArticleCreateRequest $request)
    {
        return $this->service->store($request);
    }

    public function edit(int $id)
    {
        return $this->service->edit($id);
    }

    public function update(ArticleUpdateRequest $request)
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
