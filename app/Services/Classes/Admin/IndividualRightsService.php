<?php

namespace App\Services\Classes\Admin;

use App\Services\BaseService;
use Inertia\Inertia;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Article;
use App\Models\News;
use App\Models\Event;

final class IndividualRightsService extends BaseService
{
    public function attach(Request $request)
    {
        $user = User::where('id', '=', $request->user_id)->firstOrFail();

        if($request->type === 'article') {
            $article = Article::where('id', '=', $request->model_id)->firstOrFail();
            $user->articleRights()->attach($article);
        } else if($request->type === 'news') {
            $news = News::where('id', '=', $request->model_id)->firstOrFail();
            $user->newsRights()->attach($news);
        } else if($request->type === 'event') {
            $event = Event::where('id', '=', $request->model_id)->firstOrFail();
            $user->eventRights()->attach($event);
        } else {
            abort(404);
        }

        $this->log->add('attach', 'rights', $request->model_id, "Model: $request->type | Model-ID: $request->model_id | User: $request->user_id");

        return response(200);
    }

    public function detach(Request $request)
    {
        $user = User::where('id', '=', $request->user_id)->firstOrFail();

        if($request->type === 'article') {
            $article = Article::where('id', '=', $request->model_id)->firstOrFail();
            $user->articleRights()->detach($article);
        } else if($request->type === 'news') {
            $news = News::where('id', '=', $request->model_id)->firstOrFail();
            $user->newsRights()->detach($news);
        } else if($request->type === 'event') {
            $event = Event::where('id', '=', $request->model_id)->firstOrFail();
            $user->eventRights()->detach($event);
        } else {
            abort(404);
        }

        $this->log->add('detach', 'rights', $request->model_id, "Model: $request->type | Model-ID: $request->model_id | User: $request->user_id");

        return response(200);
    }

    public function models(Request $request)
    {
        $user_id = $request->user_id;
        $models = [];

        if($request->type === 'article') {
            $models = Article::select('id', 'title')
                ->when($user_id, function ($query, $user_id) {
                    $query->whereDoesntHave('userRights', function ($q) use($user_id) {
                        return $q->where('id', $user_id);
                    });
                })
                ->orderBy('title')
                ->get();
        } else if($request->type === 'news') {
            $models = News::select('id', 'title')
                ->when($user_id, function ($query, $user_id) {
                    $query->whereDoesntHave('userRights', function ($q) use($user_id) {
                        return $q->where('id', $user_id);
                    });
                })
                ->orderBy('title')
                ->get();
        } else if($request->type === 'event') {
            $models = Event::select('id', 'title')
                ->when($user_id, function ($query, $user_id) {
                    $query->whereDoesntHave('userRights', function ($q) use($user_id) {
                        return $q->where('id', $user_id);
                    });
                })
                ->orderBy('title')
                ->get();
        } else {
            abort(404);
        }

        return response()->json($models);
    }

    public function users()
    {
        $users = User::select('id', 'surname', 'name', 'patronymic', 'email')
            ->with([
                'articleRights:id,title',
                'newsRights:id,title',
                'eventRights:id,title'
            ])
            ->orderBy('surname')->get();

        return response()->json($users);
    }
}