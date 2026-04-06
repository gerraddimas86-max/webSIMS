<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'event_date',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'event_registrations')
                    ->withPivot('attended')
                    ->withTimestamps();
    }
}