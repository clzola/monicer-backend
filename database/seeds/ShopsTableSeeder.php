<?php

use Illuminate\Database\Seeder;

class ShopsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Shop::create([
            'name' => 'Bonella Green Bazar',
            'discount1' => 10,
            'discount2' => 5,
            'user_id' => 7,
            'address' => 'Dzordza Vasingtona br. 20, Podgorica',
            'latitude' => 42.442574,
            'longitude' => 19.268646,
        ]);
    }
}
