<?php

namespace App\Services\Classes\Admin;

use App\Services\BaseService;
use Inertia\Inertia;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\Admin\Slider\SliderCreateRequest;
use App\Http\Requests\Admin\Slider\SliderUpdateRequest;
use App\Models\Slider;

final class SliderService extends BaseService
{
    public function index()
    {
        $sliders = Slider::with('slides')
            ->filter(Request::only('title', 'enabled'))
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Admin/Slider/Index', [
            'filters' => Request::all('title', 'enabled'),
            'sliders' => $sliders
        ]);
    }

    public function store(SliderCreateRequest $request)
    {
        $slider = new Slider($request->validated());

        $slider->save();

        $this->log->add('create', 'slider', $slider->id, $slider->name);

        return redirect()->back()->with([
            'success' => 'Слайдер успешно создан.'
        ]);
    }

    public function edit(int $id)
    {
        return Inertia::render('Admin/Slider/Edit', [
            'slider' => $this->getSliderByID($id)
        ]);
    }

    public function update(SliderUpdateRequest $request)
    {
        $slider = $this->getSliderByID($request->id);

        $slider->update($request->validated());

        $this->log->add('update', 'slider', $slider->id, $slider->name);

        return redirect()->back()->with([
            'success' => 'Слайдер успешно обновлен.'
        ]);
    }

    public function remove(int $id)
    {
        try {
            $slider = $this->getSliderByID($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(['error' => 'Слайдер не найден.']);
        }

        if(!$slider->can_removed) {
            return back()->with(['error' => 'Удаление данного слайдера недоступно настройками.']);
        }

        $slider->delete();

        $this->log->add('delete', 'slider', $slider->id, $slider->name);

        return to_route('Admin.SliderIndex')->with([
            'success' => 'Слайдер успешно удален.'
        ]);
    }

    private function getSliderByID(int $id)
    {
        return Slider::with(['slides' => function($query) {
                $query->orderBy('sort_order');
            }])
            ->where('id', '=', $id)
            ->firstOrFail();
    }
}
