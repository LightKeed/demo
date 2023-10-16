<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckModelPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $type): Response
    {
        if(($user = Auth::user()) && $request->id) {
            if($user->hasRole('admin')) {
                return $next($request);
            } else if($this->checkType($type, $request->id, $user)) {
                return $next($request);
            }
        }

        return back()->with(['error' => 'Недостаточно прав для действия или доступа к разделу.']);
    }

    private function checkType($type, $model_id, $user)
    {
        if($type === 'article' && $user->can('article_edit')) {
            return !$user->articleRights()->count() ? true : $user->articleRights()->where('id', '=', $model_id)->first();
        } else if($type === 'news' && $user->can('news_edit')) {
            return !$user->newsRights()->count() ? true : $user->newsRights()->where('id', '=', $model_id)->first();
        } else if($type === 'event' && $user->can('event_edit')) {
            return !$user->eventRights()->count() ? true : $user->eventRights()->where('id', '=', $model_id)->first();
        }

        return false;
    }
}
