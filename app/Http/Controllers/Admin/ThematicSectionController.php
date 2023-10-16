<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ThematicSection\SectionCreateRequest;
use App\Http\Requests\Admin\ThematicSection\SectionUpdateRequest;
use App\Services\Classes\Admin\ThematicSectionService;

class ThematicSectionController extends Controller
{
    private ThematicSectionService $service;

    public function __construct(ThematicSectionService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service->index();
    }

    public function store(SectionCreateRequest $request)
    {
        return $this->service->store($request);
    }

    public function edit(int $id)
    {
        return $this->service->edit($id);
    }

    public function update(SectionUpdateRequest $request)
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

    public function all()
    {
        return $this->service->all();
    }

    public function get(Request $request)
    {
        return $this->service->get($request);
    }
}
