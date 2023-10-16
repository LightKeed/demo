<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\ModelTrait;
use Illuminate\Support\Facades\Cache;

class Article extends Model
{
    use HasFactory, SoftDeletes, ModelTrait;

    protected $dates = ['deleted_at'];
    
    protected $fillable = [
        'title',
        'slug',
        'slug_alternative',
        'description',
        'text',
        'sort_order',
        'enabled',
        'enabled_menu',
        'category_id',
        'owner_id',
        'moder_id',
        'poster_id',
        'background_id',
        'slider_id',
    ];

    protected function route(): Attribute
    {
        return Attribute::make(
            get: function () {
                return Cache::remember('article_route_' . $this->id, now()->addHour(), function () {
                    $path = explode('/', $this->fullPath);

                    if(isset($path[4]) && $path[4]) return route('HomePage', ['path' => $path[1], 'subPath' => $path[2], 'subChild' => $path[3], 'page' => $path[4]]);
                    if(isset($path[3]) && $path[3]) return route('HomeSubChild', ['path' => $path[1], 'subPath' => $path[2], 'subChild' => $path[3]]);
                    if(isset($path[2]) && $path[2]) return route('HomeSubPath', ['path' => $path[1], 'subPath' => $path[2]]);
                    if(isset($path[1]) && $path[1]) return route('HomePath', ['path' => $path[1]]);

                    return '$path';
                });
            },
        )->shouldCache();
    }

    protected function fullPath(): Attribute
    {
        return Attribute::make(
            get: function () {
                return Cache::remember('article_full_path_' . $this->id, now()->addHour(), function () {
                    return $this->category->fullPath."/$this->slug";
                });
            },
        )->shouldCache();
    }

    public function scopeIsEnabled($query)
    {
        return $query->where('enabled', true);
    }

    public function scopeIsEnabledMenu($query)
    {
        return $query->where('enabled_menu', true);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['category'] ?? null, function ($query, $category) {
            $query->where('category_id', '=', $category === 'empty' ? null : $category);
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

    public function slider()
    {
        return $this->hasOne(Slider::class, 'id', 'slider_id');
    }

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
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
