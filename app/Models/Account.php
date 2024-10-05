<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $primaryKey = 'account_code';

    // If your table name is different from 'accounts', specify it here
    // protected $table = 'your_table_name';

    // Define the primary key if it's not 'id'
    // protected $primaryKey = 'account_code'; // Use this if your primary key is 'account_code'

    // Specify the columns that can be mass-assigned
    protected $fillable = [
        'account_code',
        'account_name',
        'account_type',
        'currency',
        'is_active',
        'parent_id',
    ];

    // If the primary key is not an incrementing integer, set this to false
    // public $incrementing = false;

    // If the primary key is not an integer, set this
    // protected $keyType = 'string';
        // Relation to parent account

        public function currency()
        {
            return $this->belongsTo(Currency::class, 'currency_code', 'currency_code');
        }

        public function parent()
        {
            return $this->belongsTo(Account::class, 'parent_id');
        }

        // Relation to child accounts (if any)
        public function children()
        {
            return $this->hasMany(Account::class, 'parent_id');
        }

        // Relation to journal line items
        public function journalLineItems()
        {
            return $this->hasMany(JournalLineItem::class, 'account_code', 'account_code');
        }

}
