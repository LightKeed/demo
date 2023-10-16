<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Classes\Web\NewsService;

class NewsController extends Controller
{
    private NewsService $service;

    public function __construct(NewsService $service)
    {
        $this->service = $service;
    }

    public function searchTake(Request $request)
    {
        $news = $this->service->searchTakeSimple($request->title, 4);

        return response()->json($news->append('route'));
    }
}
