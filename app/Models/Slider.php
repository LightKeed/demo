<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $with = ['slides'];

    protected $fillable = [
        'name',
        'description',
        'enabled',
        'can_removed'
    ];

    protected static function booted()
    {
        static::deleting(function ($slider) {
            $slider->slides->each->delete();
        });
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['title'] ?? null, function ($query, $title) {
            $query->where('name', 'LIKE', "%$title%");
        })->when($filters['enabled'] ?? null, function ($query, $enabled) {
            $query->where('enabled', '=', $enabled);
        });
    }

    public function scopeIsEnabled($query) {
        return $query->where('enabled', true);
    }

    public function slides()
    {
        return $this->hasMany(Slide::class, 'slider_id', 'id');
    }
}
