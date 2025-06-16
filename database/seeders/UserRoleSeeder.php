<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            'Admin' => 'Admin',
            'Guru' => 'Guru',
            'User' => 'User',
            'Siti Mujaer' => 'User',
        ];

        foreach ($users as $userName => $roleName) {
            $user = User::where('name', $userName)->first();
            $role = Role::where('role_name', $roleName)->first();

            if ($user && $role) {
                // Check if user role already exists to prevent duplicate
                $existingUserRole = $user->userRoles()->where('role_id', $role->role_id)->first();
                
                if (!$existingUserRole) {
                    $user->userRoles()->create([
                        'role_id' => $role->role_id,
                    ]);
                }
            }
        }
    }
}
