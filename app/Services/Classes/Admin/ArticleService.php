<?php

namespace App\Services\Classes\Admin;

use App\Services\BaseService;
use Inertia\Inertia;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\Admin\Article\ArticleCreateRequest;
use App\Http\Requests\Admin\Article\ArticleUpdateRequest;
use App\Models\Article;
use App\Models\Category;
use App\Models\Slider;

final class ArticleService extends BaseService
{
    public function index()
    {
        $articles = Article::filter(Request::only('title', 'category', 'archive'))
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->orderBy('category_id')
            ->paginate(20)
            ->withQueryString();

        $categories = $this->getAvailableCategories();

        return Inertia::render('Admin/Article/Index', [
            'filters' => Request::all('title', 'category', 'archive'),
            'categories' => $categories,
            'articles' => $articles
        ]);
    }

    public function create()
    {
        $categories = $this->getAvailableCategories();
        $sliders = $this->getAvaibleSliders();

        return Inertia::render('Admin/Article/Create', [
            'categories' => $categories,
            'sliders' => $sliders
        ]);
    }

    public function store(ArticleCreateRequest $request)
    {
        $slug = $request->slug ?? $this->getSlug($request->title);

        if($this->checkArticleSlug($slug, $request->category_id)) {
            return redirect()->back()->with(['error' => 'URL страницы является неуникальным для выбранной категории.']);
        }

        $article = new Article([
            'title' => $request->title,
            'slug' => $slug,
            'slug_alternative' => $request->slug_alternative,
            'owner_id' => Auth::id(),
            'moder_id' => Auth::id(),
            'category_id' => $request->category_id,
            'poster_id' => $request->poster_id,
            'background_id' => $request->background_id,
            'slider_id' => $request->slider_id,
            'description' => $request->description,
            'text' => $request->text ?? [],
            'sort_order' => $request->sort_order,
            'enabled' => $request->enabled,
            'enabled_menu' => $request->enabled_menu
        ]);

        $article->save();

        $this->log->add('create', 'article', $article->id, $article->title);

        return to_route('Admin.ArticleIndex')->with([
            'success' => 'Страница успешно создана.'
        ]);
    }

    public function edit(int $id)
    {
        $categories = $this->getAvailableCategories();
        $sliders = $this->getAvaibleSliders();

        return Inertia::render('Admin/Article/Edit', [
            'article' => $this->getArticleByID($id),
            'categories' => $categories,
            'sliders' => $sliders
        ]);
    }

    public function update(ArticleUpdateRequest $request)
    {
        $article = $this->getArticleByID($request->id);

        $slug = !$article->slug && !$request->slug ? $this->getSlug($request->title) : ($request->slug != $article->slug || $request->title != $article->title ? $request->slug ?? $this->getSlug($request->title) : $article->slug);

        if($this->checkArticleSlug($slug, $article->category_id, $article->id)) {
            return redirect()->back()->with(['error' => 'URL страницы является неуникальным для выбранной категории.']);
        }

        $article->update([
            'title' => $request->title,
            'slug' => $slug,
            'slug_alternative' => $request->slug_alternative,
            'moder_id' => Auth::id(),
            'category_id' => $request->category_id,
            'poster_id' => $request->poster_id,
            'background_id' => $request->background_id,
            'slider_id' => $request->slider_id,
            'description' => $request->description,
            'text' => $request->text ?? [],
            'sort_order' => $request->sort_order,
            'enabled' => $request->enabled,
            'enabled_menu' => $request->enabled_menu
        ]);

        $this->log->add('update', 'article', $article->id, $article->title);
        
        return redirect()->back()->with([
            'success' => 'Страница успешно обновлена.'
        ]);
    }

    public function restore(int $id)
    {
        try {
            $article = $this->getArticleByID($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(['error' => 'Страница не найдена.']);
        }

        $article->restore();

        $this->log->add('restore', 'article', $article->id, $article->title);

        return redirect()->back()->with([
            'success' => 'Страница успешно восстановлена.'
        ]);
    }

    public function visible(int $id)
    {
        try {
            $article = $this->getArticleByID($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(['error' => 'Страница не найдена.']);
        }

        $visible = !$article->enabled;

        $article->update(['enabled' => $visible]);

        $this->log->add($visible ? 'displayed' : 'hidden', 'article', $article->id, $article->title);

        return redirect()->back()->with([
            'success' => 'Страница успешно ' . ($visible ? 'отображена' : 'скрыта') . '.'
        ]);
    }

    public function remove(int $id)
    {
        try {
            $article = $this->getArticleByID($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(['error' => 'Страница не найдена.']);
        }

        $trashed = $article->trashed();
        $trashed ? $article->forceDelete() : $article->delete();

        $this->log->add($trashed ? 'force_delete' : 'delete', 'article', $article->id, $article->title);

        if ($trashed) {
            return to_route('Admin.ArticleIndex')->with([
                'success' => 'Страница успешно удалена.'
            ]);
        } else {
            return redirect()->back()->with([
                'success' => 'Страница успешно удалена.'
            ]);
        }
    }

    private function checkArticleSlug(string $slug, int $category_id = null, int $id = null)
    {
        return Article::withTrashed()
            ->where([
                ['slug', '=', $slug],
                ['category_id', '=', $category_id]
            ])
            ->when($id, function ($query, $id) {
                $query->where('id', '!=', $id);
            })
            ->first();
    }

    private function getArticleByID(int $id)
    {
        return Article::withTrashed()
            ->with(['category', 'owner', 'moder'])
            ->where('id', '=', $id)
            ->firstOrFail();
    }

    private function getAvailableCategories()
    {
        return Category::select('id', 'name', 'slug', 'enabled', 'parent_id')
            ->whereNull('parent_id')
            ->with([
                'childrenCategories' => fn($query) => $query->isEnabled()->with([
                    'childrenCategories' => fn($query) => $query->isEnabled()
                ])
            ])
            ->isEnabled()
            ->orderBy('sort_order')
            ->get();
    }

    private function getAvaibleSliders()
    {
        return Slider::select('id', 'name', 'enabled')
            ->isEnabled()
            ->with('slides')
            ->orderby('name')
            ->get();
    }
}
