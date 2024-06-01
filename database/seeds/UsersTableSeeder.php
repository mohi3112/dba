<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Create superadmin user
        $superadmin = User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'superadmin@example.com',
            'gender' => 1,
            'mobile1' => 1234567890,
            'address' => 'Test Address',
            'password' => Hash::make('123456'),
        ]);

        // Assign role
        $superadmin->roles()->attach(1); // role_id 1 corresponds to 'superadmin'

        // Create admin user
        $admin = User::create([
            'first_name' => 'President',
            'last_name' => 'User',
            'email' => 'president@example.com',
            'gender' => 1,
            'mobile1' => 1234567890,
            'address' => 'Test Address',
            'password' => Hash::make('123456'),
        ]);

        // Assign role
        $admin->roles()->attach(2); // role_id 2 corresponds to 'admin'

        // Create regular user
        $user = User::create([
            'first_name' => 'Lawyer',
            'last_name' => 'User',
            'email' => 'user@example.com',
            'gender' => 1,
            'mobile1' => 1234567890,
            'address' => 'Test Address',
            'password' => Hash::make('123456'),
        ]);

        // Assign role
        $user->roles()->attach(8); // role_id 8 corresponds to 'user'
    }
}
