<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'model',
        'serial_number',
        'manufacturer',
        'location',
        'building',
        'floor',
        'status',
        'installation_date',
        'warranty_expiry',
        'description',
        'assigned_technician_id'
    ];

    protected $casts = [
        'installation_date' => 'date',
        'warranty_expiry' => 'date',
    ];

    public function assignedTechnician()
    {
        return $this->belongsTo(User::class, 'assigned_technician_id');
    }

    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }

    public function maintenanceSchedules()
    {
        return $this->hasMany(MaintenanceSchedule::class);
    }

    public function spareParts()
    {
        return $this->hasMany(SparePart::class);
    }
}
