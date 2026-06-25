<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id', 'name', 'email', 'phone', 'company', 'source',
        'stage_id', 'assigned_to', 'value', 'contacted_at', 'won_at',
        'lost_reason', 'custom_fields', 'created_by'
    ];

    protected $casts = [
        'custom_fields' => 'array',
        'value' => 'decimal:2',
        'contacted_at' => 'date',
        'won_at' => 'date',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function stage()
    {
        return $this->belongsTo(PipelineStage::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function notes()
    {
        return $this->hasMany(LeadNote::class);
    }

    public function files()
    {
        return $this->hasMany(LeadFile::class);
    }
    public function communications()
{
    return $this->morphMany(Communication::class, 'communicable');
}
}