<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory;

    protected $table = 'journals';

    protected $primaryKey = 'trans_id';

    protected $fillable = [
        'trans_id',
        'trans_code',
        'type_id',
        'manual_ref',
        'trans_date',
        'activation_date',
        'locked',
        'status',
        'parent_id',
        'third_party_id',
        'created_by',
        'updated_by'
    ];

    public function transactionType()
    {
        return $this->belongsTo(TransactionType::class, 'type_id', 'id');
    }


    public function journal()
    {
        return $this->belongsTo(Journal::class, 'trans_id', 'trans_id');
    }

    public function lineItems()
    {
        return $this->hasMany(JournalLineItem::class, 'trans_id', 'trans_id');
    }

    public function thirdParty()
    {
        return $this->belongsTo(ThirdParty::class, 'third_party_id');
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class, 'trans_id', 'trans_id');
    }

        // Relation to the account
        public function account()
        {
            return $this->belongsTo(Account::class, 'account_code', 'account_code');
        }

}
