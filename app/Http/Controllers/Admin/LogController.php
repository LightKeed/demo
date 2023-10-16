<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Classes\Admin\LogService;
class LogController extends Controller
{
    private LogService $service;

    public function __construct(LogService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service->index();
    }
}
