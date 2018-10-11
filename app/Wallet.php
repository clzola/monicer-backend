<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = [
        'owner_id',
        'address',
    ];

    protected $casts = [
        'id' => 'integer',
        'owner_id' => 'integer',
        'address' => 'integer',
        'balance' => 'double',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
