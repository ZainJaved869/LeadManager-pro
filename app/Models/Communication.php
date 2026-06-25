<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Communication extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id', 'communicable_type', 'communicable_id', 'user_id',
        'type', 'subject', 'body', 'date', 'time', 'duration', 'direction', 'is_completed'
    ];

    protected $casts = [
        'date' => 'date',
        'is_completed' => 'boolean',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function communicable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeLabelAttribute()
    {
        return [
            'call' => '📞 Call',
            'email' => '✉️ Email',
            'meeting' => '🤝 Meeting',
            'note' => '📝 Note',
        ][$this->type] ?? $this->type;
    }

    public function getTypeColorAttribute()
    {
        return [
            'call' => 'bg-blue-100 text-blue-700',
            'email' => 'bg-indigo-100 text-indigo-700',
            'meeting' => 'bg-purple-100 text-purple-700',
            'note' => 'bg-yellow-100 text-yellow-700',
        ][$this->type] ?? 'bg-slate-100 text-slate-700';
    }
}