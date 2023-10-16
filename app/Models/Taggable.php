<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Taggable extends Model
{
    public $timestamps = false;

    protected $with = ['data'];

    protected $fillable = [
        'tag_id'
    ];

    public function data()
    {
        return $this->hasOne(Tag::class, 'id', 'tag_id');
    }
}
