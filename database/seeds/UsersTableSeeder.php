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
            'name' => 'Monicer Account',
            'email' => 'monicer.account@omnired.eu',
            'password' => bcrypt('123456'),
            'role' => 'admin',
            'pay_code' => 'u'.str_random(),
        ]);

        \App\User::create([
            'name' => 'Aleksandar Vukovic',
            'email' => 'aleksandar.vukovic@omnired.eu',
            'password' => bcrypt('123456'),
            'role' => 'customer',
            'pay_code' => 'u'.str_random(),
        ]);

        \App\User::create([
            'name' => 'Davor Golubovic',
            'email' => 'davor.golubovic@omnired.eu',
            'password' => bcrypt('123456'),
            'role' => 'customer',
            'pay_code' => 'u'.str_random(),
        ]);

        \App\User::create([
            'name' => 'Vladimir Bigovic',
            'email' => 'vladimir.bigovic@omnired.eu',
            'password' => bcrypt('123456'),
            'role' => 'customer',
            'pay_code' => 'u'.str_random(),
        ]);

        \App\User::create([
            'name' => 'Lazar Radinovic',
            'email' => 'lazar.radinovic@omnired.eu',
            'password' => bcrypt('123456'),
            'role' => 'customer',
            'pay_code' => 'u'.str_random(),
        ]);

        \App\User::create([
            'name' => 'Danilo Montero',
            'email' => 'danilo.montero@omnired.eu',
            'password' => bcrypt('123456'),
            'role' => 'customer',
            'pay_code' => 'u'.str_random(),
        ]);

        \App\User::create([
            'name' => 'Janko Jankovic',
            'email' => 'janko.jankovic@test.com',
            'password' => bcrypt('123456'),
            'role' => 'shop',
            'pay_code' => 'u'.str_random(),
        ]);
    }
}
