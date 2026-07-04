<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'priority',
        'status',
        'sla_status',
        'assigned_to',
        'created_by',
        'client_id',
        'equipment_id',
        'due_date',
        'completed_at',
        'price'
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function gpsTrackings()
    {
        return $this->hasMany(GPSTracking::class);
    }

    /**
     * Update SLA status based on due date.
     */
    public function updateSlaStatus()
    {
        $now = now();
        $due = $this->due_date;

        if ($this->status === 'completed') {
            $this->sla_status = 'on_time';
        } elseif ($now > $due) {
            $this->sla_status = 'overdue';
        } elseif ($now->diffInDays($due) <= 3) {
            $this->sla_status = 'due_soon';
        } else {
            $this->sla_status = 'on_time';
        }

        $this->save();
        return $this;
    }
}
