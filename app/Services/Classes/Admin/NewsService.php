<?php

namespace App\Services\Classes\Admin;

use App\Services\BaseService;
use Inertia\Inertia;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\Admin\News\NewsCreateRequest;
use App\Http\Requests\Admin\News\NewsUpdateRequest;
use App\Models\News;
use App\Models\Tag;
use App\Models\ThematicSection;

final class NewsService extends BaseService
{
    public function index()
    {
        $news = News::select('id', 'title', 'slug', 'section_id', 'views', 'enabled', 'created_at', 'deleted_at')
            ->with('section')
            ->filter(Request::only('date', 'title', 'section', 'tag', 'archive'))
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        $sections = $this->getSections();

        $tags = $this->getAvailableTags();

        return Inertia::render('Admin/News/Index', [
            'filters' => Request::all('date', 'title', 'section', 'tag', 'archive'),
            'sections' => $sections,
            'tags' => $tags,
            'news' => $news
        ]);
    }

    public function create()
    {
        $tags = $this->getAvailableTags();
        $sections = $this->getSections();

        return Inertia::render('Admin/News/Create', [
            'tags' => $tags,
            'sections' => $sections
        ]);
    }

    public function store(NewsCreateRequest $request)
    {
        $slug = $request->slug ?? $this->getSlug($request->title);

        $news = new News([
            'title' => $request->title,
            'slug' => $slug,
            'slug_alternative' => $request->slug_alternative,
            'owner_id' => Auth::id(),
            'moder_id' => Auth::id(),
            'poster_id' => $request->poster_id,
            'background_id' => $request->background_id,
            'description' => $request->description,
            'text' => $request->text ?? [],
            'enabled' => $request->enabled,
            'section_id' => $request->section_id
        ]);

        $news->save();

        $this->attachTags($news, $request->validated()['tags']);

        $this->log->add('create', 'news', $news->id, $news->title);

        return to_route('Admin.NewsIndex')->with([
            'success' => 'Новость успешно создана.'
        ]);
    }

    public function edit(int $id)
    {
        $tags = $this->getAvailableTags();
        $sections = $this->getSections();

        return Inertia::render('Admin/News/Edit', [
            'news' => $this->getNewsByID($id),
            'tags' => $tags,
            'sections' => $sections
        ]);
    }

    public function update(NewsUpdateRequest $request)
    {
        $news = $this->getNewsByID($request->id);

        $slug = !$news->slug && !$request->slug ? $this->getSlug($request->title) : ($request->slug != $news->slug || $request->title != $news->title ? $request->slug ?? $this->getSlug($request->title) : $news->slug);

        $news->update([
            'title' => $request->title,
            'slug' => $slug,
            'slug_alternative' => $request->slug_alternative,
            'moder_id' => Auth::id(),
            'poster_id' => $request->poster_id,
            'background_id' => $request->background_id,
            'description' => $request->description,
            'text' => $request->text ?? [],
            'enabled' => $request->enabled,
            'section_id' => $request->section_id
        ]);

        $this->attachTags($news, $request->validated()['tags']);

        $this->log->add('update', 'news', $news->id, $news->title);

        return redirect()->back()->with([
            'success' => 'Новость успешно обновлена.'
        ]);
    }

    public function restore(int $id)
    {
        try {
            $news = $this->getNewsByID($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(['error' => 'Новость не найдена.']);
        }

        $news->restore();

        $this->log->add('restore', 'news', $news->id, $news->title);

        return redirect()->back()->with([
            'success' => 'Новость успешно восстановлена.'
        ]);
    }

    public function visible(int $id)
    {
        try {
            $news = $this->getNewsByID($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(['error' => 'Новость не найдена.']);
        }

        $visible = !$news->enabled;

        $news->update(['enabled' => $visible]);

        $this->log->add($visible ? 'displayed' : 'hidden', 'news', $news->id, $news->title);

        return redirect()->back()->with([
            'success' => 'Новость успешно ' . ($visible ? 'отображена' : 'скрыта') . '.'
        ]);
    }

    public function remove(int $id)
    {
        try {
            $news = $this->getNewsByID($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(['error' => 'Новость не найдена.']);
        }

        $trashed = $news->trashed();
        $trashed ? $news->forceDelete() : $news->delete();

        $this->log->add($trashed ? 'force_delete' : 'delete', 'news', $news->id, $news->title);

        if ($trashed) {
            return to_route('Admin.NewsIndex', ['archive' => 1, 'page' => request()->page ?? null])->with([
                'success' => 'Новость успешно удалена.'
            ]);
        } else {
            return redirect()->back()->with([
                'success' => 'Новость успешно удалена.'
            ]);
        }
    }

    private function getNewsByID(int $id)
    {
        return News::withTrashed()
            ->with(['owner', 'moder'])
            ->where('id', '=', $id)
            ->firstOrFail();
    }

    private function getAvailableTags()
    {
        return Tag::where('scope', '=', 'news')
            ->orWhere('scope', '=', null)
            ->orderBy('name')
            ->get();
    }

    private function getSections()
    {
        return ThematicSection::with('parent')
            ->orderBy('name')
            ->get();
    }
}
