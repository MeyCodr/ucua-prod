<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Group::truncate();
        
        Group::create([
            'name' => 'admin',
            'name_display' => 'System Admin',
            'description' => 'Group for System Administrators',
            'is_enabled' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Group::create([
            'name' => 'she_admin',
            'name_display' => 'SHE Admin',
            'description' => 'Group for SHE Administrators',
            'is_enabled' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Group::create([
            'name' => 'hodiv',
            'name_display' => 'Head of Division',
            'description' => 'Group for Head of Division',
            'is_enabled' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Group::create([
            'name' => 'hodept',
            'name_display' => 'Head of Department',
            'description' => 'Group for Head of Department',
            'is_enabled' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Group::create([
            'name' => 'hosubdept',
            'name_display' => 'Head of Sub Department',
            'description' => 'Group for Head of Sub Department',
            'is_enabled' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Group::create([
            'name' => 'hop',
            'name_display' => 'Head of Plant',
            'description' => 'Group for Head of Plant',
            'is_enabled' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Group::create([
            'name' => 'hos',
            'name_display' => 'Head of Section',
            'description' => 'Group for Head of Section',
            'is_enabled' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
