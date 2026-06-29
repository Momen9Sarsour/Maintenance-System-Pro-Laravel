<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Technician extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'specialization', 'status', 'latitude', 'longitude',
        'rating', 'tasks_completed', 'first_time_fix_rate',
        'on_time_rate', 'avg_repair_time'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class, 'assigned_to');
    }
}
