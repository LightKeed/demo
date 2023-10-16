<?php

namespace App\Services\Classes\Admin;

use App\Services\BaseService;
use Inertia\Inertia;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\Admin\Slider\SlideCreateRequest;
use App\Http\Requests\Admin\Slider\SlideUpdateRequest;
use App\Models\Slide;

final class SlideService extends BaseService
{
    public function store(SlideCreateRequest $request)
    {
        $slide = new Slide($request->validated());

        $slide->save();

        $this->log->add('create', 'slide', $slide->id, $slide->name);

        return redirect()->back()->with([
            'success' => 'Слайд успешно создан.'
        ]);
    }

    public function update(SlideUpdateRequest $request)
    {
        $slide = $this->getSlideByID($request->id);

        $slide->update($request->validated());

        $this->log->add('update', 'slide', $slide->id, $slide->name);

        return redirect()->back()->with([
            'success' => 'Слайд успешно обновлен.'
        ]);
    }

    public function remove(int $id)
    {
        try {
            $slide = $this->getSlideByID($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(['error' => 'Слайд не найден.']);
        }

        $slide->delete();

        $this->log->add('delete', 'slide', $slide->id, $slide->name);

        return redirect()->back()->with([
            'success' => 'Слайд успешно удален.'
        ]);
    }

    private function getSlideByID(int $id)
    {
        return Slide::where('id', '=', $id)
            ->firstOrFail();
    }
}
