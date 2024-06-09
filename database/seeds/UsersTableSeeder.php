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
            'account_approved' => true,
            'password' => Hash::make('123456'),
        ]);

        // Assign role
        $superadmin->roles()->attach(1); // role_id 1 corresponds to 'superadmin'

        // Create president user
        $president = User::create([
            'first_name' => 'President',
            'last_name' => 'User',
            'email' => 'president@example.com',
            'gender' => 1,
            'mobile1' => 1234567890,
            'address' => 'Test Address',
            'account_approved' => true,
            'password' => Hash::make('123456'),
        ]);

        // Assign role
        $president->roles()->attach(2); // role_id 2 corresponds to 'president'

        // Create lawyer user
        $lawyer = User::create([
            'first_name' => 'Lawyer',
            'last_name' => 'User',
            'email' => 'lawyer@example.com',
            'gender' => 1,
            'mobile1' => 1234567890,
            'address' => 'Test Address',
            'account_approved' => true,
            'password' => Hash::make('123456'),
        ]);

        // Assign role
        $lawyer->roles()->attach(8); // role_id 8 corresponds to 'lawyer'
    }
}
