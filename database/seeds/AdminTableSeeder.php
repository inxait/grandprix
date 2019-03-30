<?php

use App\User;
use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'first_name' => 'Alejandro',
            'last_name' => 'Gutiérrez Acosta',
            'identification' => '1019051055',
            'email' => 'alejandro.gutierrez@inxaitcorp.com',
            'password' => bcrypt('123'),
            'cellphone' => '3156291355',
            'address' => 'Carrera 11a #91-52',
            'gender' => 'Masculino',
            'updated_data' => true,
            'approved_sent' => true,
        ]);
        $user->assignRole('super-admin');

        $user = User::create([
            'first_name' => 'William',
            'last_name' => 'Vega',
            'identification' => '1030570766',
            'email' => 'william.vega@inxaitcorp.com',
            'password' => bcrypt('123'),
            'cellphone' => '3197305217',
            'address' => 'Carrera 11a #91-52',
            'gender' => 'Masculino',
            'updated_data' => true,
            'approved_sent' => true,
        ]);

        $user->assignRole('super-admin');

        $user = User::create([
            'first_name' => 'COMERCIALIZADORA',
            'last_name' => 'REPUESTERA S.A.S',
            'identification' => '901003749',
            'email' => 'correo@repuestera.com',
            'password' => bcrypt('123'),
            'cellphone' => '3132545562',
            'address' => 'Carrera 123',
            'gender' => 'Masculino',
            'updated_data' => true,
            'approved_sent' => true,
        ]);


        DB::table('distributor_user')->insert([
            'distributor_id' => 1,
            'user_id' => $user->id
        ]);

        $user->assignRole('dealer');

    }
}
