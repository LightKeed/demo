<?php

namespace App\Services\Classes\Web;

use App\Services\BaseService;
use App\Models\Category;

final class CategoryService extends BaseService
{
    public function getAll()
    {
        return Category::orderBy('sort_order', 'desc')
            ->get();
    }

    static public function getAllMenu()
    {
        return Category::select('id', 'name', 'slug', 'enabled', 'sort_order', 'parent_id')
            ->with([
                'articles:id,title,slug,enabled,enabled_menu,category_id',
                'articles' => function ($query) { $query->isEnabled()->isEnabledMenu(); },
                'childrenCategories:id,name,slug,enabled,sort_order,parent_id',
                'childrenCategories.childrenCategories:id,name,slug,enabled,sort_order,parent_id',
                'childrenCategories' => function ($query) { $query->isEnabled(); },
                'childrenCategories.childrenCategories' => function ($query) { $query->isEnabled(); },
                'childrenCategories.articles:id,title,slug,enabled,enabled_menu,category_id',
                'childrenCategories.childrenCategories.articles:id,title,slug,enabled,enabled_menu,category_id',
                'childrenCategories.articles' => function ($query) { $query->isEnabled()->isEnabledMenu(); },
                'childrenCategories.childrenCategories.articles' => function ($query) { $query->isEnabled()->isEnabledMenu(); },
            ])
            ->whereNull('parent_id')
            ->isEnabled()
            ->orderBy('sort_order')
            ->get();
    }

    static public function getById(int $id)
    {
        return Category::where('id', '=', $id)
            ->isEnabled()
            ->firstOrFail();
    }

    static public function search(string $val)
    {
        return Category::where('name', 'LIKE', "%$val%")
            ->orWhere('slug', 'LIKE', "%$val%")
            ->isEnabled()
            ->orderBy('name')
            ->get();
    }
}
