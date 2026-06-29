<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    //
    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}
