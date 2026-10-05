<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class LogoProdi extends Model
{
    use HasFactory;

    protected $table = 'logo_prodis';

    protected $fillable = [
        'program_studi',
        'fakultas',
        'nama_organisasi',
        'singkatan',
        'logo',
        'keterangan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * URL logo atau default UIS logo jika file tidak ditemukan.
     */
    public function getLogoUrlAttribute(): string
    {
        if ($this->logo && Storage::disk('public')->exists($this->logo)) {
            return asset('storage/' . $this->logo);
        }

        return asset('assets/img/logo_uis.png');
    }

    /**
     * Mencari logo yang cocok berdasarkan nama Program Studi user.
     */
    public static function getForProdi(?string $prodi): ?self
    {
        if (empty($prodi)) {
            return null;
        }

        $cleaned = strtolower(trim(str_replace(['S1 - ', 'S2 - ', 'S1-', 'S2-', 's1 - ', 's2 - '], '', $prodi)));

        $logos = self::where('is_active', true)->get();

        // 1. Coba exact match
        $exact = $logos->firstWhere('program_studi', $prodi);
        if ($exact) {
            return $exact;
        }

        // 2. Coba normalized match (tanpa prefix S1 - / S2 -)
        return $logos->first(function ($item) use ($cleaned) {
            $itemCleaned = strtolower(trim(str_replace(['S1 - ', 'S2 - ', 'S1-', 'S2-', 's1 - ', 's2 - '], '', $item->program_studi)));
            return $itemCleaned === $cleaned || str_contains($itemCleaned, $cleaned) || str_contains($cleaned, $itemCleaned);
        });
    }
}
