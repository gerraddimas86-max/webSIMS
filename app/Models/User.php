<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

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

    public function posts()
{
    return $this->hasMany(Post::class);
}

public function comments()
{
    return $this->hasMany(Comment::class);
}

public function likes()
{
    return $this->hasMany(Like::class);
}

public function eventRegistrations()
{
    return $this->hasMany(EventRegistration::class);
}

public function badges()
{
    return $this->belongsToMany(Badge::class, 'user_badges');
}

public function sentConnections()
{
    return $this->hasMany(Connection::class, 'requester_id');
}

public function receivedConnections()
{
    return $this->hasMany(Connection::class, 'receiver_id');
}

public function connections()
{
    return Connection::where(function ($query) {
        $query->where('requester_id', $this->id)
              ->orWhere('receiver_id', $this->id);
    })->where('status', 'accepted');
}

}
