<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'alert' => function () use ($request) {
                return [
                    'success' => $request->session()->get('success'),
                    'error' => $request->session()->get('error'),
                ];
            },
            'auth' => function () use ($request) {
                return [
                    'user' => $request->user() ? [
                        'id' => $request->user()->id,
                        'surname' => $request->user()->surname,
                        'name' => $request->user()->name,
                        'patronymic' => $request->user()->patronymic,
                        'email' => $request->user()->email,
                        'roles' => $request->user()->roles->pluck('name'),
                        'permissions' => $request->user()->permissions->pluck('name'),
                        'article_rights' => $request->user()->articleRights->pluck('id'),
                        'news_rights' => $request->user()->newsRights->pluck('id'),
                        'event_rights' => $request->user()->eventRights->pluck('id'),
                    ] : null,
                ];
            },
        ]);
    }
}
