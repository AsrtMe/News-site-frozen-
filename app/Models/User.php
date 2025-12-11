<?php

namespace App\Models;

<<<<<<< HEAD
// use Illuminate\Contracts\Auth\MustVerifyEmail;
=======
>>>>>>> dde27edcd07f2c1a2e776ab8904912a6d07c7709
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
<<<<<<< HEAD
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
=======
>>>>>>> dde27edcd07f2c1a2e776ab8904912a6d07c7709

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

<<<<<<< HEAD
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

=======
>>>>>>> dde27edcd07f2c1a2e776ab8904912a6d07c7709
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
<<<<<<< HEAD

    /**
     * Получить или создать гостевого пользователя (используется для анонимных комментариев)
     *
     * @return self
     */
    public static function guest(): self
    {
        return static::firstOrCreate(
            ['email' => 'guest@forum.local'],
            [
                'name' => 'Гость',
                'password' => Hash::make(Str::random(40)),
            ]
        );
    }
}
=======
}
>>>>>>> dde27edcd07f2c1a2e776ab8904912a6d07c7709
