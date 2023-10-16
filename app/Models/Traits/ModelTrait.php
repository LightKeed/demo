<?php

namespace App\Models\Traits;

use App\Models\User;

trait ModelTrait
{
    public function userRights()
    {
        return $this->morphToMany(User::class, 'model', 'user_has_models');
    }
}
