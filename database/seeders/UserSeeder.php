<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Simple creation with check for existing users
        $users = [
            [
                'name' => 'Admin',
                'email' => 'raka.test@gmail.com',
                'password' => Hash::make('Raka1234'),
                'phone_number' => '08123456789',
                'address' => 'jl.rambutan',
            ],
            [
                'name' => 'Guru',
                'email' => 'guru.test@gmail.com',
                'password' => Hash::make('Guru1234'),
                'phone_number' => '08123456789',
                'address' => 'jl.mangga',
            ],
            [
                'name' => 'User',
                'email' => 'user.test@gmail.com',
                'password' => Hash::make('User1234'),
                'phone_number' => '08123456789',
                'address' => 'jl.durian',
            ],
            [
                'name' => 'Siti Mujaer',
                'email' => 'siti.test@gmail.com',
                'password' => Hash::make('Siti1234'),
                'phone_number' => '08123456789',
                'address' => 'jl.durian',
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
