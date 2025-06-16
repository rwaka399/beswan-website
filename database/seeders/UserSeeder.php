<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'raka.test@gmail.com'],
            [
                'name'          => 'Admin',
                'password'      => Hash::make('rakaraka1'),
                'phone_number'  => '08123456789',
                'address'       => 'jl.rambutan'
            ]
        );
        
        User::updateOrCreate(
            ['email' => 'guru.test@gmail.com'],
            [
                'name'          => 'Guru',
                'password'      => Hash::make('guruguru123'),
                'phone_number'  => '08123456789',
                'address'       => 'jl.mangga'
            ]
        );
        
        User::updateOrCreate(
            ['email' => 'user.test@gmail.com'],
            [
                'name'          => 'User',
                'password'      => Hash::make('useruser123'),   
                'phone_number'  => '08123456789',
                'address'       => 'jl.durian'
            ]
        );
        
        User::updateOrCreate(
            ['email' => 'siti.test@gmail.com'],
            [
                'name'          => 'Siti Mujaer',
                'password'      => Hash::make('siti1234'),   
                'phone_number'  => '08123456789',
                'address'       => 'jl.durian'
            ]
        );
    }
}
