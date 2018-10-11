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
            'balance' => 1000,
            'address' => 58957
        ]);

        \App\Wallet::create([
            'owner_id' => 2,
            'balance' => 1000,
            'address' => 125698
        ]);

        \App\Wallet::create([
            'owner_id' => 3,
            'balance' => 1000,
            'address' => 874521
        ]);

        \App\Wallet::create([
            'owner_id' => 4,
            'balance' => 1000,
            'address' => 896697
        ]);

        \App\Wallet::create([
            'owner_id' => 5,
            'balance' => 1000,
            'address' => 55964
        ]);
    }
}
