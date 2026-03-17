<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class EventRegistrationNotification extends Notification
{
    protected $event;

    public function __construct($event)
    {
        $this->event = $event;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Kamu berhasil mendaftar event ' . $this->event->title
        ];
    }
}