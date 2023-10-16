<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Classes\Admin\HomeService;

class HomeController extends Controller
{
    private HomeService $service;

    public function __construct(HomeService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service->index();
    }

    public function newsupload(Request $request)
    {
        return $this->service->newsupload($request);
    }

    public function newsstore(Request $request)
    {
        return $this->service->newsstore($request);
    }
}
