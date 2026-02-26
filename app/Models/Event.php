<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['name', 'description', 'event_date'];

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }
}