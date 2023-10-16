<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Employee extends Model
{
    use HasFactory;

    protected $with = ['positions'];

    protected $appends = ['phone', 'email'];

    protected $fillable = [
        'surname',
        'name',
        'patronymic',
        'level_education',
        'general_experience',
        'scientific_experience',
        'photo_id'
    ];

    protected static function booted()
    {
        static::deleting(function ($employee) {
            $employee->positions->each->delete();
            $employee->attributes->each->delete();
        });
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['title'] ?? null, function ($query, $title) {
            $query->where(function ($query) use ($title) {
                $query->where('surname', 'LIKE', "%$title%")
                    ->orWhere('name', 'LIKE', "%$title%")
                    ->orWhere('patronymic', 'LIKE', "%$title%");
            });
        })->when($filters['department'] ?? null, function ($query, $department) {
            if($department === 'empty') {
                $query->doesntHave('positions');
            } else {
                $query->whereHas('positions', function($query) use ($department) {
                    $query->where('id', '=', $department);
                });
            }
        });
    }

    protected function fio(): Attribute
    {
        $result = implode(', ', array_filter([$this->surname, $this->name, $this->patronymic]));

        return Attribute::make(
            get: fn($value) => $result,
        );
    }

    protected function phone(): Attribute
    {
        $result = $this->attributes()->filter(['title' => 'phone'])->first()->value ?? '';

        return Attribute::make(
            get: fn($value) => $result,
        );
    }

    protected function email(): Attribute
    {
        $result = $this->attributes()->filter(['title' => 'email'])->first()->value ?? '';

        return Attribute::make(
            get: fn($value) => $result,
        );
    }

    public function positions()
    {
        return $this->hasMany(DepartmentPosition::class, 'employee_id', 'id');
    }

    public function attributes()
    {
        return $this->hasMany(EmployeeAttribute::class, 'employee_id', 'id');
    }
}
