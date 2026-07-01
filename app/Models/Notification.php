<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',        // info, success, warning, error
        'title',
        'message',
        'is_read',
        'link',
        'icon',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    // العلاقة مع المستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // تحديد إذا كانت مقروءة
    public function isRead(): bool
    {
        return $this->is_read;
    }

    // تحديد إذا كانت غير مقروءة
    public function isUnread(): bool
    {
        return !$this->is_read;
    }

    // نطاق لجلب الإشعارات غير المقروءة
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    // نطاق لجلب الإشعارات المقروءة
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    // نطاق حسب النوع
    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    // نطاق حسب المستخدم
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Mark as read
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    // Mark as unread
    public function markAsUnread()
    {
        $this->update(['is_read' => false]);
    }

    // Mark all as read for a user
    public static function markAllAsRead($userId)
    {
        return static::forUser($userId)->unread()->update(['is_read' => true]);
    }

    // Get unread count for a user
    public static function unreadCount($userId)
    {
        return static::forUser($userId)->unread()->count();
    }
}
