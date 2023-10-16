<?php

namespace App\Models\Traits;

use App\Models\Viewable;

trait ViewTrait
{
    public function viewed()
    {
        return $this->morphMany(Viewable::class, 'viewable');
    }
}
