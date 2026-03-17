<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class ConnectionRequestNotification extends Notification
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
            'message' => $this->user->name . ' mengirim permintaan koneksi'
        ];
    }
}