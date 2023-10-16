<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Classes\Admin\ProfileService;
use Illuminate\Contracts\Auth\StatefulGuard;

class ProfileController extends Controller
{
    private ProfileService $service;

    public function __construct(ProfileService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service->index();
    }

    public function closeSessions(StatefulGuard $guard)
    {
        return $this->service->closeSessions($guard);
    }
}
