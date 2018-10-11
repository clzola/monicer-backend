<?php

namespace App\Http\Resources;

use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'from' => [
                'name' => $this->walletFrom->owner->getRealWalletOwnerName(),
            ],
            'to' => [
                'is_shop' => $this->walletTo->owner->role === User::ROLE_SHOP,
                'name' => $this->walletTo->owner->getRealWalletOwnerName(),
                'cash_back_percent' => $this->walletTo->owner->getCashBackPercent(),
            ],
            'amount' => $this->amount,
            'type' => $this->type,
            'created_at' => $this->created_at->format('Y-m-d H:m:s'),
        ];
    }
}
