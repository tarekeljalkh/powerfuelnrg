<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThirdParty extends Model
{
    use HasFactory;

    protected $table = 'third_parties';

    protected $fillable = [
        'name', 'address', 'phone', 'email', 'created_by'
    ];

    public function journals()
    {
        return $this->hasMany(Journal::class, 'third_party_id');
    }

    public function journalLineItems()
    {
        return $this->hasMany(JournalLineItem::class, 'third_party_id');
    }

}
