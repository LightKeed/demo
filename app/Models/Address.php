<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_code',
        'district',
        'region',
        'city',
        'street',
        'house_number'
    ];

    protected static function booted()
    {
        static::deleting(function ($employee) {
            $employee->departments->each->update([
                'address_id' => null
            ]);;
        });
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['title'] ?? null, function ($query, $title) {
            $query->where('post_code', 'LIKE', "%$title%")
                ->orWhere('district', 'LIKE', "%$title%")
                ->orWhere('region', 'LIKE', "%$title%")
                ->orWhere('city', 'LIKE', "%$title%")
                ->orWhere('street', 'LIKE', "%$title%");
        });
    }

    protected function fullTitle(): Attribute
    {
        $result = [$this->post_code, $this->district, $this->region, $this->city, $this->street, $this->house_number];

        $result = implode(', ', array_filter($result));

        return Attribute::make(
            get: fn($value) => $result,
        );
    }

    protected function fullTitleCard(): Attribute
    {
        $result = [$this->district, $this->region, $this->city, $this->street, $this->house_number];

        $result = implode(', ', array_filter($result));

        return Attribute::make(
            get: fn($value) => $result,
        );
    }

    public function departments()
    {
        return $this->hasMany(Department::class, 'address_id', 'id');
    }
}
