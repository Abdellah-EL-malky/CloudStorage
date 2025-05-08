<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{

    public $guarded = [];

    //Relation
    public function plans()
    {
        return $this->belongsTo(Plan::class);
    }
}
