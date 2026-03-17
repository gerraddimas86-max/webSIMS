<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class NewCommentNotification extends Notification
{
    protected $user;
    protected $comment;

    public function __construct($user, $comment)
    {
        $this->user = $user;
        $this->comment = $comment;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->user->name . ' mengomentari postinganmu',
            'comment' => $this->comment
        ];
    }
}