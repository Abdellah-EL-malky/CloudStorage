<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $guarded = [];

    protected $casts = [
        'type' => 'string',
    ];

    //Relations
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function userPlans()
    {
        return $this->hasMany(UserPlan::class);
    }

    //Méthodes
    public function getFormattedStorageAttribute()
    {
        return $this->formatBytes($this->storage);
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
