<?php

namespace Database\Seeders;

use App\Models\StopCulture;
use Illuminate\Database\Seeder;

class StopCultureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StopCulture::create([
            'name' => 'STOP 1',
            'description' => 'Caught in Machine',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        StopCulture::create([
            'name' => 'STOP 2',
            'description' => 'Hit by Object - Heavy / Sharp / Hot',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        StopCulture::create([
            'name' => 'STOP 3',
            'description' => 'Material Handling, Equipment & Vehicle Movement',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        StopCulture::create([
            'name' => 'STOP 4',
            'description' => 'Fall From Different Heights',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        StopCulture::create([
            'name' => 'STOP 5',
            'description' => 'Electrocution',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        StopCulture::create([
            'name' => 'STOP 6',
            'description' => 'Fire',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        StopCulture::create([
            'name' => 'STOP 7',
            'description' => 'Construction',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        StopCulture::create([
            'name' => 'STOP 8',
            'description' => 'Others (Environmental, Noise, Chemical, PPE, SOP, SW, Hygiene, Health, etc)',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
