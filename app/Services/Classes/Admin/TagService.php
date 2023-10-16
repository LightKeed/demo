<?php

namespace App\Services\Classes\Admin;

use App\Services\BaseService;
use Inertia\Inertia;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\Admin\Tag\TagCreateRequest;
use App\Http\Requests\Admin\Tag\TagUpdateRequest;
use App\Models\Tag;

final class TagService extends BaseService
{
    public function index()
    {
        $tags = Tag::with('elements')
            ->filter(Request::only('title', 'scope', 'archive'))
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Admin/Tag/Index', [
            'filters' => Request::all('title', 'scope', 'archive'),
            'tags' => $tags
        ]);
    }

    public function store(TagCreateRequest $request)
    {
        $slug = $request->slug ?? $this->getSlug($request->name);

        if($this->uniqueSlug(Tag::class, $slug)) {
            return redirect()->back()->with([
                'error' => 'Проверьте список тегов, возможно данный тег уже существует.'
            ]);
        }

        $tag = new Tag([
            'name' => $request->name,
            'slug' => $slug,
            'rating' => $request->rating ?? 0,
            'scope' => $request->scope
        ]);

        $tag->save();

        $this->log->add('create', 'tag', $tag->id, $tag->name);

        return redirect()->back()->with([
            'success' => 'Тег успешно создан.'
        ]);
    }

    public function edit(int $id)
    {
        return Inertia::render('Admin/Tag/Edit', [
            'tag' => $this->getTagByID($id)
        ]);
    }

    public function update(TagUpdateRequest $request)
    {
        $tag = $this->getTagByID($request->id);

        $slug = $request->slug != $tag->slug || $request->title != $tag->title ? $request->slug ?? $this->getSlug($request->name) : $tag->slug;

        if($this->uniqueSlug(Tag::class, $slug, $tag->id)) {
            return redirect()->back()->with([
                'error' => 'Проверьте список тегов, возможно данный тег уже существует.'
            ]);
        }

        $tag->update([
            'name' => $request->name,
            'slug' => $slug,
            'rating' => $request->rating ?? 0,
            'scope' => $request->scope
        ]);

        $this->log->add('update', 'tag', $tag->id, $tag->name);

        return redirect()->back()->with([
            'success' => 'Тег успешно обновлен.'
        ]);
    }

    public function restore(int $id)
    {
        try {
            $tag = $this->getTagByID($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(['error' => 'Тег не найден.']);
        }

        $tag->restore();

        $this->log->add('restore', 'tag', $tag->id, $tag->name);

        return redirect()->back()->with([
            'success' => 'Тег успешно восстановлен.'
        ]);
    }

    public function remove(int $id)
    {
        try {
            $tag = $this->getTagByID($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(['error' => 'Тег не найден.']);
        }

        $trashed = $tag->trashed();
        $trashed ? $tag->forceDelete() : $tag->delete();

        $this->log->add($trashed ? 'force_delete' : 'delete', 'tag', $tag->id, $tag->name);

        if($trashed) {
            return to_route('Admin.TagIndex')->with([
                'success' => 'Тег успешно удален.'
            ]);
        } else {
            return redirect()->back()->with([
                'success' => 'Тег успешно удален.'
            ]);
        }
    }

    private function getTagByID(int $id)
    {
        return Tag::withTrashed()
            ->where('id', '=', $id)
            ->firstOrFail();
    }
}
