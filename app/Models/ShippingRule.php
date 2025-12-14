<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'wilaya',
        'wilaya_code',
        'home_delivery_fee',
        'desk_delivery_fee',
    ];

    protected $casts = [
        'home_delivery_fee' => 'decimal:2',
        'desk_delivery_fee' => 'decimal:2',
    ];

    public static function getFeeByWilayaAndType($wilaya, $type)
    {
        $rule = self::where('wilaya', $wilaya)->first();
        
        if (!$rule) {
            return 0;
        }

        return $type === 'home' ? $rule->home_delivery_fee : $rule->desk_delivery_fee;
    }
}
