<?php

namespace App\Services\Classes\Web;

use App\Services\BaseService;
use App\Models\Article;

final class ArticleService extends BaseService
{
    public function getAll()
    {
        return Article::orderBy('created_at', 'desc')
            ->isEnabled()
            ->paginate(30);
    }

    public function getByIdOrSlug(int|string $val)
    {
        return Article::where('id', '=', $val)
            ->orWhere('slug', '=', $val)
            ->orWhere('slug_alternative', '=', $val)
            ->isEnabled()
            ->firstOrFail();
    }

    static public function search(string $val)
    {
        return Article::select('id', 'title', 'slug', 'slug_alternative', 'category_id', 'poster_id', 'background_id', 'description', 'sort_order', 'enabled')
            ->where('title', 'LIKE', "%$val%")
            ->orWhere('slug', 'LIKE', "%$val%")
            ->orWhere('slug_alternative', 'LIKE', "%$val%")
            ->orWhere('description', 'LIKE', "%$val%")
            ->orWhere('text', 'LIKE', "%$val%")
            ->with('category')
            ->isEnabled()
            ->orderBy('created_at', 'desc')
            ->get();
    }

    static public function searchTake(string $val, int $count)
    {
        return Article::select('id', 'title', 'slug', 'slug_alternative', 'category_id', 'poster_id', 'background_id', 'description', 'sort_order', 'enabled')
            ->where('title', 'LIKE', "%$val%")
            ->orWhere('slug', 'LIKE', "%$val%")
            ->orWhere('slug_alternative', 'LIKE', "%$val%")
            ->orWhere('description', 'LIKE', "%$val%")
            ->orWhere('text', 'LIKE', "%$val%")
            ->with('category')
            ->isEnabled()
            ->orderBy('created_at', 'desc')
            ->paginate($count);
    }

    static public function searchTakeSimple(string $val, int $count)
    {
        return Article::select('id', 'title', 'slug', 'slug_alternative', 'category_id', 'poster_id', 'background_id', 'description', 'sort_order', 'enabled')
            ->where('title', 'LIKE', "%$val%")
            ->orWhere('slug', 'LIKE', "%$val%")
            ->orWhere('slug_alternative', 'LIKE', "%$val%")
            ->orWhere('description', 'LIKE', "%$val%")
            ->orWhere('text', 'LIKE', "%$val%")
            ->with('category')
            ->isEnabled()
            ->orderBy('created_at', 'desc')
            ->simplePaginate($count);
    }
}
