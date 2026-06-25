<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
// Remove this: use Illuminate\Contracts\Queue\ShouldQueue;

class ActivityNotification extends Notification //, ShouldQueue
{
    // use Queueable; // Remove this too

    protected $title;
    protected $description;
    protected $url;

    public function __construct($title, $description = null, $url = null)
    {
        $this->title = $title;
        $this->description = $description;
        $this->url = $url;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'url' => $this->url,
            'type' => 'activity',
        ];
    }
}