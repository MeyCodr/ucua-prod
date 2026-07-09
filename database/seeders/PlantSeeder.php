<?php

namespace Database\Seeders;

use App\Models\Plant;
use Illuminate\Database\Seeder;

class PlantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Plant::truncate();
        
        Plant::create([
            'department_id' => 20,
            'name' => 'Shah Alam 1 Plant',
            'short_name' => 'SA1',
            'user_head_id' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Plant::create([
            'department_id' => 20,
            'name' => 'Shah Alam 2 Plant',
            'short_name' => 'SA2',
            'user_head_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Plant::create([
            'department_id' => 21,
            'name' => 'Rasa Plant',
            'short_name' => 'Rasa',
            'user_head_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Plant::create([
            'department_id' => 21,
            'name' => 'BB Plant',
            'user_head_id' => null,
            'short_name' => 'BB',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Plant::create([
            'department_id' => 21,
            'name' => 'FIF Tg Malim Plant',
            'short_name' => 'TGM1',
            'user_head_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Plant::create([
            'department_id' => 21,
            'name' => 'Tg Malim 2 Plant',
            'short_name' => 'TGM2',
            'user_head_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Plant::create([
            'department_id' => 22,
            'name' => 'Pegoh Plant',
            'short_name' => 'PGH',
            'user_head_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Plant::create([
            'department_id' => 22,
            'name' => 'Pekan Plant',
            'short_name' => 'PKN',
            'user_head_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
