<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientRequest extends Model
{
    //
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function assignedTechnician()
    {
        return $this->belongsTo(User::class, 'assigned_technician_id');
    }

    public function workOrder()
    {
        return $this->hasOne(WorkOrder::class);
    }
}
