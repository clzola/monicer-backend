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
    ];

    protected $casts = [
        'id' => 'integer',
    ];
}
