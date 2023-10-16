<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Classes\Web\EventService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    private EventService $service;

    public function __construct(EventService $service)
    {
        $this->service = $service;
    }

    public function searchTake(Request $request)
    {
        $events = $this->service->searchTakeSimple($request->title, 4);

        return response()->json($events->append('route'));
    }
}
