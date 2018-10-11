<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    const STATUS_CREATED = "created";
    const STATUS_SCANNED = "scanned";
    const STATUS_PAYMENT_PROCESSED = "payment_processed";

    protected $fillable = [
        'code',
        'status',
        'owner_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'bill_amount' => 'double',
        'owner_id' => 'integer',
    ];

    public function customers()
    {
        return $this->belongsToMany(User::class, 'room_user', 'room_id', 'customer_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
