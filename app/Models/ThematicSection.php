<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ThematicSection extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'slug',
        'rating',
        'parent_id'
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['title'] ?? null, function ($query, $title) {
            $query->where(function ($query) use ($title) {
                $query->where('name', 'LIKE', "%$title%")
                    ->orWhere('slug', 'LIKE', "%$title%");
            });
        })->when($filters['parent'] ?? null, function ($query, $parent) {
            $query->where('parent_id', '=', $parent === 'empty' ? null : $parent);
        })->when($filters['archive'] ?? null, function ($query, $archive) {
            $query->onlyTrashed();
        });
    }

    public function parent()
    {
        return $this->hasOne(ThematicSection::class, 'id', 'parent_id');
    }

    public function childs()
    {
        return $this->hasMany(ThematicSection::class, 'parent_id', 'id');
    }

    public function news()
    {
        return $this->hasMany(News::class, 'section_id', 'id');
    }
}
