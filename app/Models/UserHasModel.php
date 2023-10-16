<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserHasModel extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
