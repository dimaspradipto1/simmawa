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
        'kategori',
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
     * Badge visual untuk kategori organisasi.
     */
    public function getKategoriBadgeAttribute(): string
    {
        $kategori = $this->kategori ?? 'Himpunan Mahasiswa (HIMA)';
        if (str_contains($kategori, 'BEM') || str_contains($kategori, 'DPM') || str_contains($kategori, 'Lembaga')) {
            return '<span class="badge bg-danger"><i class="bi bi-shield-check me-1"></i> ' . e($kategori) . '</span>';
        }
        if (str_contains($kategori, 'UKM') || str_contains($kategori, 'Unit Kegiatan')) {
            return '<span class="badge bg-info text-dark"><i class="bi bi-stars me-1"></i> ' . e($kategori) . '</span>';
        }
        if (str_contains($kategori, 'Fakultas')) {
            return '<span class="badge bg-warning text-dark"><i class="bi bi-building me-1"></i> ' . e($kategori) . '</span>';
        }

        return '<span class="badge bg-primary"><i class="bi bi-mortarboard me-1"></i> ' . e($kategori) . '</span>';
    }

    /**
     * Menghasilkan daftar seluruh Kartu Tanda Anggota (KTA / KTM) untuk masing-masing organisasi user.
     */
    public static function getKtaCardsForUser(?User $user): array
    {
        if (!$user) {
            return [];
        }

        $activeLogos = self::where('is_active', true)->get();
        $userJabatanMap = $user->jabatan_map ?? [];
        $cards = [];

        // 1. Ambil seluruh organisasi yang diikuti user
        $userOrgs = [];
        if (!empty($user->organisasi)) {
            $userOrgs = is_array($user->organisasi)
                ? $user->organisasi
                : array_map('trim', explode(',', (string)$user->organisasi));
        }

        foreach ($userOrgs as $index => $orgName) {
            if (empty($orgName)) continue;
            $cleanOrg = strtolower($orgName);

            // Cari logo di database yang cocok
            $matchedLogo = $activeLogos->first(function ($item) use ($cleanOrg) {
                $itemNama = strtolower(trim((string)$item->nama_organisasi));
                $itemSingkatan = strtolower(trim((string)$item->singkatan));

                if ($itemSingkatan && (str_contains($cleanOrg, $itemSingkatan) || str_contains($itemSingkatan, $cleanOrg))) {
                    return true;
                }
                if ($itemNama && (str_contains($cleanOrg, $itemNama) || str_contains($itemNama, $cleanOrg))) {
                    return true;
                }
                return false;
            });

            // Tentukan singkatan & judul
            $singkatan = $matchedLogo->singkatan ?? trim(explode(' - ', $orgName)[0]);
            $singkatan = str_replace('Universitas Ibnu Sina', 'UIS', $singkatan);
            
            $namaOrg = $matchedLogo->nama_organisasi ?? $orgName;
            
            // Jabatan spesifik untuk organisasi ini
            $posisi = $userJabatanMap[$orgName] ?? null;
            if (!$posisi) {
                // Coba cocokkan dengan key singkatan atau variasi nama
                $cleanSingkatan = strtolower($singkatan);
                foreach ($userJabatanMap as $mapKey => $mapPos) {
                    $cleanKey = strtolower(trim($mapKey));
                    // 1. Cek substring matching langsung
                    if (str_contains($cleanOrg, $cleanKey) || str_contains($cleanKey, $cleanOrg) ||
                        str_contains($cleanSingkatan, $cleanKey) || str_contains($cleanKey, $cleanSingkatan)) {
                        $posisi = $mapPos;
                        break;
                    }
                    // 2. Cek token utama (misal: BEM, DPM, HMTI, dsb.)
                    $firstTokenKey = explode(' ', $cleanKey)[0] ?? '';
                    $firstTokenOrg = explode(' ', $cleanOrg)[0] ?? '';
                    if (!empty($firstTokenKey) && strlen($firstTokenKey) >= 3 && ($firstTokenKey === $firstTokenOrg || str_contains($cleanOrg, $firstTokenKey))) {
                        $posisi = $mapPos;
                        break;
                    }
                }
            }
            if (!$posisi) {
                $posisi = count($userOrgs) === 1 ? ($user->jabatan ?? 'Anggota') : 'Anggota';
            }

            // Tentukan kategori / badge
            $badge = 'Ormawa';
            if (str_contains($orgName, 'BEM') || str_contains($orgName, 'DPM')) {
                $badge = 'Lembaga Kampus';
            } elseif (str_contains($orgName, 'HMTI') || str_contains($orgName, 'HIMASI') || str_contains($orgName, 'HIMA') || str_contains($orgName, 'HMM') || str_contains($orgName, 'HMA') || str_contains($orgName, 'HM')) {
                $badge = 'Himpunan (HIMA)';
            } elseif (str_contains($orgName, 'UKM')) {
                $badge = 'UKM';
            }

            $cards[] = [
                'id'           => 'kta-' . $index,
                'tab_title'    => $singkatan,
                'badge'        => $badge,
                'singkatan'    => $singkatan,
                'nama_org'     => $namaOrg,
                'banner_sub'   => strtoupper($namaOrg),
                'jabatan'      => $posisi,
                'logo_url'     => $matchedLogo ? $matchedLogo->logo_url : asset('assets/img/logo_uis.png'),
                'is_bem'       => str_contains($cleanOrg, 'bem'),
                'qr_data'      => urlencode("VERIFIKASI-KTA-{$singkatan}|" . ($user->nim ?? $user->id) . "|{$user->name}"),
                'qr_text'      => "untuk verifikasi keanggotaan {$singkatan} Universitas Ibnu Sina",
            ];
        }

        // 2. Jika user tidak punya organisasi, atau ingin disediakan KTM prodi jika ada
        if (empty($cards)) {
            $logoProdi = self::getForProdi($user->program_studi);
            $cards[] = [
                'id'           => 'kta-default',
                'tab_title'    => 'KTM Mahasiswa',
                'badge'        => 'Mahasiswa UIS',
                'singkatan'    => $logoProdi->singkatan ?? 'UIS',
                'nama_org'     => $logoProdi->nama_organisasi ?? 'UNIVERSITAS IBNU SINA',
                'banner_sub'   => strtoupper($logoProdi->nama_organisasi ?? 'UNIVERSITAS IBNU SINA'),
                'jabatan'      => $user->jabatan ?: 'Mahasiswa Aktif',
                'logo_url'     => $logoProdi ? $logoProdi->logo_url : asset('assets/img/logo_uis.png'),
                'is_bem'       => false,
                'qr_data'      => urlencode("VERIFIKASI-KTM-UIS|" . ($user->nim ?? $user->id) . "|{$user->name}"),
                'qr_text'      => "untuk verifikasi status kemahasiswaan Universitas Ibnu Sina",
            ];
        }

        return $cards;
    }

    /**
     * Mencari logo yang cocok untuk user (berdasarkan organisasi atau program studi).
     */
    public static function getForUser(?User $user): ?self
    {
        if (!$user) {
            return null;
        }

        $activeLogos = self::where('is_active', true)->get();

        // 1. Coba cocokkan dengan organisasi user
        if (!empty($user->organisasi)) {
            $userOrgs = is_array($user->organisasi)
                ? $user->organisasi
                : array_map('trim', explode(',', (string)$user->organisasi));

            foreach ($userOrgs as $orgName) {
                if (empty($orgName)) continue;
                $cleanOrg = strtolower($orgName);

                $matched = $activeLogos->first(function ($item) use ($cleanOrg) {
                    $itemNama = strtolower(trim((string)$item->nama_organisasi));
                    $itemSingkatan = strtolower(trim((string)$item->singkatan));

                    if ($itemSingkatan && (str_contains($cleanOrg, $itemSingkatan) || str_contains($itemSingkatan, $cleanOrg))) {
                        return true;
                    }
                    if ($itemNama && (str_contains($cleanOrg, $itemNama) || str_contains($itemNama, $cleanOrg))) {
                        return true;
                    }
                    return false;
                });

                if ($matched) {
                    return $matched;
                }
            }
        }

        // 2. Jika tidak ada kecocokan organisasi, cari berdasarkan Program Studi (HIMA)
        if (!empty($user->program_studi)) {
            return self::getForProdi($user->program_studi);
        }

        return null;
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
            if (empty($item->program_studi)) return false;
            $itemCleaned = strtolower(trim(str_replace(['S1 - ', 'S2 - ', 'S1-', 'S2-', 's1 - ', 's2 - '], '', $item->program_studi)));
            return $itemCleaned === $cleaned || str_contains($itemCleaned, $cleaned) || str_contains($cleaned, $itemCleaned);
        });
    }
}
