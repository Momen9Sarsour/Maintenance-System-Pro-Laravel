<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\ClientRequest;
use App\Models\Equipment;
use App\Models\GPSTracking;
use App\Models\Invoice;
use App\Models\PerformanceAnalytic;
use App\Models\Technician;
use App\Models\WorkOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'company_name',
        'is_active',
        'profile_photo'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

   // Role checks
    public function isAdmin() { return $this->role === 'admin'; }
    public function isManager() { return $this->role === 'manager'; }
    public function isTechnician() { return $this->role === 'technician'; }
    public function isClient() { return $this->role === 'client'; }
    public function isDataEntry() { return $this->role === 'data_entry'; }

    // Relationships
    public function technicianProfile()
    {
        return $this->hasOne(Technician::class);
    }

    public function assignedWorkOrders()
    {
        return $this->hasMany(WorkOrder::class, 'assigned_to');
    }

    public function createdWorkOrders()
    {
        return $this->hasMany(WorkOrder::class, 'created_by');
    }

    public function clientWorkOrders()
    {
        return $this->hasMany(WorkOrder::class, 'client_id');
    }

    public function clientRequests()
    {
        return $this->hasMany(ClientRequest::class, 'client_id');
    }

    public function assignedRequests()
    {
        return $this->hasMany(ClientRequest::class, 'assigned_technician_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'client_id');
    }

    public function gpsTrackings()
    {
        return $this->hasMany(GPSTracking::class);
    }

    public function performanceAnalytics()
    {
        return $this->hasMany(PerformanceAnalytic::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function assignedEquipment()
    {
        return $this->hasMany(Equipment::class, 'assigned_technician_id');
    }
}
