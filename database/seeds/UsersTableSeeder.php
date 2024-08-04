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
            'designation' => 1,
            'account_approved' => true,
            'password' => Hash::make('123456'),
        ]);

        // Assign role
        $superadmin->roles()->attach(1); // role_id 1 corresponds to 'superadmin'

        // Create president user
        $president = User::create([
            'first_name' => 'Chetan',
            'last_name' => 'Verma',
            'email' => 'president@example.com',
            'gender' => 1,
            'mobile1' => 9815908091,
            'designation' => User::DESIGNATION_PRESIDENT,
            'account_approved' => true,
            'password' => Hash::make('dba@123'),
        ]);

        // Assign role
        $president->roles()->attach(User::DESIGNATION_PRESIDENT); // role_id 2 corresponds to 'president'

        // Create vicePresident user
        $vicePresident = User::create([
            'first_name' => 'Sandeep',
            'last_name' => 'Arora',
            'email' => 'vicepresident@example.com',
            'gender' => 1,
            'mobile1' => 9814822305,
            'designation' => User::DESIGNATION_VICE_PRESIDENT,
            'account_approved' => true,
            'password' => Hash::make('dba@123'),
        ]);

        // Assign role
        $vicePresident->roles()->attach(User::DESIGNATION_VICE_PRESIDENT);

        // Create secretary user
        $secretary = User::create([
            'first_name' => 'Parminder Pal',
            'last_name' => 'S',
            'email' => 'secretary@example.com',
            'gender' => 1,
            'mobile1' => 9417009621,
            'designation' => User::DESIGNATION_SECRETARY,
            'account_approved' => true,
            'password' => Hash::make('dba@123'),
        ]);

        // Assign role
        $secretary->roles()->attach(User::DESIGNATION_SECRETARY);

        // Create financesecretary user
        $financesecretary = User::create([
            'first_name' => 'Karnish',
            'last_name' => 'Gupta',
            'email' => 'financesecretary@example.com',
            'gender' => 1,
            'mobile1' => 9888882383,
            'designation' => User::DESIGNATION_FINANCE_SECRETARY,
            'account_approved' => true,
            'password' => Hash::make('dba@123'),
        ]);

        // Assign role
        $financesecretary->roles()->attach(User::DESIGNATION_FINANCE_SECRETARY);

        // Create jointsecretary user
        $jointsecretary = User::create([
            'first_name' => 'Rajinder S',
            'last_name' => 'Bhandari',
            'email' => 'jointsecretary@example.com',
            'gender' => 1,
            'mobile1' => 9217787175,
            'designation' => User::DESIGNATION_JOINT_SECRETARY,
            'account_approved' => true,
            'password' => Hash::make('dba@123'),
        ]);

        // Assign role
        $jointsecretary->roles()->attach(User::DESIGNATION_JOINT_SECRETARY);

        // Create executive1 user
        $executive1 = User::create([
            'first_name' => 'Paras',
            'last_name' => 'Sharma',
            'email' => 'executive1@example.com',
            'gender' => 1,
            'mobile1' => 9815908080,
            'designation' => User::DESIGNATION_EXECUTIVE_MEMBER,
            'account_approved' => true,
            'password' => Hash::make('dba@123'),
        ]);

        // Assign role
        $executive1->roles()->attach(User::DESIGNATION_EXECUTIVE_MEMBER);

        // Create executive1 user
        $executive2 = User::create([
            'first_name' => 'Vanshika',
            'last_name' => 'Jain',
            'email' => 'executive2@example.com',
            'gender' => 2,
            'mobile1' => 9781883157,
            'designation' => User::DESIGNATION_EXECUTIVE_MEMBER,
            'account_approved' => true,
            'password' => Hash::make('dba@123'),
        ]);

        // Assign role
        $executive2->roles()->attach(User::DESIGNATION_EXECUTIVE_MEMBER);

        // Create executive1 user
        $executive3 = User::create([
            'first_name' => 'Aanchal',
            'last_name' => 'Kapoor',
            'email' => 'executive3@example.com',
            'gender' => 2,
            'mobile1' => 9914340979,
            'designation' => User::DESIGNATION_EXECUTIVE_MEMBER,
            'account_approved' => true,
            'password' => Hash::make('dba@123'),
        ]);

        // Assign role
        $executive3->roles()->attach(User::DESIGNATION_EXECUTIVE_MEMBER);

        // Create executive1 user
        $executive4 = User::create([
            'first_name' => 'Divya',
            'last_name' => 'Mittal',
            'email' => 'executive4@example.com',
            'gender' => 2,
            'mobile1' => 8847651881,
            'designation' => User::DESIGNATION_EXECUTIVE_MEMBER,
            'account_approved' => true,
            'password' => Hash::make('dba@123'),
        ]);

        // Assign role
        $executive4->roles()->attach(User::DESIGNATION_EXECUTIVE_MEMBER);

        // Create executive1 user
        $executive5 = User::create([
            'first_name' => 'Manat',
            'last_name' => 'Arora',
            'email' => 'executive5@example.com',
            'gender' => 1,
            'mobile1' => 9781200055,
            'designation' => User::DESIGNATION_EXECUTIVE_MEMBER,
            'account_approved' => true,
            'password' => Hash::make('dba@123'),
        ]);

        // Assign role
        $executive5->roles()->attach(User::DESIGNATION_EXECUTIVE_MEMBER);

        // Create executive1 user
        $executive6 = User::create([
            'first_name' => 'Umesh',
            'last_name' => 'Garg',
            'email' => 'executive6@example.com',
            'gender' => 1,
            'mobile1' => 8568077869,
            'designation' => User::DESIGNATION_EXECUTIVE_MEMBER,
            'account_approved' => true,
            'password' => Hash::make('dba@123'),
        ]);

        // Assign role
        $executive6->roles()->attach(User::DESIGNATION_EXECUTIVE_MEMBER);
    }
}
