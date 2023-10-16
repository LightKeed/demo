<?php

namespace App\Models\Traits;

use App\Models\Comment;

trait CommentTrait
{
    public function comments()
    {
        return $this->morphToMany(Comment::class, 'commentable');
    }
}
