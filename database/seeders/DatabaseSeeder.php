<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            GroupSeeder::class,
            LocationSeeder::class,
            DivisionSeeder::class,
            DepartmentSeeder::class,
            SubDepartmentSeeder::class,
            PlantSeeder::class,
            SiteSeeder::class,
            UserSeeder::class,
            UnsafeSeeder::class,
            StopCultureSeeder::class,
            ZeroHarmRuleSeeder::class,
            RankSeeder::class,
        ]);
    }
}
