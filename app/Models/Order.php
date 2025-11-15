<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'sub_total',
        'shipping_charge',
        'mfs_charge',
        'vat',
        'total',
        'shipping_address',
        'status',
    ];

    protected $casts = [
        'sub_total' => 'decimal:2',
        'shipping_charge' => 'decimal:2',
        'mfs_charge' => 'decimal:2',
        'vat' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}

