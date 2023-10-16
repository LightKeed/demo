<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentPosition extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'employee_id',
        'address_id',
        'subdivision',
        'cabinet',
        'title',
        'experience',
        'status'
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['fio'] ?? null, function ($query, $fio) {
            $query->whereHas('employee', function($query) use ($fio) {
                $query->where('surname', 'LIKE', "%$fio%")
                    ->orWhere('name', 'LIKE', "%$fio%")
                    ->orWhere('patronymic', 'LIKE', "%$fio%");
            });
        })->when($filters['department'] ?? null, function ($query, $department) {
            $query->whereHas('department', function($query) use ($department) {
                $query->where('title', 'LIKE', "%$department%");
            });
        });
    }

    public function department()
    {
        return $this->hasOne(Department::class, 'id', 'department_id');
    }

    public function employee()
    {
        return $this->hasOne(Employee::class, 'id', 'employee_id');
    }

    public function address()
    {
        return $this->hasOne(Address::class, 'id', 'address_id');
    }
}
