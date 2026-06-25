<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id', 'user_id', 'remindable_type', 'remindable_id',
        'title', 'description', 'remind_at', 'sent_at', 'type', 'is_sent'
    ];

    protected $casts = [
        'remind_at' => 'datetime',
        'sent_at' => 'datetime',
        'is_sent' => 'boolean',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function reminders()
{
    return $this->morphMany(Reminder::class, 'remindable');
}

    public function remindable()
    {
        return $this->morphTo();
    }
}