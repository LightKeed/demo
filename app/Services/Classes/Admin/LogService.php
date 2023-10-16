<?php

namespace App\Services\Classes\Admin;

use App\Services\BaseService;
use Inertia\Inertia;
use Illuminate\Support\Facades\Request;
use App\Models\Log;
use App\Models\User;

final class LogService extends BaseService
{
    public function index()
    {
        $logs = Log::filter(Request::only('date', 'method', 'action', 'component', 'user', 'ip'))
                ->orderBy('created_at', 'desc')
                ->paginate(50);

        $users = User::select('id', 'surname', 'name', 'patronymic')->orderBy('surname')->get();

        return Inertia::render('Admin/Log/Index', [
            'filters' => Request::all('date', 'method', 'action', 'component', 'user', 'ip'),
            'logs' => $logs,
            'users' => $users
        ]);
    }
}
