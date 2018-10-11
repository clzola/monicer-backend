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
            'user_id' => 6,
            'address' => 'Ulica u podgorici br 20, Podgorica',
            'latitude' => 42.442574,
            'longitude' => 19.268646,
        ]);
    }
}
