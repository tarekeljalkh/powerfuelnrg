<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionType extends Model
{
    use HasFactory;

    protected $table = 'transaction_types';

    protected $fillable = [
        'trans_code', 'description', 'show_in_receivable',
        'show_in_payable', 'created_by'
    ];

    public function journals()
    {
        return $this->hasMany(Journal::class, 'type_id');
    }

}


