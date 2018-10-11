<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\TransactionResource;
use App\Http\Resources\WalletResource;
use App\Transaction;
use App\User;
use App\Wallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WalletsController extends Controller
{
    public function getMyWallet()
    {
        $wallet = Wallet::with('owner')
            ->where('owner_id', auth('api')->id())
            ->firstOrFail();

        $transactions = Transaction::where('type', '<>', Transaction::TYPE_CASH_BACK)
            ->where(function ($query) use ($wallet) {
                $query->where('from_wallet', $wallet->id)
                    ->orWhere('to_wallet', $wallet->id);
            })
            ->get();

        $savedMoney = doubleval(Transaction::where('type', Transaction::TYPE_CASH_BACK)
            ->where('from_wallet', $wallet->id)
            ->orWhere('to_wallet', $wallet->id)
            ->sum('amount'));

        return [
            'wallet' => (new WalletResource($wallet))->toArray(null),
            'saved_money' => $savedMoney,
            'transactions' => $transactions->map(function ($transaction) {
                return (new TransactionResource($transaction))->toArray(null);
            }),
        ];
    }
}
