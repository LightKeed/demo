<?php

namespace App\Services\Classes\Admin;

use App\Services\BaseService;
use Inertia\Inertia;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Auth\StatefulGuard;
use Laravel\Fortify\Actions\ConfirmPassword;

final class ProfileService extends BaseService
{
    public function index()
    {
        return Inertia::render('Admin/Profile', [
            'sessions' => $this->sessions(request())->all()
        ]);
    }

    public function closeSessions(StatefulGuard $guard)
    {
        $confirmed = app(ConfirmPassword::class)(
            $guard, auth()->user(), request()->password
        );

        if (!$confirmed) {
            return back()->with(['error' => 'Предоставленный пароль является неверным.']);
        }

        $guard->logoutOtherDevices(request()->password);

        $this->deleteOtherSessionRecords(request());

        return back(303)->with([
            'success' => 'Все сеансы, кроме текущего завершены.'
        ]);
    }
}
