<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    use HasFactory;

    protected $fillable = ['supplier_id', 'inventory_id', 'quantity', 'transaction_date', 'type'];

    protected $casts = [
        'transaction_date' => 'datetime',
    ];

    /**
     * Get the inventory that owns the transaction.
     */
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    /**
     * Get the supplier associated with the transaction.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

}
