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
            'user_id' => 6
        ]);
    }
}
