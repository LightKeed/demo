<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Log extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $dates = ['created_at'];

    protected $fillable = [
        'action',
        'method',
        'component',
        'object_id',
        'note',
        'target',
        'target_id',
        'agent',
        'url',
        'origin',
        'ip',
        'created_at'
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['date'] ?? null, function ($query, $date) {
            $query->whereDate('created_at', '=', $date);
        })->when($filters['method'] ?? null, function ($query, $method) {
            $query->where('method', '=', $method);
        })->when($filters['action'] ?? null, function ($query, $action) {
            $query->where('action', '=', $action);
        })->when($filters['component'] ?? null, function ($query, $component) {
            $query->where('component', '=', $component);
        })->when($filters['user'] ?? null, function ($query, $user) {
            $query->where('target_id', '=', $user);
        })->when($filters['ip'] ?? null, function ($query, $ip) {
            $query->where('ip', '=', $ip);
        });
    }

    protected function action(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if($value === 'viewed') $value = 'Просмотрено';
                if($value === 'create') $value = 'Создание';
                if($value === 'update') $value = 'Обновление';
                if($value === 'delete') $value = 'Удаление';
                if($value === 'force_delete') $value = 'Полное удаление';
                if($value === 'restore') $value = 'Восстановление';
                if($value === 'hidden') $value = 'Скрытие';
                if($value === 'displayed') $value = 'Отображение';
                if($value === 'attach') $value = 'Прикреплено';
                if($value === 'detach') $value = 'Откреплено';

                return $value;
            }
        );
    }
}
