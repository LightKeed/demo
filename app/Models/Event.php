<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\TagTrait;
use App\Models\Traits\ModelTrait;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory, SoftDeletes, TagTrait, ModelTrait;

    protected $dates = ['deleted_at'];

    protected $with = ['tags'];

    protected $appends = ['start_at'];

    protected $fillable = [
        'title',
        'slug',
        'slug_alternative',
        'description',
        'text',
        'views',
        'recording',
        'enabled',
        'event_start_at',
        'event_end_at',
        'record_start_at',
        'record_end_at',
        'owner_id',
        'moder_id',
        'poster_id',
        'background_id'
    ];

    protected function startAt(): Attribute
    {
        return Attribute::make(
            get: fn () => [
                'day' => Carbon::parse($this->event_start_at)->isoFormat('D'),
                'fullMonth' => Carbon::parse($this->event_start_at)->locale('ru_RU')->getTranslatedMonthName('Do MMMM')
            ]
        );
    }

    protected function route(): Attribute
    {
        return Attribute::make(
            get: fn() => route('EventIndex', ['path' => $this->slug])
        );
    }

    public function scopeIsEnabled($query) {
        return $query->where('enabled', true);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['tag'] ?? null, function ($query, $tag) {
            if($tag === 'empty') {
                $query->doesntHave('tags');
            } else {
                $query->whereHas('tags', function ($query) use($tag) {
                    $query->where('tag_id', '=', $tag);
                });
            }
        })->when($filters['title'] ?? null, function ($query, $title) {
            $query->where(function ($query) use ($title) {
                $query->where('title', 'LIKE', "%$title%")
                    ->orWhere('slug', 'LIKE', "%$title%")
                    ->orWhere('slug_alternative', 'LIKE', "%$title%")
                    ->orWhere('description', 'LIKE', "%$title%")
                    ->orWhere('text', 'LIKE', "%$title%");
            });
        })->when($filters['archive'] ?? null, function ($query, $archive) {
            $query->onlyTrashed();
        });
    }

    public function poster()
    {
        return $this->hasOne(Media::class, 'id', 'poster_id');
    }

    public function background()
    {
        return $this->hasOne(Media::class, 'id', 'background_id');
    }

    public function owner()
    {
        return $this->hasOne(User::class, 'id', 'owner_id');
    }

    public function moder()
    {
        return $this->hasOne(User::class, 'id', 'moder_id');
    }
}
