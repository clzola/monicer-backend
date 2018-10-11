<?php

namespace App\Http\Resources;

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
                'name' => $this->walletTo->owner->getRealWalletOwnerName(),
            ],
            'amount' => $this->amount,
            'type' => $this->type,
            'created_at' => $this->created_at,
        ];
    }
}
