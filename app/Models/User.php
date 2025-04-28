<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'storage_used',
        'storage_limit',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relations
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function folders()
    {
        return $this->hasMany(Folder::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function tags()
    {
        return $this->hasMany(Tag::class);
    }

    public function shares()
    {
        return $this->hasMany(Share::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Méthodes
    public function hasRole($roleName)
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function getRemainingStorage()
    {
        return $this->storage_limit - $this->storage_used;
    }

    public function getStoragePercentage()
    {
        if ($this->storage_limit === 0) return 100;
        return round(($this->storage_used / $this->storage_limit) * 100, 2);
    }
}
