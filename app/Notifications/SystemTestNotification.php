<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SystemTestNotification extends Notification
{
    use Queueable;

    private $message;

    public function __construct($message = 'This is a system test notification.')
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => $this->message,
            'type' => 'system',
            'action_url' => '#',
        ];
    }
}