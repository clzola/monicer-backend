<?php

namespace App\Http\Controllers\Api;

use App\Shop;
use App\Transaction;
use App\User;
use App\Wallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function pay(Request $request)
    {
        $type = $request->get('type');
        $amount = doubleval($request->get('amount'));
        $code = $request->get('code');

        $transaction = new Transaction();
        $transaction->amount = $amount;

        if($type === 'app')
            return $this->payWithApplication($transaction, $code);
        if($type === 'cash')
            return $this->payWithCash($amount, intval($code));
        else return response()->json([
            'error_message' => 'Invalid payment type: expected: app or cash',
            'error_status' => 400
        ], 400);

    }

    /**
     * @param Transaction $transaction
     * @param $code
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function payWithApplication($transaction, $code)
    {
        $customer = User::where('pay_code', $code)->first();
        $shopUser = auth('api')->user();

        if(is_null($customer))
            return response()->json([
                'error_message' => 'Invalid payment code: expected application or room code',
            ]);

        if($customer->id === $shopUser->id) {
            return response()->json([
                'error_message' => 'You cannot send money to yourself!',
            ]);
        }

        $transaction->type = Transaction::TYPE_CUSTOMER_PAYMENT;

        $customer->wallet->balance -= $transaction->amount;
        if($customer->wallet->balance < 0) {
            return response([
                "error_message" => 'Insufficient funds'
            ], 400);
        }

        $transaction->from_wallet = $customer->wallet->id;
        $shopUser->wallet->balance += $transaction->amount;
        $transaction->to_wallet = $shopUser->wallet->id;

        $customer->wallet->save();
        $shopUser->wallet->save();
        $transaction->save();
        $customer->pay_code = 'u'.str_random();
        $customer->save();

        $totalCashBack = $transaction->amount * $shopUser->shop->discount1 / 100;
        $returnToCustomer = $transaction->amount * $shopUser->shop->discount2 / 100;

        $shopUser->wallet->balance -= $totalCashBack;
        $customer->wallet->balance += $returnToCustomer;
        $fee = $totalCashBack - $returnToCustomer;

        $returnTransaction = new Transaction();
        $returnTransaction->amount = $returnToCustomer;
        $returnTransaction->from_wallet = $shopUser->wallet->id;
        $returnTransaction->to_wallet = $customer->wallet->id;
        $returnTransaction->type = Transaction::TYPE_CASH_BACK;
        $returnTransaction->save();

        $monicer = User::find(1);
        $feeTransaction = new Transaction();
        $feeTransaction->amount = $fee;
        $feeTransaction->from_wallet = $shopUser->wallet->id;
        $feeTransaction->to_wallet = $monicer->wallet->id;
        $feeTransaction->type = Transaction::TYPE_FEE;
        $feeTransaction->save();
        $monicer->wallet->balance += $feeTransaction->amount;
        $monicer->wallet->save();

        $customer->wallet->save();
        $shopUser->wallet->save();

        return response()->json([
            'message' => 'Success'
        ]);
    }


    /**
     * @param double $amount
     * @param string $code
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function payWithCash($amount, $code)
    {
        $shopUser = auth('api')->user();
        $customerWallet = Wallet::where('address', $code)->first();

        if(is_null($customerWallet)) {
            return response()->json([
                'error_message' => 'Wallet with that address does not exists!',
            ], 400);
        }

        if($customerWallet->owner->id === $shopUser->id) {
            return response()->json([
                'error_message' => 'You cannot send money to yourself!',
            ]);
        }

        $totalCashBack = $amount * $shopUser->shop->discount1 / 100;
        $returnToCustomer = $amount * $shopUser->shop->discount2 / 100;

        $customerWallet->balance += $returnToCustomer;
        $shopUser->wallet->balance -= $totalCashBack;
        $fee = $totalCashBack - $returnToCustomer;

        $returnTransaction = new Transaction();
        $returnTransaction->amount = $returnToCustomer;
        $returnTransaction->from_wallet = $shopUser->wallet->id;
        $returnTransaction->to_wallet = $customerWallet->id;
        $returnTransaction->type = Transaction::TYPE_CASH_BACK;
        $returnTransaction->save();

        $monicer = User::find(1);
        $returnTransaction = new Transaction();
        $returnTransaction->amount = $fee;
        $returnTransaction->from_wallet = $shopUser->wallet->id;
        $returnTransaction->to_wallet = $monicer->wallet->id;
        $returnTransaction->type = Transaction::TYPE_FEE;
        $returnTransaction->save();

        $customerWallet->save();
        $shopUser->wallet->save();

        return response()->json([
            'message' => 'Success'
        ]);
    }
}
