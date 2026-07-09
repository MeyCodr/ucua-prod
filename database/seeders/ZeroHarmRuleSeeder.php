<?php

namespace Database\Seeders;

use App\Models\ZeroHarmRule;
use Illuminate\Database\Seeder;

class ZeroHarmRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ZeroHarmRule::create([
            'name' => 'ZHR 1',
            'description' => 'Memakai PPE yang lengkap semasa bekerja',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        ZeroHarmRule::create([
            'name' => 'ZHR 2',
            'description' => 'Menjalani, Memahami dan Mematuhi SOP Kerja dengan jelas',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        ZeroHarmRule::create([
            'name' => 'ZHR 3',
            'description' => 'Memastikan anggota badan berada di posisi selamat semasa peralatan/jentera beroperasi',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        ZeroHarmRule::create([
            'name' => 'ZHR 4',
            'description' => 'Memakai Alat Keselamatan Diri dan mematuhi SOP semasa bekerja di tempat tinggi atau tangga',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        ZeroHarmRule::create([
            'name' => 'ZHR 5',
            'description' => 'Memastikan punca tenaga/elektrik diputuskan sebelum kerja selenggara',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        ZeroHarmRule::create([
            'name' => 'ZHR 6',
            'description' => 'Mematuhi SOP mengangkat objek / beban dengan selamat',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        ZeroHarmRule::create([
            'name' => 'ZHR 7',
            'description' => 'Memastikan kontraktor mendapat kebenaran PTW',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        ZeroHarmRule::create([
            'name' => 'ZHR 8',
            'description' => 'Mendapat kebenaran sebelum memulakan kerja selenggara jentera / peralatan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        ZeroHarmRule::create([
            'name' => 'ZHR 9',
            'description' => 'Mematuhi SOP selenggaraan dalam ruang terkurung',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        ZeroHarmRule::create([
            'name' => 'ZHR 10',
            'description' => 'Merancang laluan kerja yang selamat',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        ZeroHarmRule::create([
            'name' => 'ZHR 11',
            'description' => 'Memakai tali pinggang / Topi keledar semasa memandu kenderaan / forklift',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        ZeroHarmRule::create([
            'name' => 'ZHR 12',
            'description' => 'Sihat dan tidak bekerja dibawah pengaruh dadah, alkohol atau ubat',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
