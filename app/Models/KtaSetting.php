<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class KtaSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'tahun_periode',
        'label_periode',
        'label_masa_berlaku',
        'teks_masa_berlaku',
        'nama_universitas',
        'slogan_bawah',
        'background_card',
        'catatan_keterangan',
    ];

    /**
     * Mengambil setting KTA aktif, atau membuat data default jika belum ada.
     */
    public static function getSetting(): self
    {
        return self::firstOrCreate([], [
            'tahun_periode'      => '2026 – 2027',
            'label_periode'      => 'PERIODE',
            'label_masa_berlaku' => 'Masa Berlaku:',
            'teks_masa_berlaku'  => 'Tahun Kepengurusan 2026 – 2027',
            'nama_universitas'   => 'UNIVERSITAS IBNU SINA',
            'slogan_bawah'       => 'Unggul • Berintegritas • Berjiwa Entrepreneur',
        ]);
    }

    /**
     * Accessor untuk URL background kartu KTA / KTM.
     */
    public function getBackgroundUrlAttribute(): string
    {
        if ($this->background_card && Storage::disk('public')->exists($this->background_card)) {
            return Storage::disk('public')->url($this->background_card);
        }

        return asset('assets/img/ktm.png');
    }
}

