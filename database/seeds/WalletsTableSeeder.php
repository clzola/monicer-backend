<?php

use Illuminate\Database\Seeder;

class WalletsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Wallet::create([
            'owner_id' => 1,
            'balance' => 0,
            'address' => 34457767
        ]);

        \App\Wallet::create([
            'owner_id' => 2,
            'balance' => 1000,
            'address' => 9215481
        ]);

        \App\Wallet::create([
            'owner_id' => 3,
            'balance' => 1000,
            'address' => 8641253
        ]);

        \App\Wallet::create([
            'owner_id' => 4,
            'balance' => 1000,
            'address' => 8529641
        ]);

        \App\Wallet::create([
            'owner_id' => 5,
            'balance' => 1000,
            'address' => 2614583
        ]);

        \App\Wallet::create([
            'owner_id' => 6,
            'balance' => 1000,
            'address' => 9614542
        ]);

        \App\Wallet::create([
            'owner_id' => 7,
            'balance' => 1000,
            'address' => 55964
        ]);
    }
}
