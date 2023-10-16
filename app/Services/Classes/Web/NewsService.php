<?php

namespace App\Services\Classes\Web;

use App\Services\BaseService;
use App\Models\News;

final class NewsService extends BaseService
{
    public function getAll()
    {
        return News::orderBy('created_at', 'desc')
            ->isEnabled()
            ->paginate(30);
    }

    static public function take(int $count)
    {
        return News::select('id', 'title', 'slug', 'enabled', 'poster_id', 'background_id', 'description', 'views', 'rating', 'created_at')
            ->orderBy('created_at', 'desc')
            ->isEnabled()
            ->take($count)
            ->get();
    }

    static public function takeByThematicSection(int $count, int $section_id = null)
    {
        return News::select('id', 'title', 'slug', 'enabled', 'poster_id', 'background_id', 'description', 'views', 'rating', 'created_at')
            ->where('section_id', '=', $section_id)
            ->orderBy('created_at', 'desc')
            ->isEnabled()
            ->take($count)
            ->get();
    }

    public function getByIdOrSlug(int|string $val)
    {
        return News::where('id', '=', $val)
            ->orWhere('slug', '=', $val)
            ->isEnabled()
            ->firstOrFail();
    }

    static public function search(string $val)
    {
        return News::select('id', 'title', 'slug', 'slug_alternative', 'enabled', 'poster_id', 'background_id', 'section_id', 'description', 'views', 'rating', 'created_at')
            ->where('title', 'LIKE', "%$val%")
            ->orWhere('slug', 'LIKE', "%$val%")
            ->orWhere('description', 'LIKE', "%$val%")
            ->orWhere('text', 'LIKE', "%$val%")
            ->with('section')
            ->isEnabled()
            ->orderBy('created_at', 'desc')
            ->get();
    }

    static public function searchTake(string $val, int $count)
    {
        return News::select('id', 'title', 'slug', 'slug_alternative', 'enabled', 'poster_id', 'background_id', 'section_id', 'description', 'views', 'rating', 'created_at')
            ->where('title', 'LIKE', "%$val%")
            ->orWhere('slug', 'LIKE', "%$val%")
            ->orWhere('description', 'LIKE', "%$val%")
            ->orWhere('text', 'LIKE', "%$val%")
            ->with('section')
            ->isEnabled()
            ->orderBy('created_at', 'desc')
            ->paginate($count);
    }

    static public function searchTakeSimple(string $val, int $count)
    {
        return News::select('id', 'title', 'slug', 'slug_alternative', 'enabled', 'poster_id', 'background_id', 'section_id', 'description', 'views', 'rating', 'created_at')
            ->where('title', 'LIKE', "%$val%")
            ->orWhere('slug', 'LIKE', "%$val%")
            ->orWhere('description', 'LIKE', "%$val%")
            ->orWhere('text', 'LIKE', "%$val%")
            ->with('section')
            ->isEnabled()
            ->orderBy('created_at', 'desc')
            ->simplePaginate($count);
    }
}
