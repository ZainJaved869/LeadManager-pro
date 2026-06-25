<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id', 'lead_id', 'filename', 'original_name',
        'mime_type', 'size', 'path', 'uploaded_by'
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}