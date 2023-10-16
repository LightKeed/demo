<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\Filesystem\Filesystem;
use App\Services\Classes\Web\MediaService;

class MediaController extends Controller
{
    private MediaService $service;

    public function __construct(MediaService $service)
    {
        $this->service = $service;
    }

    public function show(string $path)
    {
        return $this->service->show($path);
    }

    public function image(Filesystem $filesystem, string $path = null)
    {
        return $this->service->image($filesystem, $path);
    }
}
