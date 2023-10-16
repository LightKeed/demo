<?php

namespace App\Services\Classes\Admin;

use App\Services\BaseService;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Media;
use App\Models\ThematicSection;
use App\Models\News;
use Illuminate\Support\Facades\DB;

final class HomeService extends BaseService
{
    public function index()
    {
        return Inertia::render('Admin/Home');
    }

    public function newsupload(Request $request)
    {
        $info = pathinfo($request->url);
        $contents = file_get_contents($request->url);
        $uploaded_file = '/tmp/' . $info['basename'];
        file_put_contents($uploaded_file, $contents);
        $file = new UploadedFile($uploaded_file, $info['basename']);

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
                'owner_id' => 1,
                'moder_id' => 1
            ]);

            $media->save();

            return response()->json([
                'id' => $media->id,
            ]);
        } else {
            return response()->json([
                'message' => 'При загрузке файла возникла ошибка.'
            ], 400);
        }
    }

    public function newsstore(Request $request)
    {
        if(!News::where('slug_alternative', '=', $request->slug_alternative)->first()) {
            $slug = $this->getSlug($request->title);

            if($request->section) {
                $section = ThematicSection::where('name', '=', $request->section)->first();

                if(!$section) {
                    $slugSection = $request->section ? $this->getSlug($request->section) : null;

                    $section = new ThematicSection([
                        'name' => $request->section,
                        'slug' => $slugSection,
                        'rating' => 0,
                        'parent_id' => null
                    ]);

                    $section->save();
                }
            }

            $news = new News([
                'title' => $request->title,
                'slug' => $slug,
                'slug_alternative' => $request->slug_alternative,
                'owner_id' => 1,
                'moder_id' => 1,
                'poster_id' => $request->image_id,
                'background_id' => $request->image_id,
                'description' => null,
                'text' => $request->text ?? '{}',
                'enabled' => 1,
                'section_id' => $section ? $section->id : null,
                'created_at' => $request->created_at
            ]);

            $news->save();
        }

        return response(200);
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
