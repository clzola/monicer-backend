<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    const STATUS_CREATED = "created";
    const STATUS_SCANNED = "scanned";
    const STATUS_PAYMENT_PROCESSING = "payment_processing";
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
        return $this->belongsToMany(User::class, 'room_user', 'room_id', 'customer_id')
            ->withPivot(['room_id', 'user_id', 'participation_amount']);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
