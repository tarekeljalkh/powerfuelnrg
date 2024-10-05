<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalLineItem extends Model
{
    use HasFactory;

    protected $table = 'journal_line_items';

    protected $fillable = [
        'trans_id',
        'ligne_id',
        'account_code',
        'dc_indicator',
        'amount',
        'third_party_id',
        'created_by',
        'updated_by'
    ];

    public function journal()
    {
        return $this->belongsTo(Journal::class, 'trans_id', 'trans_id');
    }

    public function thirdParty()
    {
        return $this->belongsTo(ThirdParty::class, 'third_party_id');
    }
}
