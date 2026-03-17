<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class NewLikeNotification extends Notification
{
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->user->name . ' menyukai postinganmu'
        ];
    }
}