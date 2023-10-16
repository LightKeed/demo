<?php

namespace App\Services\Classes\Web;

use App\Services\BaseService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\Filesystem;
use League\Glide\Responses\LaravelResponseFactory;
use League\Glide\ServerFactory;
use App\Models\Media;

final class MediaService extends BaseService
{
    public function show($path)
    {
        if (!Storage::disk('local')->exists($path)) abort(404);

        return Storage::disk('local')->response($path);
    }

    public function image(Filesystem $filesystem, string $value = null)
    {
        if(!$value || !Storage::disk('local')->exists($value)) {
            $media = Media::where('id', $value)->first();
            $value = $media && Storage::disk('local')->exists($media->path) ? $media->path : 'default.webp';
        }

        $extension = pathinfo($value, PATHINFO_EXTENSION);

        if($extension !== 'svg') {
            $server = ServerFactory::create([
                'response' => new LaravelResponseFactory(app('request')),
                'source' => $filesystem->getDriver(),
                'cache' => Storage::disk('local')->getDriver(),
                'cache_path_prefix' => '.cache',
                'base_url' => 'img',
            ]);

            return $server->getImageResponse($value, request()->all());
        } else {
            return Storage::disk('local')->response($value);
        }
    }
}
