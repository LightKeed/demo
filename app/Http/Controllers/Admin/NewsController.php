<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\News\NewsCreateRequest;
use App\Http\Requests\Admin\News\NewsUpdateRequest;
use App\Services\Classes\Admin\NewsService;

class NewsController extends Controller
{
    private NewsService $service;

    public function __construct(NewsService $service)
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

    public function store(NewsCreateRequest $request)
    {
        return $this->service->store($request);
    }

    public function edit(int $id)
    {
        return $this->service->edit($id);
    }

    public function update(NewsUpdateRequest $request)
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

    public function thematic(Request $request)
    {
        return response()->json(\App\Services\Classes\Web\NewsService::takeByThematicSection($request->count, $request->section));
    }
}
