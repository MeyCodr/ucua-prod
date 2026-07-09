<?php

namespace Database\Seeders;

use App\Models\Site;
use Illuminate\Database\Seeder;

class SiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Site::create([
            'name' => 'PHN Shah Alam 1 (SA1)',
            'short_name' => 'PHNSA',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Site::create([
            'name' => 'PHN Shah Alam 2 (SA2)',
            'short_name' => 'PHNSA2',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Site::create([
            'name' => 'PHN Pegoh (PGH)',
            'short_name' => 'PHNPGH',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Site::create([
            'name' => 'PHN Tanjung Malim (TGM2)',
            'short_name' => 'PHNTGM',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Site::create([
            'name' => 'PHN FIF Tanjung Malim (TGM1)',
            'short_name' => 'PHNFIF',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Site::create([
            'name' => 'PHN Rasa (RASA)',
            'short_name' => 'PHNRASA',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Site::create([
            'name' => 'PHN Pekan (PKN)',
            'short_name' => 'PHNPKN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
