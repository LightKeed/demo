<?php

namespace App\Models\Traits;

use App\Models\Tag;

trait TagTrait
{
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
