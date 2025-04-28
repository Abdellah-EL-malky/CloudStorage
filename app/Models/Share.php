<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Share extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'token',
        'shareable_type',
        'shareable_id',
        'permission',
        'recipient_id',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function shareable()
    {
        return $this->morphTo();
    }

// Méthodes
    public function isExpired()
    {
        return $this->expires_at !== null && now()->gt($this->expires_at);
    }

    public function canRead()
    {
        return in_array($this->permission, ['read', 'write', 'admin']);
    }

    public function canWrite()
    {
        return in_array($this->permission, ['write', 'admin']);
    }

    public function canAdmin()
    {
        return $this->permission === 'admin';
    }
}
