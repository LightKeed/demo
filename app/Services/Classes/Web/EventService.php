<?php

namespace App\Services\Classes\Web;

use App\Services\BaseService;
use App\Models\Event;

final class EventService extends BaseService
{
    public function getAll()
    {
        return Event::orderBy('created_at', 'desc')
            ->isEnabled()
            ->paginate(30);
    }

    static public function take(int $count)
    {
        return Event::select('id', 'title', 'slug', 'enabled', 'poster_id', 'background_id', 'description', 'views', 'recording', 'event_start_at', 'event_end_at', 'record_start_at', 'record_end_at', 'created_at')
            ->orderBy('event_start_at', 'desc')
            ->isEnabled()
            ->take($count)
            ->get();
    }

    public function getByIdOrSlug(int|string $val)
    {
        return Event::select('id', 'title', 'slug', 'enabled', 'poster_id', 'background_id', 'description', 'views', 'recording', 'event_start_at', 'event_end_at', 'record_start_at', 'record_end_at', 'created_at')
            ->where('id', '=', $val)
            ->orWhere('slug', '=', $val)
            ->isEnabled()
            ->firstOrFail();
    }

    static public function search(string $val)
    {
        return Event::select('id', 'title', 'slug', 'slug_alternative', 'enabled', 'poster_id', 'background_id', 'description', 'views', 'recording', 'event_start_at', 'created_at')
            ->where('title', 'LIKE', "%$val%")
            ->orWhere('slug', 'LIKE', "%$val%")
            ->orWhere('description', 'LIKE', "%$val%")
            ->orWhere('text', 'LIKE', "%$val%")
            ->isEnabled()
            ->orderBy('event_start_at', 'desc')
            ->get();
    }

    static public function searchTake(string $val, int $count)
    {
        return Event::select('id', 'title', 'slug', 'slug_alternative', 'enabled', 'poster_id', 'background_id', 'description', 'views', 'recording', 'event_start_at', 'created_at')
            ->where('title', 'LIKE', "%$val%")
            ->orWhere('slug', 'LIKE', "%$val%")
            ->orWhere('description', 'LIKE', "%$val%")
            ->orWhere('text', 'LIKE', "%$val%")
            ->isEnabled()
            ->orderBy('event_start_at', 'desc')
            ->paginate($count);
    }

    static public function searchTakeSimple(string $val, int $count)
    {
        return Event::select('id', 'title', 'slug', 'slug_alternative', 'enabled', 'poster_id', 'background_id', 'description', 'views', 'recording', 'event_start_at', 'created_at')
            ->where('title', 'LIKE', "%$val%")
            ->orWhere('slug', 'LIKE', "%$val%")
            ->orWhere('description', 'LIKE', "%$val%")
            ->orWhere('text', 'LIKE', "%$val%")
            ->isEnabled()
            ->orderBy('event_start_at', 'desc')
            ->simplePaginate($count);
    }
}
