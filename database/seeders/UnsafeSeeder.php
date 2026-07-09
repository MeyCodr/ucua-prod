<?php

namespace Database\Seeders;

use App\Models\Unsafe;
use Illuminate\Database\Seeder;

class UnsafeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $unsafes = [
            [
                'name' => 'Operating equipment / machine without authorization',
                'name_my' => 'Mengendalikan peralatan atau mesin tanpa kebenaran',
                'is_act' => 1,
                'is_condition' => 0,
                'is_enabled' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Not following Zero Harm Rules / SOPs while performing work',
                'name_my' => 'Tidak mengikut Peraturan Zero Harm / SOP semasa bekerja',
                'is_act' => 1,
                'is_condition' => 0,
                'is_enabled' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Not wearing proper Personal Protective Equipment (PPE)',
                'name_my' => 'Tidak memakai Kelengkapan Pelindung Diri (KPD) yang betul',
                'is_act' => 1,
                'is_condition' => 0,
                'is_enabled' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Working without supervision',
                'name_my' => 'Bekerja tanpa penyeliaan',
                'is_act' => 1,
                'is_condition' => 0,
                'is_enabled' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Incompetence / untrained worker',
                'name_my' => 'Pekerja tidak cekap / terlatih',
                'is_act' => 1,
                'is_condition' => 0,
                'is_enabled' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Not fit to work (illness / fatigue)',
                'name_my' => 'Tidak sesuai untuk bekerja (sakit / keletihan)',
                'is_act' => 1,
                'is_condition' => 0,
                'is_enabled' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Speeding inside the premise',
                'name_my' => 'Memecut laju di dalam premis',
                'is_act' => 1,
                'is_condition' => 0,
                'is_enabled' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Working under the influence of alcohol and / or drugs',
                'name_my' => 'Bekerja di bawah pengaruh alkohol dan / atau dadah',
                'is_act' => 1,
                'is_condition' => 0,
                'is_enabled' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Engaged in a dangerous behavior that could lead to a harmful situation',
                'name_my' => 'Terlibat dalam tingkah laku berbahaya yang berisiko memudaratkan',
                'is_act' => 1,
                'is_condition' => 0,
                'is_enabled' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Littering',
                'name_my' => 'Membuang sampah merata-rata',
                'is_act' => 1,
                'is_condition' => 0,
                'is_enabled' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Improper traffic management',
                'name_my' => 'Pengurusan trafik yang tidak betul',
                'is_act' => 1,
                'is_condition' => 0,
                'is_enabled' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Dangerous machine / equipment condition',
                'name_my' => 'Keadaan mesin / jentera yang berbahaya',
                'is_act' => 0,
                'is_condition' => 1,
                'is_enabled' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Defective tools / equipment',
                'name_my' => 'Peralatan / jentera yang rosak',
                'is_act' => 0,
                'is_condition' => 1,
                'is_enabled' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Slippery / uneven floor / road surface',
                'name_my' => 'Permukaan jalan yang licin / tidak rata',
                'is_act' => 0,
                'is_condition' => 1,
                'is_enabled' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Improper wire management',
                'name_my' => 'Pengurusan wayar yang tidak betul',
                'is_act' => 0,
                'is_condition' => 1,
                'is_enabled' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Malfunction safety devices',
                'name_my' => 'Peranti keselamatan yang tidak berfungsi',
                'is_act' => 0,
                'is_condition' => 1,
                'is_enabled' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Improper working position',
                'name_my' => 'Kedudukan bekerja yang tidak baik',
                'is_act' => 0,
                'is_condition' => 1,
                'is_enabled' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Fire and explosion',
                'name_my' => 'Kebakaran dan letupan',
                'is_act' => 0,
                'is_condition' => 1,
                'is_enabled' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Risk of falling object',
                'name_my' => 'Risiko objek terjatuh',
                'is_act' => 0,
                'is_condition' => 1,
                'is_enabled' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Inadequate safety signage / marking',
                'name_my' => 'Kekurangan tanda amaran keselamatan',
                'is_act' => 0,
                'is_condition' => 1,
                'is_enabled' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Sharp edges / surface',
                'name_my' => 'Permukaan / bucu yang tajam',
                'is_act' => 0,
                'is_condition' => 1,
                'is_enabled' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Loud noise / vibration',
                'name_my' => 'Bunyi / getaran yang kuat',
                'is_act' => 0,
                'is_condition' => 1,
                'is_enabled' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Poor housekeeping',
                'name_my' => 'Tempat kerja yang tidak kemas',
                'is_act' => 0,
                'is_condition' => 1,
                'is_enabled' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Inadequate / malfunction lighting',
                'name_my' => 'Pencahayaan yang malap / lampu tidak berfungsi',
                'is_act' => 0,
                'is_condition' => 1,
                'is_enabled' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Chemical hazard',
                'name_my' => 'Bahan kimia yang berbahaya',
                'is_act' => 0,
                'is_condition' => 1,
                'is_enabled' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Cracked wall / glass',
                'name_my' => 'Dinding / kaca yang retak',
                'is_act' => 0,
                'is_condition' => 1,
                'is_enabled' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Malfunction lift',
                'name_my' => 'Lif tidak berfungsi',
                'is_act' => 0,
                'is_condition' => 1,
                'is_enabled' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Obstruct emergency route',
                'name_my' => 'Menghalang laluan kecemasan',
                'is_act' => 0,
                'is_condition' => 1,
                'is_enabled' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Rotten / aging / fallen tree',
                'name_my' => 'Pokok yang reput / tua / tumbang',
                'is_act' => 0,
                'is_condition' => 1,
                'is_enabled' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Threats of animal / insect / termite',
                'name_my' => 'Ancaman haiwan / serangga / anai-anai',
                'is_act' => 0,
                'is_condition' => 1,
                'is_enabled' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Unpleasant odour / low indoor air quality',
                'name_my' => 'Bau yang tidak menyenangkan / kualiti udara dalaman yang rendah',
                'is_act' => 0,
                'is_condition' => 1,
                'is_enabled' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Poor vehicle maintenance',
                'name_my' => 'Penyelenggaraan kenderaan yang kurang memuaskan',
                'is_act' => 0,
                'is_condition' => 1,
                'is_enabled' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Water / gas leaking',
                'name_my' => 'Kebocoran air / gas',
                'is_act' => 0,
                'is_condition' => 1,
                'is_enabled' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        foreach ($unsafes as $unsafe) {
            Unsafe::create($unsafe);
        }
    }
}
