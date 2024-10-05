<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'third_party_id', 'trans_id', 'receipt_number', 'amount', 'date', 'payment_method', 'created_by'
    ];

    public function journal()
    {
        return $this->belongsTo(Journal::class, 'trans_id', 'trans_id');
    }

    public function thirdParty()
    {
        return $this->belongsTo(ThirdParty::class, 'third_party_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }


}
