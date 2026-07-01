<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GPSTracking extends Model
{
    use HasFactory;

    // تحديد اسم الجدول صراحةً
    protected $table = 'gps_trackings';

    protected $fillable = [
        'user_id',
        'work_order_id',
        'latitude',
        'longitude',
        'accuracy',
        'tracked_at',
    ];

    protected $casts = [
        'tracked_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    // العلاقات
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }
}
