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
        $shopUser = User::find(7);
        $controller = new \App\Http\Controllers\Api\PaymentController();

        foreach($users as $user) {
            for($i=0; $i<10; $i++) {
                $user->pay_code = User::find($user->id)->pay_code;
                $amount = rand(10, 30);
                $type = 'app';
                if ($type === 'app') {
                    print_r($controller->payWithApplication($amount, $user->pay_code, $shopUser));
                } else {
                    $controller->payWithCash($amount, $user->wallet->address, $shopUser);
                }
            }
        }
    }
}
