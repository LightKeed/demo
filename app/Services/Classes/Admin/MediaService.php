<?php

namespace App\Services\Classes\Admin;

use App\Services\BaseService;
use Inertia\Inertia;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\Media\MediaCreateRequest;
use App\Http\Requests\Admin\Media\MediaUpdateRequest;
use App\Http\Requests\Admin\Media\MediaMultipleRestoreDeleteRequest;
use App\Models\Media;

final class MediaService extends BaseService
{
    public function index()
    {
        $media = $this->getMedia();

        return Inertia::render('Admin/Media/Index', [
            'filters' => Request::all('title', 'type', 'created', 'archive', 'my'),
            'media' => $media
        ]);
    }

    public function manager()
    {
        $media = $this->getMedia(20);

        return response()->json($media);
    }

    public function store(MediaCreateRequest $request)
    {
        foreach($request->validated()['files'] as $file) {
            try {
                $path = $this->fileUpload($file);

                $extension = \File::extension($path) ?? null;

                $media = new Media([
                    'fullname' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                    'path' => $path,
                    'type' => $this->getFileType($extension),
                    'extension' => $extension,
                    'size' => Storage::disk('local')->size($path),
                    'owner_id' => Auth::id(),
                    'moder_id' => Auth::id()
                ]);

                $media->save();
            } catch (\Exception $e) {
                return redirect()->route('Admin.MediaIndex')->withErrors([$e->getMessage()]);
            }
        }

        return redirect()->back()->with([
            'success' => 'Файлы успешно загружены.'
        ]);
    }

    public function upload(MediaCreateRequest $request)
    {
        $file = $request->validated()['files'][0];

        if($path = $this->fileUpload($file)) {
            $extension = \File::extension($path) ?? null;
            $type = $this->getFileType($extension);

            if($request->type && !in_array($type, is_array($request->type) ? $request->type : [$request->type])) {
                return response()->json([
                    'message' => 'Недопустимый тип файла.'
                ], 400);
            }

            $media = new Media([
                'fullname' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'path' => $path,
                'type' => $type,
                'extension' => $extension,
                'size' => Storage::disk('local')->size($path),
                'owner_id' => Auth::id(),
                'moder_id' => Auth::id()
            ]);

            $media->save();

            return response()->json([
                'id' => $media->id,
                'fullname' => $media->fullname,
                'path' => $media->path,
                'size' => $media->size,
                'extension' => $media->extension
            ]);
        } else {
            return response()->json([
                    'message' => 'При загрузке файла возникла ошибка.'
                ], 400);
        }
    }

    public function update(MediaUpdateRequest $request)
    {
        $media = $this->getMediaByID($request->id);

        $media->update([
            'description' => $request->description,
            'moder_id' => Auth::id()
        ]);

        return redirect()->back()->with([
            'success' => 'Информация о файле успешно обновлена.'
        ]);
    }

    public function restore(int $id)
    {
        try {
            $media = $this->getMediaByID($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(['error' => 'Файл не найден.']);
        }

        $media->restore();

        return redirect()->back()->with([
            'success' => 'Файл успешно восстановлен.'
        ]);
    }

    public function restoreMultiple(MediaMultipleRestoreDeleteRequest $request)
    {
        try {
            foreach($request->items as $id) {
                $media = $this->getMediaByID($id);

                $media->restore();
            }
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(['error' => 'Один или несколько файлов не найдены.']);
        }

        return redirect()->back()->with([
            'success' => 'Выбранные файлы успешно восстановлены.'
        ]);
    }

    public function remove(int $id)
    {
        try {
            $media = $this->getMediaByID($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(['error' => 'Файл не найден.']);
        }

        $media->trashed() ? $media->forceDelete() : $media->delete();

        return redirect()->back()->with([
            'success' => 'Файл успешно удален.'
        ]);
    }

    public function removeMultiple(MediaMultipleRestoreDeleteRequest $request)
    {
        try {
            foreach($request->items as $id) {
                $media = $this->getMediaByID($id);

                $media->trashed() ? $media->forceDelete() : $media->delete();
            }
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(['error' => 'Один или несколько файлов не найдены.']);
        }

        return redirect()->back()->with([
            'success' => 'Выбранные файлы успешно удалены.'
        ]);
    }

    private function getMedia($limit = 48)
    {
        return Media::with(['owner', 'moder'])
            ->filter(Request::only('title', 'type', 'created', 'archive', 'my'))
            ->orderBy('created_at', 'desc')
            ->paginate($limit)
            ->withQueryString();
    }

    private function getMediaByID(int $id)
    {
        return Media::withTrashed()
            ->where('id', '=', $id)
            ->firstOrFail();
    }

    private function getFileType(string $extension)
    {
        if (preg_match('/(jpe?g|jpg|png|bmp|webp|svg|gif|ico)$/', $extension))
            return 'image';
        else if (preg_match('/(mp3|flac|aac|wav|alac|dsd|ogg|mqa|wma)$/', $extension))
            return 'audio';
        else if (preg_match('/(mp4|mov|wmv|avi|avchd|flv|f4v|swf|mkv|webm|mpeg|m4v|vob|3gp|3gp2)$/', $extension))
            return 'video';
        else if (preg_match('/(doc|docx|docxf|docm|dotm|htm|html|odt|pdf|rtf|txt|wps|xml|xps|pptp|ptx|pptm)$/', $extension))
            return 'document';
        else if (preg_match('/(csv|dbf|ods|xla|xlam|xls|xlsb|xlsm|xlsx|xlt|xltm|xlw)$/', $extension))
            return 'table';
        else if (preg_match('/(zip|rar|tar|7z|ace|gz|gzip|jar|tar-gz|tgz|xar|zipx)$/', $extension))
            return 'archive';

        return null;
    }
}
