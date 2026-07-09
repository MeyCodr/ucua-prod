<?php

namespace Database\Seeders;

use App\Models\Rank;
use Illuminate\Database\Seeder;

class RankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Rank::create([
            'name' => 'Rank A',
            'description' => 'Kematian atau cacat kekal',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Rank::create([
            'name' => 'Rank B',
            'description' => 'Cedera parah atau MC lebih 4 hari',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Rank::create([
            'name' => 'Rank C',
            'description' => 'Cedera ringan atau first aid',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
