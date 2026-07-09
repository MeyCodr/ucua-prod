<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Division::create([
            'name' => 'Finance, Procurement & IT Division',
            'user_head_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Division::create([
            'name' => 'Human Capital & ESG Division',
            'user_head_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Division::create([
            'name' => 'Business Development and Strategy Division',
            'user_head_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Division::create([
            'name' => 'Engineering and RND Division',
            'user_head_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Division::create([
            'name' => 'Quality Management Division',
            'user_head_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Division::create([
            'name' => 'Operations Management Division',
            'user_head_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Division::create([
            'name' => 'DHMSB',
            'user_head_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
