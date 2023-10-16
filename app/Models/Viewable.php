<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Viewable extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'viewable_id',
        'viewable_type',
        'ip',
        'viewed_at'
    ];
}
