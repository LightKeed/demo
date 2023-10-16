<?php

namespace App\Services\Classes\Admin;

use App\Services\BaseService;
use Inertia\Inertia;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\Admin\ThematicSection\SectionCreateRequest;
use App\Http\Requests\Admin\ThematicSection\SectionUpdateRequest;
use App\Models\ThematicSection;

final class ThematicSectionService extends BaseService
{
    public function index()
    {
        $sections = ThematicSection::with(['parent', 'news:id,title,section_id'])
            ->filter(Request::only('title', 'parent', 'archive'))
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        $parents = ThematicSection::whereNull('parent_id')->orderBy('name')->get();

        return Inertia::render('Admin/ThematicSection/Index', [
            'filters' => Request::all('title', 'parent', 'archive'),
            'sections' => $sections,
            'parents' => $parents
        ]);
    }

    public function store(SectionCreateRequest $request)
    {
        $slug = $request->slug ?? $this->getSlug($request->name);

        if($this->uniqueSlug(ThematicSection::class, $slug)) {
            return redirect()->back()->with([
                'error' => 'Проверьте список тематических разделов, возможно данный раздел уже существует.'
            ]);
        }

        $section = new ThematicSection([
            'name' => $request->name,
            'slug' => $slug,
            'rating' => $request->rating ?? 0,
            'parent_id' => $request->parent_id
        ]);

        $section->save();

        $this->log->add('create', 'thematic-section', $section->id, $section->name);

        return redirect()->back()->with([
            'success' => 'Тематический раздел успешно создан.'
        ]);
    }

    public function edit(int $id)
    {
        $parents = ThematicSection::whereNull('parent_id')->where('id', '!=', $id)->orderBy('name')->get();

        return Inertia::render('Admin/ThematicSection/Edit', [
            'section' => $this->getSectionByID($id),
            'parents' => $parents
        ]);
    }

    public function update(SectionUpdateRequest $request)
    {
        $section = $this->getSectionByID($request->id);

        $slug = $request->slug != $section->slug || $request->title != $section->title ? $request->slug ?? $this->getSlug($request->name) : $section->slug;

        if($this->uniqueSlug(ThematicSection::class, $slug, $section->id)) {
            return redirect()->back()->with([
                'error' => 'Проверьте список тематических разделов, возможно данный раздел уже существует.'
            ]);
        }

        $section->update([
            'name' => $request->name,
            'slug' => $slug,
            'rating' => $request->rating ?? 0,
            'parent_id' => $request->parent_id
        ]);

        $this->log->add('update', 'thematic-section', $section->id, $section->name);

        return redirect()->back()->with([
            'success' => 'Тематический раздел успешно обновлен.'
        ]);
    }

    public function restore(int $id)
    {
        try {
            $section = $this->getSectionByID($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(['error' => 'Тематический раздел не найден.']);
        }

        $section->restore();

        $this->log->add('restore', 'thematic-section', $section->id, $section->name);

        return redirect()->back()->with([
            'success' => 'Тематический раздел успешно восстановлен.'
        ]);
    }

    public function remove(int $id)
    {
        try {
            $section = $this->getSectionByID($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(['error' => 'Тематический раздел не найден.']);
        }

        $trashed = $section->trashed();
        $trashed ? $section->forceDelete() : $section->delete();

        $this->log->add($trashed ? 'force_delete' : 'delete', 'thematic-section', $section->id, $section->name);

        if($trashed) {
            return to_route('Admin.ThematicSectionIndex')->with([
                'success' => 'Тематический раздел успешно удален.'
            ]);
        } else {
            return redirect()->back()->with([
                'success' => 'Тематический раздел успешно удален.'
            ]);
        }
    }

    public function all()
    {
        $sections = ThematicSection::with(['parent'])
            ->orderBy('name')
            ->get();

        return response()->json($sections);
    }

    public function get()
    {
        return response()->json($this->getSectionByID(request()->id));
    }

    private function getSectionByID(int $id)
    {
        return ThematicSection::withTrashed()
            ->where('id', '=', $id)
            ->firstOrFail();
    }
}
