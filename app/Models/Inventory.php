<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = ['supplier_id', 'fuel_type', 'quantity'];

    /**
     * Get the supplier associated with the inventory.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the transactions associated with the inventory.
     */
    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }

}
