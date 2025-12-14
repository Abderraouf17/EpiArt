<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'delivery_type',
        'wilaya',
        'address',
        'total_price',
        'delivery_fee',
        'status',
        'notes',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    const DELIVERY_HOME = 'home';
    const DELIVERY_DESK = 'desk';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function isActive()
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_CONFIRMED, self::STATUS_SHIPPED]);
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            self::STATUS_PENDING => 'قيد الانتظار',
            self::STATUS_CONFIRMED => 'مؤكدة',
            self::STATUS_SHIPPED => 'مرسلة',
            self::STATUS_DELIVERED => 'مسلمة',
            self::STATUS_CANCELLED => 'ملغاة',
        ];
        return $labels[$this->status] ?? $this->status;
    }
}
