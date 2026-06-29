<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'frequency',
        'interval_value',
        'start_date',
        'next_due_date',
        'last_completed_date',
        'status',
        'equipment_id'
    ];

    protected $casts = [
        'start_date' => 'date',
        'next_due_date' => 'date',
        'last_completed_date' => 'date',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }
}
