<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'name' => 'admin',
                'email' => 'templatesstudio71@gmail.com',
                'password' => Hash::make('password123'),
                'gender' => 1, 
                'mobile' => '1234567890',
                'role_id' => 1,
            ],
            [
                'name' => 'user john',
                'email' => 'user@example.com',
                'password' => Hash::make('password123'),
                'gender' => 2,
                'mobile' => '1234567790',
                'role_id' => 2,
            ],
            [
                'name' => 'demo user',
                'email' => 'demouser@example.com',
                'password' => Hash::make('password123'),
                'gender' => 3,
                'mobile' => '1234567890',
                'role_id' => 2,
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
