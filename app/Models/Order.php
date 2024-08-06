<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'inventory_id',
        'quantity',
        'price',
        'status',
        'order_date',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'order_date' => 'datetime',
    ];

    /**
     * Get the client that owns the order.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the inventory associated with the order.
     */
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    /**
     * Calculate the total price of the order.
     */
    public function getTotalAttribute()
    {
        return $this->quantity * $this->price;
    }

}
