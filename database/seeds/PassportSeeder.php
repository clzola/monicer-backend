<?php

use Illuminate\Database\Seeder;

class PassportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('oauth_clients')->insert([
            "name" => "Hackaton Personal Access Client",
            "secret" => "jiLGFrM3mZ7z2G8aSPVS71swN3L9Eng59CAR3B75",
            "redirect" => "http://localhost ",
            "personal_access_client" => true,
            "password_client" => false,
            "revoked" => false
        ]);

        \DB::table('oauth_clients')->insert([
            "name" => "Hackaton Password Grant Client",
            "secret" => "ORycnc2G0MQ9Nft5JaiJ8W03iGGakzOCZrhl48S2",
            "redirect" => "http://localhost ",
            "personal_access_client" => false,
            "password_client" => true,
            "revoked" => false
        ]);


        \DB::table("oauth_personal_access_clients")->insert([
            'client_id' => 1
        ]);
    }

}
