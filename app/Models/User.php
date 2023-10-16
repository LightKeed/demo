<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'surname',
        'name',
        'patronymic',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::deleting(function ($user) {
            if ($user->isForceDeleting()) {
                $admin_id = auth()->user()->id;

                $user->articleRights()->detach();
                $user->newsRights()->detach();
                $user->eventRights()->detach();

                foreach($user->media as $media) {
                    $media->update(['owner_id' => $admin_id]);
                }

                foreach($user->articles as $article) {
                    $article->update(['owner_id' => $admin_id]);
                }

                foreach($user->news as $news) {
                    $news->update(['owner_id' => $admin_id]);
                }

                foreach($user->events as $event) {
                    $event->update(['owner_id' => $admin_id]);
                }
            }
        });
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['name'] ?? null, function ($query, $name) {
            $query->where(function ($query) use ($name) {
                $query->where('surname', 'LIKE', "%$name%")
                    ->orWhere('name', 'LIKE', "%$name%")
                    ->orWhere('patronymic', 'LIKE', "%$name%");
            });
        })->when($filters['email'] ?? null, function ($query, $email) {
            $query->where('email', 'LIKE', "%$email%");
        })->when($filters['archive'] ?? null, function ($query, $archive) {
            $query->onlyTrashed();
        });
    }

    protected function fio(): Attribute
    {
        $result = implode(', ', array_filter([$this->surname, $this->name, $this->patronymic]));

        return Attribute::make(
            get: fn($value) => $result,
        );
    }

    public function articleRights()
    {
        return $this->morphedByMany(Article::class, 'model', 'user_has_models')->orderBy('title');
    }

    public function newsRights()
    {
        return $this->morphedByMany(News::class, 'model', 'user_has_models')->orderBy('title');
    }

    public function eventRights()
    {
        return $this->morphedByMany(Event::class, 'model', 'user_has_models')->orderBy('title');
    }

    public function media()
    {
        return $this->hasMany(Media::class, 'owner_id', 'id');
    }

    public function articles()
    {
        return $this->hasMany(Article::class, 'owner_id', 'id');
    }

    public function news()
    {
        return $this->hasMany(News::class, 'owner_id', 'id');
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'owner_id', 'id');
    }
}
