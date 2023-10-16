<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Commentable extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'comment_id',
        'commentable_id',
        'commentable_type'
    ];

    protected function commentableType(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if($value === 'App\Models\News') $value = 'Новость';
                if($value === 'App\Models\Event') $value = 'Мероприятие';

                return $value;
            }
        );
    }
}
