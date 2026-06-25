<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id', 'subscription_id', 'invoice_number', 'amount',
        'currency', 'status', 'due_date', 'paid_at', 'gateway',
        'gateway_id', 'items', 'notes'
    ];

    protected $casts = [
        'items' => 'array',
        'due_date' => 'datetime',
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function getStatusLabelAttribute()
    {
        return [
            'pending' => 'Pending',
            'paid' => 'Paid',
            'failed' => 'Failed',
            'refunded' => 'Refunded',
        ][$this->status] ?? $this->status;
    }
}