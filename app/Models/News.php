<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\TagTrait;
use App\Models\Traits\ModelTrait;
use App\Models\Traits\CommentTrait;
use App\Models\Traits\ViewTrait;

class News extends Model
{
    use HasFactory, SoftDeletes, TagTrait, ModelTrait, CommentTrait, ViewTrait;

    protected $dates = ['deleted_at'];

    protected $with = ['tags'];

    protected $fillable = [
        'title',
        'slug',
        'slug_alternative',
        'description',
        'text',
        'views',
        'rating',
        'enabled',
        'owner_id',
        'moder_id',
        'poster_id',
        'background_id',
        'section_id',
        'created_at'
    ];

    protected static function booted()
    {
        static::deleting(function ($news) {
            if ($news->isForceDeleting()) {
                $news->userRights()->detach();
                $news->tags()->detach();
                $news->comments()->detach();
            }
        });
    }

    protected function route(): Attribute
    {
        return Attribute::make(
            get: fn() => route('NewsIndex', ['path' => $this->slug])
        );
    }

    public function scopeIsEnabled($query) {
        return $query->where('enabled', true);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['date'] ?? null, function ($query, $date) {
            $query->whereDate('created_at', '=', $date);
        })->when($filters['section'] ?? null, function ($query, $section) {
            $query->where('section_id', '=', $section === 'empty' ? null : $section);
        })->when($filters['tag'] ?? null, function ($query, $tag) {
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

    public function section()
    {
        return $this->hasOne(ThematicSection::class, 'id', 'section_id');
    }
}
