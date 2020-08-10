<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!User::count()) {
            $users = [
                [
                    'name'     => 'ADM TAG BIKE',
                    'email'    => 'admin@tagbike.com.br',
                    'password' => Hash::make('k7kLKbzNZs3i'),
                    'uf' => 'sp',
                    'city' => 'sp',
                    'cellphone' => '12345678',
                    'cpf' => '87654321098',
                    'birthday' => '1900-03-01',
                    'sexy' => '0'
                ]
            ];

            User::insert($users);
        }
    }
}

