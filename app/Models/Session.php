<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    public $timestamps = null;

    protected $fillable = [
        'id_sso'
    ];
}
