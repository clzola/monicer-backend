<?php

use App\User;
use Illuminate\Database\Seeder;

class TransactionGenerator extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::where('role', User::ROLE_CUSTOMER)->get();
        $shopUser = User::find(6);
        $controller = new \App\Http\Controllers\Api\PaymentController();

        foreach($users as $user) {
            for($i=0; $i<10; $i++) {
                $amount = rand(10, 30);
                $type = rand(1, 100) <= 70 ? 'app' : 'cash';
                if ($type === 'app') {
                    $transaction = new \App\Transaction();
                    $transaction->amount = $amount;
                    $controller->payWithApplication($transaction, $user->pay_code, $shopUser);
                } else {
                    $controller->payWithCash($amount, $user->wallet->address, $shopUser);
                }
            }
        }
    }
}
