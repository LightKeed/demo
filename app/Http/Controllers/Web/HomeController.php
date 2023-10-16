<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Classes\Web\HomeService;

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

    public function show(string $path, string $subPath = '', string $subChild = '', string $page = '')
    {
        return $this->service->show($path, $subPath, $subChild, $page);
    }

    public function search(Request $request)
    {
        return $this->service->search($request);
    }
}
