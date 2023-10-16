<?php

namespace App\Services\Classes\Admin;

use App\Services\BaseService;
use Inertia\Inertia;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\Admin\Event\EventCreateRequest;
use App\Http\Requests\Admin\Event\EventUpdateRequest;
use App\Models\Event;
use App\Models\Tag;

final class EventService extends BaseService
{
    public function index()
    {
        $events = Event::filter(Request::only('title', 'tag', 'archive'))
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        $tags = $this->getAvailableTags();

        return Inertia::render('Admin/Event/Index', [
            'filters' => Request::all('title', 'tag', 'archive'),
            'tags' => $tags,
            'events' => $events
        ]);
    }

    public function create()
    {
        $tags = $this->getAvailableTags();

        return Inertia::render('Admin/Event/Create', [
            'tags' => $tags
        ]);
    }

    public function store(EventCreateRequest $request)
    {
        $slug = $request->slug ?? $this->getSlug($request->title);

        $event = new Event([
            'title' => $request->title,
            'slug' => $slug,
            'slug_alternative' => $request->slug_alternative,
            'owner_id' => Auth::id(),
            'moder_id' => Auth::id(),
            'poster_id' => $request->poster_id,
            'background_id' => $request->background_id,
            'description' => $request->description,
            'text' => $request->text ?? [],
            'recording' => $request->recording,
            'enabled' => $request->enabled,
            'event_start_at' => $request->event_start_at,
            'event_end_at' => $request->event_end_at,
            'record_start_at' => $request->record_start_at,
            'record_end_at' => $request->record_end_at
        ]);

        $event->save();

        $this->attachTags($event, $request->validated()['tags']);

        $this->log->add('create', 'event', $event->id, $event->title);

        return to_route('Admin.EventIndex')->with([
            'success' => 'Мероприятие успешно создано.'
        ]);
    }

    public function edit(int $id)
    {
        $tags = $this->getAvailableTags();

        return Inertia::render('Admin/Event/Edit', [
            'event' => $this->getEventByID($id),
            'tags' => $tags
        ]);
    }

    public function update(EventUpdateRequest $request)
    {
        $event = $this->getEventByID($request->id);

        $slug = !$event->slug && !$request->slug ? $this->getSlug($request->title) : ($request->slug != $event->slug || $request->title != $event->title ? $request->slug ?? $this->getSlug($request->title) : $event->slug);

        $event->update([
            'title' => $request->title,
            'slug' => $slug,
            'slug_alternative' => $request->slug_alternative,
            'moder_id' => Auth::id(),
            'poster_id' => $request->poster_id,
            'background_id' => $request->background_id,
            'description' => $request->description,
            'text' => $request->text ?? [],
            'recording' => $request->recording,
            'enabled' => $request->enabled,
            'event_start_at' => $request->event_start_at,
            'event_end_at' => $request->event_end_at,
            'record_start_at' => $request->record_start_at,
            'record_end_at' => $request->record_end_at
        ]);

        $this->attachTags($event, $request->validated()['tags']);

        $this->log->add('update', 'event', $event->id, $event->title);

        return redirect()->back()->with([
            'success' => 'Мероприятие успешно обновлено.'
        ]);
    }

    public function restore(int $id)
    {
        try {
            $event = $this->getEventByID($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(['error' => 'Мероприятие не найдено.']);
        }

        $event->restore();

        $this->log->add('restore', 'event', $event->id, $event->title);

        return redirect()->back()->with([
            'success' => 'Мероприятие успешно восстановлено.'
        ]);
    }

    public function visible(int $id)
    {
        try {
            $event = $this->getEventByID($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(['error' => 'Мероприятие не найдено.']);
        }

        $visible = !$event->enabled;

        $event->update(['enabled' => $visible]);

        $this->log->add($visible ? 'displayed' : 'hidden', 'event', $event->id, $event->title);

        return redirect()->back()->with([
            'success' => 'Мероприятие успешно ' . ($visible ? 'отображено' : 'скрыто') . '.'
        ]);
    }

    public function remove(int $id)
    {
        try {
            $event = $this->getEventByID($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(['error' => 'Мероприятие не найдено.']);
        }

        $trashed = $event->trashed();
        $trashed ? $event->forceDelete() : $event->delete();

        $this->log->add($trashed ? 'force_delete' : 'delete', 'event', $event->id, $event->title);

        if ($trashed) {
            return to_route('Admin.EventIndex')->with([
                'success' => 'Мероприятие успешно удалено.'
            ]);
        } else {
            return redirect()->back()->with([
                'success' => 'Мероприятие успешно удалено.'
            ]);
        }
    }

    private function getEventByID(int $id)
    {
        return Event::withTrashed()
            ->with(['owner', 'moder'])
            ->where('id', '=', $id)
            ->firstOrFail();
    }

    private function getAvailableTags()
    {
        return Tag::where('scope', '=', 'events')
            ->orWhere('scope', '=', null)
            ->orderBy('name')
            ->get();
    }
}
