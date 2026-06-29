<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SparePart extends Model
{
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
