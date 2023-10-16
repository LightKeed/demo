<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    use HasFactory;

    protected $with = ['media'];

    protected $fillable = [
        'title',
        'description',
        'text_button',
        'link_button',
        'sort_order',
        'enabled',
        'media_id',
        'slider_id'
    ];

    public function scopeIsEnabled($query) {
        return $query->where('enabled', true);
    }

    public function media()
    {
        return $this->hasOne(Media::class, 'id', 'media_id');
    }

    public function slider()
    {
        return $this->hasOne(Slider::class, 'id', 'slider_id');
    }
}
