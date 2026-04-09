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
        'angkatan',
        'prodi',
        'bio',
        'role',
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

    // ==================== POSTS & COMMENTS ====================
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

    // ==================== EVENTS ====================
    public function eventRegistrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function registeredEvents()
    {
        return $this->belongsToMany(Event::class, 'event_registrations', 'user_id', 'event_id')
                    ->withPivot('status', 'registration_date')
                    ->withTimestamps();
    }

    // ==================== BADGES ====================
    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges');
    }

    // ==================== CONNECTIONS ====================
    public function sentConnections()
    {
        return $this->hasMany(Connection::class, 'requester_id');
    }

    public function receivedConnections()
    {
        return $this->hasMany(Connection::class, 'receiver_id');
    }

    /**
     * Get all connected users (accepted connections)
     * Tanpa UNION untuk menghindari error duplicate column id
     */
    public function connections()
    {
        $userId = $this->id;
        
        // Ambil ID user yang saya kirim request (saya sebagai requester)
        $sentIds = Connection::where('requester_id', $userId)
            ->where('status', 'accepted')
            ->pluck('receiver_id');
        
        // Ambil ID user yang mengirim request ke saya (saya sebagai receiver)
        $receivedIds = Connection::where('receiver_id', $userId)
            ->where('status', 'accepted')
            ->pluck('requester_id');
        
        // Gabung dan hapus duplikat
        $allIds = $sentIds->merge($receivedIds)->unique();
        
        return User::whereIn('id', $allIds);
    }

    /**
     * Count connections (accepted only)
     * Lebih efisien daripada memanggil connections()->count()
     */
    public function getConnectionsCountAttribute()
    {
        return Connection::where(function ($query) {
                $query->where('requester_id', $this->id)
                      ->orWhere('receiver_id', $this->id);
            })
            ->where('status', 'accepted')
            ->count();
    }

    /**
     * Get pending connection requests received
     */
    public function pendingRequests()
    {
        return $this->hasMany(Connection::class, 'receiver_id')
                    ->where('status', 'pending')
                    ->with('requester');
    }

    /**
     * Get pending connection requests sent
     */
    public function pendingSentRequests()
    {
        return $this->hasMany(Connection::class, 'requester_id')
                    ->where('status', 'pending')
                    ->with('receiver');
    }

    // ==================== MESSAGES ====================
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /**
     * Get unread messages count
     */
    public function getUnreadMessagesCountAttribute()
    {
        return $this->receivedMessages()->where('is_read', false)->count();
    }

    // ==================== HELPERS ====================
    
    /**
     * Check if user is connected with another user
     */
    public function isConnectedWith($userId)
    {
        return Connection::where(function ($query) use ($userId) {
                $query->where('requester_id', $this->id)
                      ->where('receiver_id', $userId);
            })
            ->orWhere(function ($query) use ($userId) {
                $query->where('requester_id', $userId)
                      ->where('receiver_id', $this->id);
            })
            ->where('status', 'accepted')
            ->exists();
    }

    /**
     * Check if connection request is pending from a user
     */
    public function hasPendingRequestFrom($userId)
    {
        return Connection::where('requester_id', $userId)
            ->where('receiver_id', $this->id)
            ->where('status', 'pending')
            ->exists();
    }

    /**
     * Check if connection request is pending to a user
     */
    public function hasPendingRequestTo($userId)
    {
        return Connection::where('requester_id', $this->id)
            ->where('receiver_id', $userId)
            ->where('status', 'pending')
            ->exists();
    }

    /**
     * Get connection status with another user
     */
    public function getConnectionStatusWith($userId)
    {
        $connection = Connection::where(function ($query) use ($userId) {
                $query->where('requester_id', $this->id)
                      ->where('receiver_id', $userId);
            })
            ->orWhere(function ($query) use ($userId) {
                $query->where('requester_id', $userId)
                      ->where('receiver_id', $this->id);
            })
            ->first();
        
        if (!$connection) {
            return 'not_connected';
        }
        
        return $connection->status;
    }

    /**
     * Get user role (admin or user)
     */
    public function getRoleAttribute()
    {
        return $this->attributes['role'] ?? 'user';
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return ($this->attributes['role'] ?? 'user') === 'admin';
    }
}