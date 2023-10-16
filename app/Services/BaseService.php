<?php

namespace App\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use WebPConvert\Convert\Converters\Cwebp;
use Jenssegers\Agent\Agent;
use Carbon\Carbon;
use App\Helpers\LogHelper;

class BaseService
{
    public LogHelper $log;

    public function __construct(LogHelper $log)
    {
        $this->log = $log;
    }

    protected function getSlug(string $value)
    {
        return Str::slug($value);
    }

    protected function uniqueSlug($model, string $slug, int $id = null)
    {
        return $model::withTrashed()
            ->where('slug', '=', $slug)
            ->when($id, function ($query, $id) {
                $query->where('id', '!=', $id);
            })
            ->first();
    }

    protected function attachTags($model, array $tags)
    {
        $arraySync = [];
        foreach ($tags as $tag) array_push($arraySync, $tag['id']);

        $model->tags()->sync($arraySync);
    }

    protected function sessions(Request $request)
    {
        if (config('session.driver') !== 'database') {
            return collect();
        }

        return collect(
            DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
                ->where('user_id', $request->user()->getAuthIdentifier())
                ->orderBy('last_activity', 'desc')
                ->get()
        )->map(function ($session) use ($request) {
            $agent = $this->createAgent($session);

            return (object) [
                'agent' => [
                    'is_desktop' => $agent->isDesktop(),
                    'platform' => $agent->platform(),
                    'browser' => $agent->browser(),
                ],
                'device' => $agent->device(),
                'ip_address' => $session->ip_address,
                'is_current_device' => $session->id === $request->session()->getId(),
                'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
            ];
        });
    }

    protected function createAgent($session)
    {
        return tap(new Agent, function ($agent) use ($session) {
            $agent->setUserAgent($session->user_agent);
        });
    }

    protected function deleteOtherSessionRecords(Request $request)
    {
        if (config('session.driver') !== 'database') {
            return;
        }

        DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
            ->where('user_id', $request->user()->getAuthIdentifier())
            ->where('id', '!=', $request->session()->getId())
            ->delete();
    }

    protected function fileUpload(UploadedFile $file)
    {
        $uuid = Str::uuid();
        $extension = $file->getClientOriginalExtension();

        if (preg_match('/(jpe?g|jpg|png|bmp)$/', $extension)) {
            $tmp = $file->storeAs('/tmp', "$uuid.$extension", ['disk' => 'local-tmp']);
            $finalPath = Storage::disk('local-tmp')->path("webp/$uuid.webp");

            try {
                Cwebp::convert(Storage::disk('local-tmp')->path($tmp), $finalPath, []);

                Storage::disk('local')->put(
                    "webp/$uuid.webp",
                    Storage::disk('local-tmp')->get("webp/$uuid.webp")
                );

                Storage::disk('local-tmp')->delete($tmp);
                Storage::disk('local-tmp')->delete("webp/$uuid.webp");

                return "webp/$uuid.webp";
            } catch (\Exception $e) {
                Storage::disk('local-tmp')->delete($tmp);
                Storage::disk('local-tmp')->delete("webp/$uuid.webp");
                return throw new \ErrorException('Ошибка загрузки изображения');
            }
        } else {
            $filePath = $file->storeAs("/$extension", "$uuid.$extension", [
                'disk' => 'local'
            ]);

            return $filePath;
        }
    }
}
