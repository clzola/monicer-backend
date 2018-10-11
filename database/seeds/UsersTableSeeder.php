<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create([
            'name' => 'Aleksandar Vukovic',
            'email' => 'aleksandar.vukovic@omnired.eu',
            'password' => bcrypt('123456'),
            'role' => 'customer',
            'pay_code' => str_random(),
        ]);

        \App\User::create([
            'name' => 'Davor Golubovic',
            'email' => 'davor.golubovic@omnired.eu',
            'password' => bcrypt('123456'),
            'role' => 'customer',
            'pay_code' => str_random(),
        ]);

        \App\User::create([
            'name' => 'Vladimir Bigovic',
            'email' => 'vladimir.bigovic@omnired.eu',
            'password' => bcrypt('123456'),
            'role' => 'customer',
            'pay_code' => str_random(),
        ]);

        \App\User::create([
            'name' => 'Lazar Radinovic',
            'email' => 'lazar.radinovic@omnired.eu',
            'password' => bcrypt('123456'),
            'role' => 'customer',
            'pay_code' => str_random(),
        ]);
    }
}
