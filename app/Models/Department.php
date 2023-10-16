<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'title_short',
        'email',
        'phone',
        'cabinet',
        'department_id',
        'photo_id',
        'address_id',
        'type_id'
    ];

    protected static function booted()
    {
        static::deleting(function ($employee) {
            $employee->positions->each->delete();
            $employee->child->each->update([
                'department_id' => null
            ]);
        });
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['title'] ?? null, function ($query, $title) {
            $query->where(function ($query) use ($title) {
                $query->where('title', 'LIKE', "%$title%")
                    ->orWhere('title_short', 'LIKE', "%$title%")
                    ->orWhere('email', 'LIKE', "%$title%")
                    ->orWhere('phone', 'LIKE', "%$title%");
            });
        })->when($filters['type'] ?? null, function ($query, $type) {
            if($type === 'empty') {
                $query->doesntHave('type');
            } else {
                $query->whereHas('type', function($query) use ($type) {
                    $query->where('id', '=', $type);
                });
            }
        });
    }

    public function positions()
    {
        return $this->hasMany(DepartmentPosition::class, 'department_id', 'id');
    }

    public function parent()
    {
        return $this->hasOne(Department::class, 'id', 'department_id');
    }

    public function child()
    {
        return $this->hasMany(Department::class, 'department_id', 'id');
    }

    public function type()
    {
        return $this->hasOne(DepartmentType::class, 'id', 'type_id');
    }

    public function address()
    {
        return $this->hasOne(Address::class, 'id', 'address_id');
    }
}
