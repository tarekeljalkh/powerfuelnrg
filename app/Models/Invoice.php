<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'invoice_number',
        'date',
        'status',
    ];

    public function lineItems()
    {
        return $this->hasMany(InvoiceLineItem::class);
    }

}
