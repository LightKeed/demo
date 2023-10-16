<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];
    
    protected $fillable = [
        'name',
        'slug',
        'rating',
        'scope'
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['title'] ?? null, function ($query, $title) {
            $query->where(function ($query) use ($title) {
                $query->where('name', 'LIKE', "%$title%")
                    ->orWhere('slug', 'LIKE', "%$title%");
            });
        })->when($filters['scope'] ?? null, function ($query, $scope) {
            $query->where('scope', '=', $scope === 'empty' ? null : $scope);
        })->when($filters['archive'] ?? null, function ($query, $archive) {
            $query->onlyTrashed();
        });
    }

    public function elements()
    {
        return $this->hasMany(Taggable::class, 'tag_id', 'id');
    }

    public function news()
    {
        return $this->morphedByMany(News::class, 'taggable');
    }

    public function events()
    {
        return $this->morphedByMany(Event::class, 'taggable');
    }
}
