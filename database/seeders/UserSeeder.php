<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin  = User::create([
            'name' => 'Admin',
            'email' => 'admin_ucua@phn.com.my',
            'password' => Hash::make('P@ssw0rd1'),
            'phone_number' => null,
            'designation' => null,
            'is_enabled' => 1,
            'is_locked' => 0,
            'num_failed_login_attempt' => 0,
            'password_expiry_date' => now()->addYear(10),
            'last_password_reset' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $admin->groups()->attach(1);
        $admin->department()->attach(3); // IT & Digitalisation Department

        $she_admin = User::create([
            'name' => 'SHE PHN Admin',
            'email' => 'shephn@phn.com.my',
            'password' => Hash::make('P@ssw0rd1'),
            'phone_number' => '0123456789',
            'designation' => null,
            'is_enabled' => 1,
            'is_locked' => 0,
            'num_failed_login_attempt' => 0,
            'password_expiry_date' => now()->addYear(10),
            'last_password_reset' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $she_admin->groups()->attach(2);
        $she_admin->department()->attach(6); // Safety, Health & Environment Department

        $hodiv = User::create([
            'name' => 'HOD Finance, Procurement & IT Division 1',
            'email' => 'hodiv_1@phn.com.my',
            'password' => Hash::make('P@ssw0rd1'),
            'phone_number' => '0123456789',
            'designation' => null,
            'is_enabled' => 1,
            'is_locked' => 0,
            'num_failed_login_attempt' => 0,
            'password_expiry_date' => now()->addYear(10),
            'last_password_reset' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $hodiv->groups()->attach(3);

        $hodept = User::create([
            'name' => 'HOD IT 1',
            'email' => 'hodept_1@phn.com.my',
            'password' => Hash::make('P@ssw0rd1'),
            'phone_number' => '0123456789',
            'designation' => null,
            'is_enabled' => 1,
            'is_locked' => 0,
            'num_failed_login_attempt' => 0,
            'password_expiry_date' => now()->addYear(10),
            'last_password_reset' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $hodept->groups()->attach(4);

        $hosubdept = User::create([
            'name' => 'HOSD 1',
            'email' => 'hosubdept_1@phn.com.my',
            'password' => Hash::make('P@ssw0rd1'),
            'phone_number' => '0123456789',
            'designation' => null,
            'is_enabled' => 1,
            'is_locked' => 0,
            'num_failed_login_attempt' => 0,
            'password_expiry_date' => now()->addYear(10),
            'last_password_reset' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $hosubdept->groups()->attach(5);

        $hop = User::create([
            'name' => 'HOP 1',
            'email' => 'hop_1@phn.com.my',
            'password' => Hash::make('P@ssw0rd1'),
            'phone_number' => '0123456789',
            'designation' => null,
            'is_enabled' => 1,
            'is_locked' => 0,
            'num_failed_login_attempt' => 0,
            'password_expiry_date' => now()->addYear(10),
            'last_password_reset' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $hop->groups()->attach(6);

        $hos = User::create([
            'name' => 'HOS 1',
            'email' => 'hos_1@phn.com.my',
            'password' => Hash::make('P@ssw0rd1'),
            'phone_number' => '0123456789',
            'designation' => null,
            'is_enabled' => 1,
            'is_locked' => 0,
            'num_failed_login_attempt' => 0,
            'password_expiry_date' => now()->addYear(10),
            'last_password_reset' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $hos->groups()->attach(7);
    }
}
