<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'title'
    ];

    protected static function booted()
    {
        static::deleting(function ($employee) {
            $employee->departments->each->update([
                'type_id' => null
            ]);;
        });
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['title'] ?? null, function ($query, $title) {
            $query->where('title', 'LIKE', "%$title%");
        });
    }

    public function departments()
    {
        return $this->hasMany(Department::class, 'type_id', 'id');
    }
}
