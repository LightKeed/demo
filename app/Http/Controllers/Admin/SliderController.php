<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Slider\SliderCreateRequest;
use App\Http\Requests\Admin\Slider\SliderUpdateRequest;
use App\Http\Requests\Admin\Slider\SlideCreateRequest;
use App\Http\Requests\Admin\Slider\SlideUpdateRequest;
use App\Services\Classes\Admin\SliderService;
use App\Services\Classes\Admin\SlideService;

class SliderController extends Controller
{
    private SliderService $service;
    private SlideService $serviceSlide;

    public function __construct(SliderService $service, SlideService $serviceSlide)
    {
        $this->service = $service;
        $this->serviceSlide = $serviceSlide;
    }

    public function index()
    {
        return $this->service->index();
    }

    public function store(SliderCreateRequest $request)
    {
        return $this->service->store($request);
    }

    public function edit(int $id)
    {
        return $this->service->edit($id);
    }

    public function update(SliderUpdateRequest $request)
    {
        return $this->service->update($request);
    }

    public function destroy(int $id)
    {
        return $this->service->remove($id);
    }

    public function storeSlide(SlideCreateRequest $request)
    {
        return $this->serviceSlide->store($request);
    }

    public function updateSlide(SlideUpdateRequest $request)
    {
        return $this->serviceSlide->update($request);
    }

    public function destroySlide(int $id)
    {
        return $this->serviceSlide->remove($id);
    }
}
