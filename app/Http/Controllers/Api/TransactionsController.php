<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\TransactionResource;
use App\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransactionsController extends Controller
{
    public function transactions()
    {
        $wallet = auth('api')->user()->wallet;
        $transactions = Transaction::where('from_wallet', $wallet->id)->orWhere('to_wallet', $wallet->id)->paginate(20);
        return TransactionResource::collection($transactions);
    }
}
