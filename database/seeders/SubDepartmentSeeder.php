<?php

namespace Database\Seeders;

use App\Models\SubDepartment;
use Illuminate\Database\Seeder;

class SubDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SubDepartment::truncate();
        
        SubDepartment::create([
            'department_id' => 12,
            'name' => 'Equipment Maintenance 1 (SA1, Rasa, Pegoh) Department',
            'short_name' => 'EM1',
            'user_head_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        SubDepartment::create([
            'department_id' => 12,
            'name' => 'Equipment Maintenance 2 (SA2, TGM1, TGM2) Department',
            'short_name' => 'EM2',
            'user_head_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        SubDepartment::create([
            'department_id' => 12,
            'name' => 'Process Engineering Department',
            'short_name' => 'PE',
            'user_head_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        SubDepartment::create([
            'department_id' => 13,
            'name' => 'Tooling Design & Development Department',
            'short_name' => 'TDD',
            'user_head_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        SubDepartment::create([
            'department_id' => 13,
            'name' => 'Research & Development Department',
            'short_name' => 'RND',
            'user_head_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        SubDepartment::create([
            'department_id' => 13,
            'name' => 'Facility & Energy Management Department',
            'short_name' => 'FEM',
            'user_head_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        SubDepartment::create([
            'department_id' => 20,
            'name' => 'Inventory Management Planning Department',
            'short_name' => 'IMP',
            'user_head_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        SubDepartment::create([
            'department_id' => 21,
            'name' => 'OPR FIF/TM Department',
            'short_name' => 'OPRFIFTM',
            'user_head_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
