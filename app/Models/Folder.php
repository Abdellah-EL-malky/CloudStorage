<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'parent_id',
        'is_favorite',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(Folder::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Folder::class, 'parent_id');
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function shares()
    {
        return $this->morphMany(Share::class, 'shareable');
    }

    // Récupération de 'arborescence des parents
    public function getPathAttribute()
    {
        $path = collect([]);
        $folder = $this;

        while ($folder->parent) {
            $path->push($folder->parent);
            $folder = $folder->parent;
        }

        return $path->reverse();
    }

    // Taille totale du dossier et de son contenu
    public function getTotalSize()
    {
        $size = $this->files->sum('size');

        foreach ($this->children as $child) {
            $size += $child->getTotalSize();
        }

        return $size;
    }
}
