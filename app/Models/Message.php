<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'is_read',
        'read_at'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    // Relasi ke pengirim
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Relasi ke penerima
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // Scope untuk pesan yang belum dibaca
    public function scopeUnread($query, $userId)
    {
        return $query->where('receiver_id', $userId)
                     ->where('is_read', false);
    }
}