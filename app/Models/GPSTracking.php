<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GPSTracking extends Model
{
    //
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }
}
