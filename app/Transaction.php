<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    const TYPE_CASH_BACK = "cashback";
    const TYPE_CUSTOMER_PAYMENT = "customer_payment";
    const TYPE_ROOM_PAYMENT = "room_payment";
    const TYPE_FEE = "fee";

    protected $casts = [
        'id' => 'integer',
        'from_wallet' => 'integer',
        'to_wallet' => 'integer',
        'amount' => 'double',
    ];

    public function walletFrom()
    {
        return $this->belongsTo(Wallet::class, 'from_wallet');
    }

    public function walletTo()
    {
        return $this->belongsTo(Wallet::class, 'to_wallet');
    }

    public function from()
    {
        return $this->walletFrom->owner;
    }

    public function to()
    {
        return $this->walletTo->owner;
    }
}
