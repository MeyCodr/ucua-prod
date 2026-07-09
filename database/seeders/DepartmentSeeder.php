<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Department::create([
            'division_id' => 1,
            'name' => 'Finance Department',
            'short_name' => 'FNS',
            'user_head_id' => null,
            'have_plant' => 0,
            'have_sub_department' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Department::create([
            'division_id' => 1,
            'name' => 'Procurement & Vendor Development Department',
            'short_name' => 'PVD',
            'user_head_id' => null,
            'have_plant' => 0,
            'have_sub_department' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Department::create([
            'division_id' => 1,
            'name' => 'IT & Digitalisation Department',
            'short_name' => 'IT&D',
            'user_head_id' => 4,
            'have_plant' => 0,
            'have_sub_department' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Department::create([
            'division_id' => 2,
            'name' => 'Culture & Talent Mgmt Department',
            'short_name' => 'CTM',
            'user_head_id' => null,
            'have_plant' => 0,
            'have_sub_department' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Department::create([
            'division_id' => 2,
            'name' => 'Rewards & Admin Department',
            'short_name' => 'RA',
            'user_head_id' => null,
            'have_plant' => 0,
            'have_sub_department' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Department::create([
            'division_id' => 2,
            'name' => 'ESG, Safety & Health Department',
            'short_name' => 'ESG-SH',
            'user_head_id' => null,
            'have_plant' => 0,
            'have_sub_department' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Department::create([
            'division_id' => 3,
            'name' => 'Business Development Department',
            'short_name' => 'BD',
            'user_head_id' => null,
            'have_plant' => 0,
            'have_sub_department' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Department::create([
            'division_id' => 3,
            'name' => 'Costing & Commercial Department',
            'short_name' => 'C&C',
            'user_head_id' => null,
            'have_plant' => 0,
            'have_sub_department' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Department::create([
            'division_id' => 3,
            'name' => 'Program Management 1 Department',
            'short_name' => 'PM1',
            'user_head_id' => null,
            'have_plant' => 0,
            'have_sub_department' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Department::create([
            'division_id' => 3,
            'name' => 'Program Management 2 Department',
            'short_name' => 'PM2',
            'user_head_id' => null,
            'have_plant' => 0,
            'have_sub_department' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Department::create([
            'division_id' => 3,
            'name' => 'Program Management 3 Department',
            'short_name' => 'PM3',
            'user_head_id' => null,
            'have_plant' => 0,
            'have_sub_department' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Department::create([
            'division_id' => 4,
            'name' => 'Engineering Management 1 Department',
            'short_name' => 'EM1',
            'user_head_id' => null,
            'have_plant' => 0,
            'have_sub_department' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Department::create([
            'division_id' => 4,
            'name' => 'Engineering Management 2 Department',
            'short_name' => 'EM2',
            'user_head_id' => null,
            'have_plant' => 0,
            'have_sub_department' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Department::create([
            'division_id' => 5,
            'name' => 'Quality Operations Department',
            'short_name' => 'QO',
            'user_head_id' => null,
            'have_plant' => 0,
            'have_sub_department' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Department::create([
            'division_id' => 5,
            'name' => 'Quality Support Department',
            'short_name' => 'QS',
            'user_head_id' => null,
            'have_plant' => 0,
            'have_sub_department' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Department::create([
            'division_id' => 5,
            'name' => 'Quality Assurance & Control 1 Department',
            'short_name' => 'QAC1',
            'user_head_id' => null,
            'have_plant' => 0,
            'have_sub_department' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Department::create([
            'division_id' => 5,
            'name' => 'Quality Assurance & Control 2 Department',
            'short_name' => 'QAC2',
            'user_head_id' => null,
            'have_plant' => 0,
            'have_sub_department' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Department::create([
            'division_id' => 5,
            'name' => 'Quality Assurance & Control 3 Department',
            'short_name' => 'QAC3',
            'user_head_id' => null,
            'have_plant' => 0,
            'have_sub_department' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Department::create([
            'division_id' => 5,
            'name' => 'Quality Development Department',
            'short_name' => 'QD',
            'user_head_id' => null,
            'have_plant' => 0,
            'have_sub_department' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Department::create([
            'division_id' => 6,
            'name' => 'Operation 1 & IMP Department',
            'short_name' => 'OPR1',
            'user_head_id' => null,
            'have_plant' => 1,
            'have_sub_department' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Department::create([
            'division_id' => 6,
            'name' => 'Operation 2 Department',
            'short_name' => 'OPR2',
            'user_head_id' => null,
            'have_plant' => 1,
            'have_sub_department' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Department::create([
            'division_id' => 6,
            'name' => 'Operation 3 Department',
            'short_name' => 'OPR3',
            'user_head_id' => null,
            'have_plant' => 1,
            'have_sub_department' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Department::create([
            'division_id' => 7,
            'name' => 'Operation & Program Management Department',
            'short_name' => 'OPM',
            'user_head_id' => null,
            'have_plant' => 0,
            'have_sub_department' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Department::create([
            'division_id' => 7,
            'name' => 'HICOM Intelligent Mobility Department',
            'short_name' => 'HIM',
            'user_head_id' => null,
            'have_plant' => 0,
            'have_sub_department' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
