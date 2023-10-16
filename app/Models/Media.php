<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class Media extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'fullname',
        'path',
        'description',
        'type',
        'extension',
        'size',
        'owner_id',
        'moder_id'
    ];

    protected static function booted()
    {
        static::deleting(function ($media) {
            if ($media->isForceDeleting()) {
                if(Storage::disk('local')->get($media->path)) {
                    Storage::delete($media->path);
                }

                $media->articlesPoster->each->update(['poster_id' => null]);
                $media->articlesBackground->each->update(['background_id' => null]);
                $media->newsPoster->each->update(['poster_id' => null]);
                $media->newsBackground->each->update(['background_id' => null]);
                $media->eventsPoster->each->update(['poster_id' => null]);
                $media->eventsBackground->each->update(['background_id' => null]);
                $media->employees->each->update(['photo_id' => null]);
                $media->departments->each->update(['photo_id' => null]);
                $media->slides->each->update(['media_id' => null]);
            }
        });
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['my'] ?? null, function ($query) {
            $query->where('owner_id', '=', Auth::id());
        })->when($filters['created'] ?? null, function ($query, $created) {
            $query->whereDate('created_at', '=', $created);
        })->when($filters['type'] ?? null, function ($query, $type) {
            if($type === 'empty') {
                $query->whereNull('type');
            } else {
                $query->whereIn('type', is_array($type) ? $type : [$type]);
            }
        })->when($filters['title'] ?? null, function ($query, $title) {
            $query->where(function ($query) use ($title) {
                $query->where('fullname', 'LIKE', "%$title%")
                    ->orWhere('path', 'LIKE', "%$title%")
                    ->orWhere('description', 'LIKE', "%$title%");
            });
        })->when($filters['archive'] ?? null, function ($query) {
            $query->onlyTrashed();
        });
    }

    public function owner()
    {
        return $this->hasOne(User::class, 'id', 'owner_id');
    }

    public function moder()
    {
        return $this->hasOne(User::class, 'id', 'moder_id');
    }

    public function articlesPoster()
    {
        return $this->hasMany(Article::class, 'poster_id', 'id');
    }

    public function articlesBackground()
    {
        return $this->hasMany(Article::class, 'background_id', 'id');
    }

    public function newsPoster()
    {
        return $this->hasMany(News::class, 'poster_id', 'id');
    }

    public function newsBackground()
    {
        return $this->hasMany(News::class, 'background_id', 'id');
    }

    public function eventsPoster()
    {
        return $this->hasMany(Event::class, 'poster_id', 'id');
    }

    public function eventsBackground()
    {
        return $this->hasMany(Event::class, 'background_id', 'id');
    }

    public function departments()
    {
        return $this->hasMany(Department::class, 'photo_id', 'id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'photo_id', 'id');
    }

    public function slides()
    {
        return $this->hasMany(Slide::class, 'media_id', 'id');
    }
}
