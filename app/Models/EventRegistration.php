<?php
// app/Models/EventRegistration.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    protected $fillable = [
        'user_id', 
        'event_id', 
        'full_name',    // <-- TAMBAHKAN
        'email',        // <-- TAMBAHKAN
        'phone',        // <-- TAMBAHKAN
        'faculty',      // <-- TAMBAHKAN
        'attended',
        'attended_at',
        'status',
        'registration_date'
    ];

    protected $casts = [
        'attended' => 'boolean',
        'registration_date' => 'datetime',
        'attended_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}