<?php

namespace Database\Seeders;

use App\Models\KtaSetting;
use Illuminate\Database\Seeder;

class KtaSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        KtaSetting::firstOrCreate([], [
            'tahun_periode'      => '2026 – 2027',
            'label_periode'      => 'PERIODE',
            'label_masa_berlaku' => 'Masa Berlaku:',
            'teks_masa_berlaku'  => 'Tahun Kepengurusan 2026 – 2027',
            'nama_universitas'   => 'UNIVERSITAS IBNU SINA',
            'slogan_bawah'       => 'Unggul • Berintegritas • Berjiwa Entrepreneur',
        ]);
    }
}

