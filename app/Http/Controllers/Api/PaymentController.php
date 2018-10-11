<?php

namespace App\Http\Controllers\Api;

use App\Room;
use App\Shop;
use App\Transaction;
use App\User;
use App\Wallet;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function pay(Request $request)
    {
        $amount = doubleval($request->get('amount'));
        $code = $request->get('code');

        $type = ($code[0] == 'u' || $code[0] == 'r') ? 'app' : 'cash';

        if($type === 'app')
            return $this->payWithApplication($amount, $code, auth('api')->user());
        if($type === 'cash')
            return $this->payWithCash($amount, intval($code), auth('api')->user());
        else return response()->json([
            'error_message' => 'Invalid payment type: expected: app or cash',
            'error_status' => 400
        ], 400);

    }

    /**
     * @param $amount
     * @param $code
     * @param $shopUser
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function payWithApplication($amount, $code, $shopUser)
    {
        $response = null;
        if($code[0] == 'r')
             $response = $this->validateSplit($amount, $code, $shopUser);

        if(!is_null($response))
            return $response;

        $customer = User::where('pay_code', $code)->first();


        if(is_null($customer))
            return response()->json([
                'error_message' => 'Invalid payment code: expected application or room code',
            ]);

        if($customer->id === $shopUser->id) {
            return response()->json([
                'error_message' => 'You cannot send money to yourself!',
            ]);
        }

        $transaction = new Transaction();
        $transaction->amount = $amount;
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
    public function payWithCash($amount, $code, $shopUser)
    {
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

    public function validateSplit($amount, $code, $shopUser)
    {
        /** @var Room $room */
        $room = Room::where('code', $code)->first();
        if(is_null($room))
            return null;

        $customersFunds = 0;
        $customers = $room->customers()->get();
        foreach ($customers as $customer) {
            $customersFunds += $customer->wallet->balance;
        }

        if($customersFunds < $amount) {
            return response()->json(['error_message' => 'Insufficient funds for split'], 400);
        }

        $room->status = Room::STATUS_PAYMENT_PROCESSING;
        $room->bill_amount = $amount;
        $room->to_wallet = $shopUser->wallet->id;
        $room->save();

        $totalAmount = $amount;
        $split = array_fill(0, $customers->count(), 0);
        $splitDone = array_fill(0, $customers->count(), false);
        $customersWithInsufficientFunds = 0;
        while( true ) {
            $atLeastOneWithInsufficientFunds = false;
            $distribution = $totalAmount / ($customers->count() - $customersWithInsufficientFunds);
            foreach ($customers as $i => $customer) {
                if( $splitDone[$i] )
                    continue;

                if($customer->wallet->balance < $distribution) {
                    $customersWithInsufficientFunds++;
                    $atLeastOneWithInsufficientFunds = true;
                    $split[$i] = $customer->wallet->balance;
                    $splitDone[$i] = true;
                    $totalAmount -= $customer->wallet->balance;
                    continue;
                } else {
                    $customer->splitAmount = $distribution;
                }
            }
            if(!$atLeastOneWithInsufficientFunds)
                break;
        }

        foreach ($customers as $i => $customer) {
            $customer->wallet->balance -= $split[$i];
            $customer->wallet->save();
            $room->customers()->attach($customer->id, ['participation_amount' => $split[$i]]);
        }

        // TODO: broadcast event to room owner

        return response()->json(['message' => 'Success']);
    }

    public function rebalance($code, Request $request)
    {
        $room = Room::where('code', $code)->findOrFail();
        foreach ($request->get('customers') as $customer) {
            $room->customers()->updateExistingPivot($customer['id'], ['participation_amount' => $customer['amount']]);
        }

        $room->status = Room::STATUS_PAYMENT_PROCESSED;
        $room->save();

        $transaction = new Transaction();
        $transaction->from_wallet = $room->id;
        $transaction->to_wallet = $room->to_wallet;
        $transaction->amount = $room->bill_amount;
        $transaction->type = Transaction::TYPE_ROOM_PAYMENT;
        $transaction->to_room = false;
        $transaction->save();

        foreach ($room->customers as $customer) {
            $transaction = new Transaction();
            $transaction->from_wallet = $customer->wallet->id;
            $transaction->to_wallet = $room->id;
            $transaction->amount = $customer->pivot->participation_amount;
            $transaction->type = Transaction::TYPE_ROOM_PAYMENT;
            $transaction->to_room = true;
            $transaction->save();
        }

        $shopWallet = Wallet::find($room->to_wallet);
        $shop = $shopWallet->owner->shop;

        $totalDiscount = $shop->discount1 * $room->bill_amount / 100;
        $returnToCustomers = $shop->discount2 * $room->bill_amount / 100;

        foreach ($room->customers as $customer) {
            $transaction = new Transaction();
            $transaction->from_wallet = $shopWallet->id;
            $transaction->to_wallet = $customer->wallet->id;
            $transaction->amount = $customer->pivot->participation_amount / $room->bill_amount * $returnToCustomers;
            $transaction->type = Transaction::TYPE_ROOM_PAYMENT;
            $transaction->to_room = true;
            $transaction->save();
        }

        $monicer = User::find(1);
        $transaction = new Transaction();
        $transaction->from_wallet = $shopWallet->id;
        $transaction->to_wallet = $monicer->wallet->id;
        $transaction->amount = $totalDiscount - $returnToCustomers;
        $transaction->type = Transaction::TYPE_FEE;
        $transaction->save();
    }
}
