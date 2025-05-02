<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'path',
        'mime_type',
        'size',
        'user_id',
        'folder_id',
        'is_favorite',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function shares()
    {
        return $this->morphMany(Share::class, 'shareable');
    }

    // Méthodes
    public function getFormattedSizeAttribute()
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $size = $this->size;
        $i = 0;

        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }

        return round($size, 2) . ' ' . $units[$i];
    }

    public function isImage()
    {
        return strpos($this->mime_type, 'image/') === 0;
    }

    public function isPdf()
    {
        return $this->mime_type === 'application/pdf';
    }

    public function isVideo()
    {
        return strpos($this->mime_type, 'video/') === 0;
    }

    public function isAudio()
    {
        return strpos($this->mime_type, 'audio/') === 0;
    }

    public function isDocument()
    {
        $documentTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'text/plain'
        ];

        return in_array($this->mime_type, $documentTypes);
    }

    public function getIconClass()
    {
        if ($this->isImage()) return 'bi-file-image';
        if ($this->isPdf()) return 'bi-file-pdf';
        if ($this->isVideo()) return 'bi-file-play';
        if ($this->isAudio()) return 'bi-file-music';
        if ($this->isDocument()) return 'bi-file-text';

        return 'bi-file';
    }

    public function getIconColor()
    {
        if ($this->isImage()) return 'text-primary';
        if ($this->isPdf()) return 'text-danger';
        if ($this->isVideo()) return 'text-success';
        if ($this->isAudio()) return 'text-warning';
        if ($this->isDocument()) return 'text-info';

        return 'text-secondary';
    }
}
