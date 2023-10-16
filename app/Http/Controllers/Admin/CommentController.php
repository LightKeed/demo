<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Comment\CommentUpdateRequest;
use App\Services\Classes\Admin\CommentService;

class CommentController extends Controller
{
    private CommentService $service;

    public function __construct(CommentService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service->index();
    }

    public function edit(int $id)
    {
        return $this->service->edit($id);
    }

    public function update(CommentUpdateRequest $request, int $id)
    {
        return $this->service->update($request, $id);
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
