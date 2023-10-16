<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'status',
        'name',
        'email',
        'phone',
        'signature',
        'other',
        'text',
        'parent_id'
    ];

    protected static function booted()
    {
        static::deleting(function ($comment) {
            if ($comment->isForceDeleting()) {
                $comment->element()->delete();
            }
        });
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['scope'] ?? null, function ($query, $scope) {
            $query->where('scope', '=', $scope === 'empty' ? null : $scope);
        })->when($filters['archive'] ?? null, function ($query, $archive) {
            $query->onlyTrashed();
        })->when($filters['archive'] ?? null, function ($query, $archive) {
            $query->onlyTrashed();
        });
    }

    public function element()
    {
        return $this->hasOne(Commentable::class, 'comment_id', 'id');
    }

    public function parent()
    {
        return $this->hasOne(Comment::class, 'id', 'parent_id');
    }
}
