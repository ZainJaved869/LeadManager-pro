<?php

namespace App\Console\Commands;

use App\Models\Reminder;
use App\Notifications\ReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendReminders extends Command
{
    protected $signature = 'reminders:send';
    protected $description = 'Send scheduled reminders';

    public function handle()
    {
        $reminders = Reminder::where('is_sent', false)
            ->where('remind_at', '<=', now())
            ->get();

        foreach ($reminders as $reminder) {
            $this->sendNotification($reminder);
            $reminder->update([
                'is_sent' => true,
                'sent_at' => now(),
            ]);
        }

        Log::info('Reminders sent: ' . $reminders->count());
        $this->info('Sent ' . $reminders->count() . ' reminders.');
    }

    protected function sendNotification($reminder)
    {
        $user = $reminder->user;

        if (!$user) {
            return;
        }

        // In-app notification (always)
        $user->notify(new ReminderNotification($reminder));

        // Email (if type includes email)
        if ($reminder->type === 'email' || $reminder->type === 'both') {
            try {
                Mail::send('emails.reminder', ['reminder' => $reminder], function ($message) use ($user, $reminder) {
                    $message->to($user->email)
                        ->subject('Reminder: ' . $reminder->title);
                });
            } catch (\Exception $e) {
                Log::error('Reminder email failed: ' . $e->getMessage());
            }
        }
    }
}