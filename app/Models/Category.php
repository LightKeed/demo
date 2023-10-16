<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'slug',
        'slug_alternative',
        'sort_order',
        'enabled',
        'parent_id',
        'background_id',
    ];

    protected static function booted()
    {
        static::deleting(function ($category) {
            if ($category->isForceDeleting()) {
                $category->categories()->forceDelete();
            } else {
                $category->articles->each->update([
                    'enabled' => 0,
                    'category_id' => null
                ]);
                $category->categories->each->delete();
            }
        });
    }

    public function scopeIsEnabled($query) {
        return $query->where('enabled', true);
    }

    public function scopeOfSort($query, $sort) {
        foreach ($sort as $column => $direction) {
            $query->orderBy($column, $direction);
        }

        return $query;
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['title'] ?? null, function ($query, $title) {
            $query->where(function ($query) use ($title) {
                $query->where('name', 'LIKE', "%$title%")
                    ->orWhere('slug', 'LIKE', "%$title%")
                    ->orWhere('slug_alternative', 'LIKE', "%$title%");
            });
        })->when($filters['archive'] ?? null, function ($query, $archive) {
            $query->onlyTrashed();
        });
    }

    protected function route(): Attribute
    {
        return Attribute::make(
            get: function () {
                return Cache::remember('category_route_' . $this->id, now()->addHour(), function () {
                    $path = explode('/', $this->fullPath);

                    if(isset($path[3]) && $path[3]) return route('HomeSubChild', ['path' => $path[1], 'subPath' => $path[2], 'subChild' => $path[3]]);
                    if(isset($path[2]) && $path[2]) return route('HomeSubPath', ['path' => $path[1], 'subPath' => $path[2]]);
                    if(isset($path[1]) && $path[1]) return route('HomePath', ['path' => $path[1]]);

                    return '/';
                });
            },
        )->shouldCache();
    }

    protected function fullPath(): Attribute
    {
        return Attribute::make(
            get: function () {
                return Cache::remember('category_full_path_' . $this->id, now()->addHour(), function () {
                    return $this->getFullPath();
                });
            },
        )->shouldCache();
    }

    protected function getFullPath()
    {
        return $this->getCategoryPath($this->parent, '/'.$this->slug);
    }

    protected function getCategoryPath($category, $path)
    {
        if(!$category) return "$path";

        $path = "/$category->slug$path";

        return !$category->parent ? $path : $this->getCategoryPath($category->parent()->select('id', 'name', 'parent_id', 'slug')->first(), $path);
    }

    public function parent()
    {
        return $this->hasOne(Category::class, 'id', 'parent_id')->with('parent');;
    }

    public function childrenCategories()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id')
            ->orderBy('sort_order');
    }

    public function articles()
    {
        return $this->hasMany(Article::class, 'category_id', 'id');
    }
}
