<?php

namespace App\Http\Resources;

use App\Room;
use App\Transaction;
use App\User;
use App\Wallet;
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
        $to = [];
        $amount = $this->amount;
        if($this->type === Transaction::TYPE_ROOM_PAYMENT) {
            $room = null;
            $toWallet = null;
            if($this->to_room) {
                $room = Room::find($this->to_wallet);
                $toWallet = Wallet::find($room->to_wallet);
                $amount = $room->customers()->where('pivot_customer_id', $this->walletFrom->owner->id)->first()->pivot->particiation_amount;
            }
            else {
                $room = Room::find($this->from_wallet);
                $toWallet = Wallet::where('owner_id', $room->owner_id)->first();
            }

            $to['is_shop'] = $toWallet->owner->role === User::ROLE_SHOP;
            $to['name'] = $toWallet->owner->getRealWalletOwnerName();
            $to['cash_back_percent'] = $toWallet->owner->getCashBackPercent();
        } else {
            $to['is_shop'] = $this->walletTo->owner->role === User::ROLE_SHOP;
            $to['name'] = $this->walletTo->owner->getRealWalletOwnerName();
            $to['cash_back_percent'] = $this->walletTo->owner->getCashBackPercent();
        }

        return [
            'id' => $this->id,
            'from' => [
                'name' => $this->walletFrom->owner->getRealWalletOwnerName(),
            ],
            'to' => $to,
            'amount' => $amount,
            'type' => $this->type,
            'created_at' => $this->created_at->format('Y-m-d H:m:s'),
        ];
    }
}
