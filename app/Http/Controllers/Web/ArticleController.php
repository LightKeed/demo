<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Classes\Web\ArticleService;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    private ArticleService $service;

    public function __construct(ArticleService $service)
    {
        $this->service = $service;
    }

    public function searchTake(Request $request)
    {
        $articles = $this->service->searchTakeSimple($request->title, 4);

        return response()->json($articles->append('route'));
    }
}
