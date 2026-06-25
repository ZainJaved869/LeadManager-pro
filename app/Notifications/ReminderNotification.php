<?php

namespace App\Notifications;

use App\Models\Reminder;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $reminder;

    public function __construct(Reminder $reminder)
    {
        $this->reminder = $reminder;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->reminder->title,
            'description' => $this->reminder->description,
            'remindable_type' => $this->reminder->remindable_type,
            'remindable_id' => $this->reminder->remindable_id,
            'url' => $this->reminder->remindable ? url('/' . strtolower(class_basename($this->reminder->remindable)) . '/' . $this->reminder->remindable_id) : null,
            'type' => 'reminder',
        ];
    }
}