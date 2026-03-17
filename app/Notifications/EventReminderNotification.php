<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class EventReminderNotification extends Notification
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
            'message' => 'Event ' . $this->event->title . ' akan segera dimulai'
        ];
    }
}