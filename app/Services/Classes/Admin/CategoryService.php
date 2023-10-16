<?php

namespace App\Services\Classes\Admin;

use App\Services\BaseService;
use Inertia\Inertia;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\Admin\Category\CategoryCreateRequest;
use App\Http\Requests\Admin\Category\CategoryUpdateRequest;
use App\Models\Category;
use App\Models\Article;

final class CategoryService extends BaseService
{
    public function index()
    {
        $categories = Category::whereNull('parent_id')
            ->filter(Request::only('title', 'archive'))
            ->orderBy('sort_order')
            ->with('articles:id,title,slug,category_id')
            ->get();

        foreach ($categories as $category) {
            $pages = Article::where('category_id', '=', $category->id)
                ->whereRelation('category', 'id', '=', $category->id)
                ->orWhereRelation('category.parent', 'id', '=', $category->id)
                ->orWhereRelation('category.parent.parent', 'id', '=', $category->id)
                ->count();

            $childs = Category::where('id', '!=', $category->id)
                ->whereRelation('parent', 'id', '=', $category->id)
                ->orWhereRelation('parent.parent', 'id', '=', $category->id)
                ->count();

            $category->articlesCount = $pages;
            $category->childsCount = $childs;
        }

        return Inertia::render('Admin/Category/Index', [
            'filters' => Request::all('title', 'archive'),
            'categories' => $categories,
            'reserved_slug' => json_decode(config('settings.reserved_slug'))
        ]);
    }

    public function store(CategoryCreateRequest $request)
    {
        $slug = $request->slug ?? $this->getSlug($request->name);

        if($this->uniqueSlug(Category::class, $slug)) {
            return redirect()->back()->with([
                'error' => 'Проверьте список разделов, возможно данный раздел уже существует.'
            ]);
        }

        $category = new Category([
            'name' => $request->name,
            'slug' => $slug ,
            'slug_alternative' => $request->slug_alternative,
            'parent_id' => $request->parent_id,
            'background_id' => $request->background_id,
            'sort_order' => $request->sort_order,
            'enabled' => $request->enabled
        ]);

        $category->save();

        $this->log->add('create', 'category', $category->id, $category->name);

        return redirect()->back()->with([
            'success' => 'Раздел успешно создан.'
        ]);
    }

    public function edit(int $id)
    {
        return Inertia::render('Admin/Category/Edit', [
            'category' => $this->getCategoryByID($id)
        ]);
    }

    public function update(CategoryUpdateRequest $request)
    {
        $category = $this->getCategoryByID($request->id);

        $slug = $request->slug != $category->slug || $request->title != $category->title ? $request->slug ?? $this->getSlug($request->name) : $category->slug;

        if($this->uniqueSlug(Category::class, $slug, $category->id)) {
            return redirect()->back()->with([
                'error' => 'Проверьте список разделов, возможно данный раздел уже существует.'
            ]);
        }

        $category->update([
            'name' => $request->name,
            'slug' => $slug,
            'slug_alternative' => $request->slug_alternative,
            'background_id' => $request->background_id,
            'sort_order' => $request->sort_order,
            'enabled' => $request->enabled
        ]);

        $this->log->add('update', 'category', $category->id, $category->name);

        return redirect()->back()->with([
            'success' => 'Раздел успешно обновлен.'
        ]);
    }

    public function restore(int $id)
    {
        try {
            $category = $this->getCategoryByID($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(['error' => 'Раздел не найден.']);
        }

        $category->restore();

        $this->log->add('restore', 'category', $category->id, $category->name);

        return redirect()->back()->with([
            'success' => 'Раздел успешно восстановлен.'
        ]);
    }

    public function visible(int $id)
    {
        try {
            $category = $this->getCategoryByID($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(['error' => 'Раздел не найден.']);
        }

        $visible = !$category->enabled;

        $category->update(['enabled' => $visible]);

        $this->log->add($visible ? 'displayed' : 'hidden', 'category', $category->id, $category->name);

        return redirect()->back()->with([
            'success' => 'Раздел успешно '. ($visible ? 'отображен' : 'скрыт') .'.'
        ]);
    }

    public function remove(int $id)
    {
        try {
            $category = $this->getCategoryByID($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(['error' => 'Раздел не найден.']);
        }

        $trashed = $category->trashed();
        $trashed ? $category->forceDelete() : $category->delete();

        $this->log->add($trashed ? 'force_delete' : 'delete', 'category', $category->id, $category->name);
        
        if($trashed) {
            return to_route('Admin.CategoryIndex')->with([
                'success' => 'Раздел успешно удален.'
            ]);
        } else {
            return redirect()->back()->with([
                'success' => 'Раздел успешно удален.'
            ]);
        }
    }

    private function getCategoryByID(int $id)
    {
        return Category::withTrashed()
            ->with([
                'articles',
                'childrenCategories' => fn($query) => $query->withTrashed()->with([
                    'childrenCategories' => fn($query) => $query->withTrashed()
                ])
            ])
            ->where('id', '=', $id)
            ->firstOrFail();
    }
}
