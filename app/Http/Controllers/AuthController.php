<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard.index');
        }

        return view('layouts.auth.login');
    }

    /**
     * Master Data Fakultas, Prodi, Organisasi, dan Jabatan Resmi UIS SK 2026-2027.
     */
    public static function getMasterAcademicAndOrgData(): array
    {
        return [
            'fakultas' => [
                'Fakultas Sains dan Teknologi' => 'Fakultas Sains dan Teknologi',
                'Fakultas Ekonomi dan Bisnis'  => 'Fakultas Ekonomi dan Bisnis',
                'Fakultas Ilmu Kesehatan'      => 'Fakultas Ilmu Kesehatan',
                'Pascasarjana'                 => 'Pascasarjana',
            ],
            'prodis' => [
                'Fakultas Sains dan Teknologi' => [
                    'S1 - Teknik Informatika' => 'S1 - Teknik Informatika (HMTI)',
                    'S1 - Sistem Informasi'   => 'S1 - Sistem Informasi (HIMASI)',
                    'S1 - Teknik Industri'    => 'S1 - Teknik Industri (HIMATRI)',
                    'S1 - Teknik Logistik'    => 'S1 - Teknik Logistik (HIMANISTIK)',
                    'S1 - Teknik Perkapalan'  => 'S1 - Teknik Perkapalan (HMTP)',
                ],
                'Fakultas Ekonomi dan Bisnis' => [
                    'S1 - Manajemen'          => 'S1 - Manajemen (HMM)',
                    'S1 - Akuntansi'          => 'S1 - Akuntansi (HMA)',
                    'S2 - Magister Manajemen' => 'S2 - Magister Manajemen',
                ],
                'Fakultas Ilmu Kesehatan' => [
                    'S1 - Kesehatan dan Keselamatan Kerja' => 'S1 - Kesehatan dan Keselamatan Kerja (HIMA K3)',
                    'S1 - Kesehatan Lingkungan'            => 'S1 - Kesehatan Lingkungan (HIMA KESLING / HMKL)',
                    'S1 - Kesehatan Masyarakat'            => 'S1 - Kesehatan Masyarakat',
                    'S2 - Magister Kesehatan Masyarakat'   => 'S2 - Magister Kesehatan Masyarakat',
                ],
            ],
            'organisasi' => [
                'Lembaga Mahasiswa (Eksekutif & Legislatif)' => [
                    'BEM Universitas Ibnu Sina' => 'BEM (Badan Eksekutif Mahasiswa) UIS',
                    'DPM Universitas Ibnu Sina' => 'DPM (Dewan Perwakilan Mahasiswa) UIS',
                ],
                'Himpunan Mahasiswa Jurusan (HIMA)' => [
                    'HMTI - Teknik Informatika'                         => 'HMTI - Himpunan Mahasiswa Teknik Informatika',
                    'HIMASI - Sistem Informasi'                         => 'HIMASI - Himpunan Mahasiswa Sistem Informasi',
                    'HIMATRI - Teknik Industri'                         => 'HIMATRI - Himpunan Mahasiswa Teknik Industri',
                    'HIMANISTIK - Teknik Logistik'                      => 'HIMANISTIK - Himpunan Mahasiswa Teknik Logistik',
                    'HMTP - Teknik Perkapalan'                          => 'HMTP - Himpunan Mahasiswa Teknik Perkapalan',
                    'HMM - Manajemen'                                   => 'HMM - Himpunan Mahasiswa Manajemen',
                    'HMA - Akuntansi'                                   => 'HMA - Himpunan Mahasiswa Akuntansi',
                    'HIMA K3 - Kesehatan & Keselamatan Kerja'           => 'HIMA K3 - Kesehatan & Keselamatan Kerja',
                    'HMKL - Kesehatan Lingkungan'                       => 'HMKL - Himpunan Mahasiswa Kesehatan Lingkungan',
                    'HIMA KESMAS - Kesehatan Masyarakat'               => 'HIMA KESMAS - Kesehatan Masyarakat',
                ],
                'Unit Kegiatan Mahasiswa (UKM)' => [
                    'UKM SiPena'                    => 'UKM SiPena (Sinergi Para Mahasiswa)',
                    'UKM PERKUSI (Komputer)'        => 'UKM PERKUSI (Persatuan Komputer Mahasiswa UIS)',
                    'UKM Perkusi (Seni Musik)'      => 'UKM Perkusi (Seni Musik & Kreativitas UIS)',
                    'UKM Mapala Ibnu Sina (MAPAIS)' => 'UKM MAPAIS (Mahasiswa Pecinta Alam Ibnu Sina)',
                    'UKM Badminton'                 => 'UKM Badminton UIS',
                    'UKM Futsal'                    => 'UKM Futsal Club UIS',
                    'UKM Pencak Silat'              => 'UKM Pencak Silat UIS',
                    'UKM Atletik'                   => 'UKM Atletik UIS',
                    'UKM Sanggar Tari Rossa Primula'=> 'UKM Sanggar Tari Rossa Primula',
                    'UKM English Club'              => 'UKM English Club UIS',
                    'UKM FORSI'                     => 'UKM FORSI (Forum Studi Islam)',
                    'UKM PUSWIRA'                   => 'UKM PUSWIRA (Pusat Kewirausahaan Mahasiswa)',
                    'UKM Gerakan Pramuka Racana'    => 'UKM Gerakan Pramuka Racana Raja H Fisabilillah - Srikandi Walanda',
                ],
            ],

            // Jabatan Umum Terstruktur (untuk fallback / belum memilih ormawa)
            'jabatan' => [
                'Pimpinan Utama Organisasi' => [
                    'Presiden Mahasiswa (Presma)'     => 'Presiden Mahasiswa (Presma)',
                    'Wakil Presiden Mahasiswa (Wapresma)' => 'Wakil Presiden Mahasiswa (Wapresma)',
                    'Ketua DPM'                       => 'Ketua DPM',
                    'Wakil Ketua DPM'                 => 'Wakil Ketua DPM',
                    'Ketua Umum'                      => 'Ketua Umum',
                    'Wakil Ketua Umum'                => 'Wakil Ketua Umum',
                    'Ketua HIMA'                      => 'Ketua HIMA',
                    'Wakil Ketua HIMA'                => 'Wakil Ketua HIMA',
                    'Ketua UKM'                       => 'Ketua UKM',
                    'Wakil Ketua UKM'                 => 'Wakil Ketua UKM',
                    'Pimpinan Redaksi'                => 'Pimpinan Redaksi (UKM Si Pena)',
                ],
                'Kementerian BEM & Komisi DPM' => [
                    'Menteri Kesekretariatan'         => 'Menteri Kesekretariatan',
                    'Menteri Keuangan'                => 'Menteri Keuangan',
                    'Menteri Luar Negeri'             => 'Menteri Luar Negeri',
                    'Menteri Dalam Negeri'            => 'Menteri Dalam Negeri',
                    'Menteri Sosial Politik dan Hukum'=> 'Menteri Sosial Politik dan Hukum',
                    'Menteri PSDM'                    => 'Menteri Pengembangan Sumber Daya Manusia (PSDM)',
                    'Menteri Komunikasi Digital'      => 'Menteri Komunikasi Digital',
                    'Menteri Agama'                   => 'Menteri Agama',
                    'Menteri Kesehatan'               => 'Menteri Kesehatan',
                    'Ketua Komisi I DPM'              => 'Ketua Komisi I DPM',
                    'Anggota Komisi I DPM'            => 'Anggota Komisi I DPM',
                    'Ketua Komisi II DPM'             => 'Ketua Komisi II DPM',
                    'Anggota Komisi II DPM'           => 'Anggota Komisi II DPM',
                    'Ketua Komisi III DPM'            => 'Ketua Komisi III DPM',
                    'Anggota Komisi III DPM'          => 'Anggota Komisi III DPM',
                    'Ketua Komisi IV DPM'             => 'Ketua Komisi IV DPM',
                    'Anggota Komisi IV DPM'           => 'Anggota Komisi IV DPM',
                ],
                'Kesekretariatan & Keuangan' => [
                    'Sekretaris Jenderal'             => 'Sekretaris Jenderal (Sekjen)',
                    'Sekretaris Umum'                 => 'Sekretaris Umum',
                    'Sekretaris'                      => 'Sekretaris',
                    'Sekretaris I'                    => 'Sekretaris I',
                    'Sekretaris II'                   => 'Sekretaris II',
                    'Bendahara'                       => 'Bendahara',
                    'Bendahara I'                     => 'Bendahara I',
                    'Bendahara II'                    => 'Bendahara II',
                ],
                'Pimpinan Departemen / Divisi / Bidang' => [
                    'Kepala Departemen'               => 'Kepala Departemen',
                    'Kepala Divisi'                   => 'Kepala Divisi',
                    'Kepala Bidang'                   => 'Kepala Bidang',
                    'Koordinator Departemen'          => 'Koordinator Departemen',
                    'Koordinator Divisi'              => 'Koordinator Divisi',
                    'Koordinator Bidang'              => 'Koordinator Bidang',
                    'PIC (Person in Charge)'          => 'PIC (Person in Charge)',
                ],
                'Staf & Keanggotaan' => [
                    'Staf Departemen'                 => 'Staf Departemen',
                    'Staf Divisi'                     => 'Staf Divisi',
                    'Personel Divisi / Bidang'        => 'Personel Divisi / Bidang',
                    'Anggota Divisi / Pengurus'       => 'Anggota Divisi / Pengurus',
                    'Anggota'                         => 'Anggota',
                ],
                'Lainnya' => [
                    'Lainnya'                         => 'Jabatan Lainnya (Ketik Manual)',
                ],
            ],

            // Pemetaan Jabatan Spesifik Resmi Berdasarkan SK 2026-2027
            'jabatan_by_org' => [
                'BEM Universitas Ibnu Sina' => [
                    'Pimpinan BEM' => [
                        'Presiden Mahasiswa (Presma)',
                        'Wakil Presiden Mahasiswa (Wapresma)',
                    ],
                    'Kementerian Kesekretariatan' => [
                        'Menteri Kesekretariatan',
                        'Kepala Departemen Administrasi dan Persuratan',
                        'Staf Departemen Administrasi dan Persuratan',
                        'Kepala Departemen Dokumentasi dan Arsip Organisasi',
                    ],
                    'Kementerian Keuangan' => [
                        'Menteri Keuangan',
                        'Kepala Departemen Pengelolaan Keuangan Internal',
                        'Staf Departemen Pengelolaan Keuangan Internal',
                        'Kepala Departemen Perencanaan dan Pengawasan Anggaran',
                        'Staf Departemen Perencanaan dan Pengawasan Anggaran',
                    ],
                    'Kementerian Luar Negeri' => [
                        'Menteri Luar Negeri',
                        'Kepala Departemen Hubungan Antar Perguruan Tinggi',
                        'Kepala Departemen Kemitraan',
                    ],
                    'Kementerian Dalam Negeri' => [
                        'Menteri Dalam Negeri',
                        'Kepala Departemen Hubungan Organisasi Mahasiswa',
                        'Staf Departemen Hubungan Organisasi Mahasiswa',
                        'Kepala Departemen Pembinaan dan Prestasi Mahasiswa',
                        'Staf Departemen Pembinaan dan Prestasi Mahasiswa',
                    ],
                    'Kementerian Sosial Politik dan Hukum' => [
                        'Menteri Sosial Politik dan Hukum',
                        'Kepala Departemen Kajian dan Aksi Sosial',
                        'Staf Departemen Kajian dan Aksi Sosial',
                        'Kepala Departemen Pergerakan Politik Kebijakan',
                        'Kepala Departemen Advokasi dan Kajian Hukum',
                        'Staf Departemen Advokasi dan Kajian Hukum',
                    ],
                    'Kementerian PSDM' => [
                        'Menteri Pengembangan Sumber Daya Manusia (PSDM)',
                        'Kepala Departemen Kaderisasi dan Kepemimpinan',
                        'Kepala Departemen Pengembangan Kompetensi dan Karier',
                        'Staf Departemen Pengembangan Kompetensi dan Karier',
                    ],
                    'Kementerian Komunikasi Digital' => [
                        'Menteri Komunikasi Digital',
                        'Kepala Departemen Desain Grafis',
                        'Kepala Departemen Dokumentasi',
                        'Staf Departemen Dokumentasi',
                    ],
                    'Kementerian Agama' => [
                        'Menteri Agama',
                        'Kepala Departemen Pembinaan Spiritual',
                        'Kepala Departemen Pengembangan Karakter',
                    ],
                    'Kementerian Kesehatan' => [
                        'Menteri Kesehatan',
                        'Kepala Departemen Promosi, Pemberdayaan dan Edukasi',
                        'Staf Departemen Promosi, Pemberdayaan dan Edukasi',
                        'Kepala Departemen Kesehatan Mental',
                        'Staf Departemen Kesehatan Mental',
                        'Kepala Departemen Kesehatan Lingkungan dan K3',
                        'Staf Departemen Kesehatan Lingkungan dan K3',
                    ],
                    'Keanggotaan' => [
                        'Anggota BEM',
                        'Lainnya',
                    ],
                ],

                'DPM Universitas Ibnu Sina' => [
                    'Pimpinan DPM' => [
                        'Ketua DPM',
                        'Wakil Ketua DPM',
                        'Sekretaris DPM',
                        'Bendahara DPM',
                    ],
                    'Komisi DPM' => [
                        'Ketua Komisi I',
                        'Anggota Komisi I',
                        'Ketua Komisi II',
                        'Anggota Komisi II',
                        'Ketua Komisi III',
                        'Anggota Komisi III',
                        'Ketua Komisi IV',
                        'Anggota Komisi IV',
                    ],
                    'Keanggotaan' => [
                        'Anggota DPM',
                        'Lainnya',
                    ],
                ],

                'HMTI - Teknik Informatika' => [
                    'Pengurus Inti' => [
                        'Ketua',
                        'Wakil Ketua',
                        'Sekretaris Jenderal',
                        'Sekretaris I',
                        'Sekretaris II',
                        'Bendahara I',
                        'Bendahara II',
                    ],
                    'Departemen' => [
                        'Kepala Departemen LITBANG',
                        'Kepala Departemen Program',
                        'Kepala Departemen Hubungan Masyarakat (HUMAS)',
                        'Kepala Departemen Media',
                        'Anggota Departemen',
                    ],
                    'Keanggotaan' => ['Anggota HMTI', 'Lainnya'],
                ],

                'HIMASI - Sistem Informasi' => [
                    'Pengurus Inti' => [
                        'Ketua',
                        'Wakil Ketua',
                        'Sekretaris',
                        'Bendahara',
                    ],
                    'Departemen' => [
                        'Kepala Departemen Media',
                        'Kepala Departemen Humas',
                        'Kepala Departemen LITBANG',
                        'Anggota Departemen',
                    ],
                    'Keanggotaan' => ['Anggota HIMASI', 'Lainnya'],
                ],

                'HIMATRI - Teknik Industri' => [
                    'Pengurus Inti' => [
                        'Ketua',
                        'Wakil Ketua',
                        'Sekretaris I',
                        'Sekretaris II',
                        'Bendahara',
                    ],
                    'Divisi' => [
                        'Kepala Divisi LITBANG',
                        'Kepala Divisi HUMAS',
                        'Kepala Divisi MEDKOM',
                        'Kepala Divisi Kewirausahaan',
                        'Anggota Divisi',
                    ],
                    'Keanggotaan' => ['Anggota HIMATRI', 'Lainnya'],
                ],

                'HIMANISTIK - Teknik Logistik' => [
                    'Pengurus Inti' => [
                        'Ketua',
                        'Wakil Ketua I',
                        'Wakil Ketua II',
                        'Sekretaris Umum',
                        'Bendahara I',
                        'Bendahara II',
                    ],
                    'Departemen' => [
                        'Koordinator Departemen Hubungan Masyarakat',
                        'Koordinator Departemen Media',
                        'Koordinator Departemen Kewirausahaan',
                        'Anggota Departemen',
                    ],
                    'Keanggotaan' => ['Anggota HIMANISTIK', 'Lainnya'],
                ],

                'HMTP - Teknik Perkapalan' => [
                    'Pengurus Inti' => [
                        'Ketua Umum',
                        'Wakil Ketua Umum',
                        'Sekretaris',
                        'Bendahara',
                    ],
                    'Divisi' => [
                        'Kepala Divisi Media dan Informasi',
                        'Kepala Divisi HUMAS',
                        'Kepala Divisi Pendidikan dan Pengembangan',
                        'Kepala Divisi PSDM',
                        'Anggota Divisi',
                    ],
                    'Keanggotaan' => ['Anggota HMTP', 'Lainnya'],
                ],

                'HMM - Manajemen' => [
                    'Pengurus Inti' => [
                        'Ketua',
                        'Wakil Ketua',
                        'Sekretaris',
                        'Bendahara',
                    ],
                    'Departemen' => [
                        'Ketua Departemen Agama',
                        'Ketua Departemen Kominfo',
                        'Ketua Departemen Kewirausahaan',
                        'Ketua Departemen Humas',
                        'Anggota Departemen',
                    ],
                    'Keanggotaan' => ['Anggota HMM', 'Lainnya'],
                ],

                'HMA - Akuntansi' => [
                    'Pengurus Inti' => [
                        'Ketua',
                        'Wakil Ketua',
                        'Sekretaris I',
                        'Sekretaris II',
                        'Bendahara I',
                        'Bendahara II',
                    ],
                    'Divisi' => [
                        'Ketua Divisi Akademik',
                        'Ketua Divisi Kewirausahaan',
                        'Ketua Divisi Informasi dan Teknologi',
                        'Ketua Divisi Hubungan Masyarakat dan Antar Lembaga',
                        'Anggota Divisi',
                    ],
                    'Keanggotaan' => ['Anggota HMA', 'Lainnya'],
                ],

                'HIMA K3 - Kesehatan & Keselamatan Kerja' => [
                    'Pengurus Inti' => [
                        'Ketua HIMA',
                        'Wakil Ketua HIMA',
                        'Sekretaris',
                        'Bendahara',
                    ],
                    'Divisi' => [
                        'Koordinator Divisi Promkes K3',
                        'Koordinator Divisi Humas',
                        'Koordinator Divisi Media',
                        'Anggota Divisi',
                    ],
                    'Keanggotaan' => ['Anggota HIMA K3', 'Lainnya'],
                ],

                'HMKL - Kesehatan Lingkungan' => [
                    'Pengurus Inti' => [
                        'Ketua HIMA KL',
                        'Wakil Ketua HIMA KL',
                        'Sekretaris',
                        'Bendahara',
                    ],
                    'Bidang & PIC' => [
                        'Kepala Bidang Humas',
                        'PIC Internal',
                        'PIC Eksternal',
                        'Kepala Bidang PSDM',
                        'Kepala Bidang Program',
                        'Kepala Bidang MEDKOM',
                        'PIC Publikasi dan Dokumentasi',
                        'PIC Design',
                        'Anggota Bidang',
                    ],
                    'Keanggotaan' => ['Anggota HMKL', 'Lainnya'],
                ],

                'HIMA KESMAS - Kesehatan Masyarakat' => [
                    'Pengurus Inti' => [
                        'Ketua HIMA Kesmas',
                        'Wakil Ketua HIMA Kesmas',
                        'Sekretaris',
                        'Anggota Sekretariat',
                        'Bendahara',
                        'Anggota Bendahara',
                    ],
                    'Bidang' => [
                        'Koordinator Bidang Akademik dan Riset',
                        'Koordinator Bidang Humas dan Jejaring',
                        'Koordinator Bidang Sosial dan Pengabdian Masyarakat',
                        'Koordinator Bidang Media dan Dokumentasi',
                        'Anggota Bidang',
                    ],
                    'Keanggotaan' => ['Anggota HIMA KESMAS', 'Lainnya'],
                ],

                'UKM SiPena' => [
                    'Pengurus Inti' => [
                        'Ketua',
                        'Wakil Ketua',
                        'Pimpinan Redaksi',
                        'Sekretaris',
                        'Bendahara',
                    ],
                    'Redaksi & Anggota' => [
                        'Redaktur / Wartawan Mahasiswa',
                        'Anggota',
                    ],
                    'Keanggotaan' => ['Lainnya'],
                ],

                'UKM Mapala Ibnu Sina (MAPAIS)' => [
                    'Pengurus Inti' => [
                        'Ketua Umum',
                        'Wakil Ketua Umum',
                        'Sekretaris I',
                        'Sekretaris II',
                        'Bendahara I',
                        'Bendahara II',
                        'Humas I',
                        'Humas II',
                        'Humas III',
                    ],
                    'Divisi' => [
                        'Koordinator Divisi Lingkungan Hidup',
                        'Anggota Divisi Lingkungan Hidup',
                        'Koordinator Divisi Hutan Rimba',
                        'Anggota Divisi Hutan Rimba',
                        'Anggota',
                    ],
                    'Keanggotaan' => ['Lainnya'],
                ],

                'UKM Atletik' => [
                    'Pengurus Inti' => [
                        'Ketua',
                        'Wakil Ketua',
                        'Sekretaris',
                        'Sekretaris II',
                        'Bendahara',
                        'Humas I',
                        'Humas II',
                        'Anggota',
                    ],
                    'Keanggotaan' => ['Lainnya'],
                ],

                'UKM Badminton' => [
                    'Pengurus Inti' => [
                        'Ketua Umum',
                        'Wakil Ketua Umum',
                        'Sekretaris',
                        'Bendahara',
                    ],
                    'Divisi' => [
                        'Koordinator Divisi Media dan Dokumentasi',
                        'Anggota',
                    ],
                    'Keanggotaan' => ['Lainnya'],
                ],

                'UKM English Club' => [
                    'Pengurus Inti' => [
                        'Ketua',
                        'Wakil Ketua',
                        'Sekretaris',
                        'Bendahara',
                        'Anggota',
                    ],
                    'Keanggotaan' => ['Lainnya'],
                ],

                'UKM FORSI' => [
                    'Pengurus Inti' => [
                        'Ketua',
                        'Wakil Ketua',
                        'Sekretaris',
                        'Bendahara',
                    ],
                    'Departemen' => [
                        'Kepala Departemen Kaderisasi',
                        'Anggota Departemen Kaderisasi',
                        'Kepala Departemen Syiar dan Dakwah',
                        'Anggota Departemen Syiar dan Dakwah',
                        'Kepala Departemen Humas',
                        'Anggota Departemen Humas',
                        'Kepala Departemen Kemuslimahan',
                        'Anggota Departemen Kemuslimahan',
                        'Anggota',
                    ],
                    'Keanggotaan' => ['Lainnya'],
                ],

                'UKM Futsal' => [
                    'Pengurus Inti' => [
                        'Ketua Umum',
                        'Wakil Ketua Umum',
                        'Sekretaris',
                        'Bendahara',
                        'Anggota',
                    ],
                    'Keanggotaan' => ['Lainnya'],
                ],

                'UKM Sanggar Tari Rossa Primula' => [
                    'Pengurus Inti' => [
                        'Ketua',
                        'Wakil Ketua',
                        'Sekretaris',
                        'Wakil Sekretaris',
                        'Bendahara',
                    ],
                    'Divisi' => [
                        'Koordinator Kegiatan',
                        'Personel Divisi Kegiatan',
                        'Koordinator Humas',
                        'Personel Divisi Humas',
                        'Koordinator Perkap',
                        'Personel Divisi Perkap',
                        'Anggota',
                    ],
                    'Keanggotaan' => ['Lainnya'],
                ],

                'UKM PUSWIRA' => [
                    'Pengurus Inti' => [
                        'Ketua',
                        'Wakil Ketua',
                        'Sekretaris',
                        'Bendahara',
                    ],
                    'Departemen' => [
                        'Kepala Departemen Humas',
                        'Personel Departemen Humas',
                        'Kepala Departemen Multimedia',
                        'Personel Departemen Multimedia',
                        'Anggota',
                    ],
                    'Keanggotaan' => ['Lainnya'],
                ],

                'UKM Pencak Silat' => [
                    'Pengurus Inti' => [
                        'Ketua Umum',
                        'Wakil Ketua Umum',
                        'Sekretaris',
                        'Bendahara I',
                        'Bendahara II',
                    ],
                    'Bidang' => [
                        'Anggota Bidang Organisasi dan Humas',
                        'Anggota Bidang Promosi dan Pemasaran (Media)',
                        'Anggota Bidang Perlengkapan',
                        'Anggota Bidang Kaderisasi dan Keanggotaan',
                        'Anggota',
                    ],
                    'Keanggotaan' => ['Lainnya'],
                ],

                'UKM Perkusi (Seni Musik)' => [
                    'Pengurus Inti' => [
                        'Ketua',
                        'Wakil Ketua',
                        'Sekretaris',
                        'Wakil Sekretaris',
                        'Bendahara',
                    ],
                    'Departemen' => [
                        'Koordinator Departemen Humas',
                        'Anggota Departemen Humas',
                        'Koordinator Departemen Multimedia',
                        'Anggota Departemen Multimedia',
                        'Koordinator Departemen Seni',
                        'Anggota Departemen Seni',
                        'Anggota',
                    ],
                    'Keanggotaan' => ['Lainnya'],
                ],

                'UKM PERKUSI (Komputer)' => [
                    'Pengurus Inti' => [
                        'Ketua',
                        'Wakil Ketua',
                        'Sekretaris',
                        'Bendahara',
                    ],
                    'Divisi' => [
                        'Koordinator Divisi IT & Jaringan',
                        'Koordinator Divisi Software / Coding',
                        'Anggota Divisi',
                        'Anggota',
                    ],
                    'Keanggotaan' => ['Lainnya'],
                ],

                'UKM Gerakan Pramuka Racana' => [
                    'Pengurus Inti' => [
                        'Ketua Dewan Racana (KDR)',
                        'Pemangku Adat',
                        'Kerani (Sekretaris)',
                        'Bendahara',
                        'Anggota Racana',
                    ],
                    'Keanggotaan' => ['Lainnya'],
                ],
            ],
        ];
    }

    public function register()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard.index');
        }
        $master = self::getMasterAcademicAndOrgData();
        return view('layouts.auth.register', [
            'fakultas'       => $master['fakultas'],
            'prodis'         => $master['prodis'],
            'organisasi'     => $master['organisasi'],
            'jabatan'        => $master['jabatan'],
            'jabatan_by_org' => $master['jabatan_by_org'],
        ]);
    }

    public function storeRegister(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users,email',
            'password'      => 'required|string|min:6|confirmed',
            'nim'           => 'nullable|string|max:50',
            'fakultas'      => 'nullable|string|max:255',
            'program_studi' => 'nullable|string|max:255',
            'organisasi'    => 'nullable',
            'jabatan'       => 'nullable|string|max:255',
            'jabatan_custom'=> 'nullable|string|max:255',
        ], [
            'name.required'      => 'Nama lengkap wajib diisi.',
            'email.required'     => 'Alamat email wajib diisi.',
            'email.email'        => 'Format alamat email tidak valid.',
            'email.unique'       => 'Email ini telah terdaftar, silakan gunakan email lain atau login.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal terdiri dari 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $organisasiVal = null;
        if ($request->has('organisasi')) {
            if (is_array($request->organisasi)) {
                $organisasiVal = implode(', ', array_filter(array_map('trim', $request->organisasi)));
            } else {
                $organisasiVal = trim($request->organisasi);
            }
        }

        // Tentukan jabatan: jika memilih 'Lainnya' dan mengisi jabatan_custom, gunakan custom
        $jabatanVal = $request->filled('jabatan') ? trim($request->jabatan) : null;
        if (($jabatanVal === 'Lainnya' || empty($jabatanVal)) && $request->filled('jabatan_custom')) {
            $jabatanVal = trim($request->jabatan_custom);
        }

        $user = User::create([
            'name'          => trim($request->name),
            'email'         => strtolower(trim($request->email)),
            'password'      => Hash::make($request->password),
            'role'          => 'mahasiswa',
            'nim'           => $request->filled('nim') ? trim($request->nim) : null,
            'fakultas'      => $request->filled('fakultas') ? trim($request->fakultas) : null,
            'program_studi' => $request->filled('program_studi') ? trim($request->program_studi) : null,
            'organisasi'    => !empty($organisasiVal) ? $organisasiVal : null,
            'jabatan'       => $jabatanVal,
            'is_active'     => true,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard.index')
            ->with('success', 'Pendaftaran berhasil! Selamat datang di SIPENA UIS, ' . $user->name . '.');
    }

    public function authenticate(AuthRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            /** @var \App\Models\User $user */
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Akun Anda saat ini dinonaktifkan. Silakan hubungi Administrator.',
                ])->onlyInput('email');
            }

            return redirect()->intended(route('dashboard.index'));
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function editPassword()
    {
        return view('pages.auth.change-password');
    }

    public function showChangePasswordForm()
    {
        return view('pages.auth.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->update([
            'password' => $request->password,
        ]);

        return redirect()->back()->with('success', 'Password Anda berhasil diperbarui.');
    }

    public function profile()
    {
        $user = Auth::user();
        $logoProdi = \App\Models\LogoProdi::getForUser($user);
        $master = self::getMasterAcademicAndOrgData();
        return view('pages.users.profile', [
            'user'           => $user,
            'logoProdi'      => $logoProdi,
            'fakultas'       => $master['fakultas'],
            'prodis'         => $master['prodis'],
            'organisasi'     => $master['organisasi'],
            'jabatan'        => $master['jabatan'],
            'jabatan_by_org' => $master['jabatan_by_org'],
        ]);
    }

    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'nim'            => ['nullable', 'string', 'max:50'],
            'fakultas'       => ['nullable', 'string', 'max:255'],
            'program_studi'  => ['nullable', 'string', 'max:255'],
            'organisasi'     => ['nullable'],
            'jabatan'        => ['nullable', 'string', 'max:255'],
            'jabatan_custom' => ['nullable', 'string', 'max:255'],
            'photo_profile'  => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'name.required'         => 'Nama lengkap wajib diisi.',
            'email.required'        => 'Alamat email wajib diisi.',
            'email.email'           => 'Format email tidak valid.',
            'email.unique'          => 'Email sudah terdaftar pada pengguna lain.',
            'photo_profile.image'   => 'File harus berupa gambar.',
            'photo_profile.mimes'   => 'Format foto harus berupa jpeg, png, jpg, atau webp.',
            'photo_profile.max'     => 'Ukuran foto profil maksimal 2 MB.',
        ]);

        if ($request->has('organisasi')) {
            if (is_array($request->organisasi)) {
                $validated['organisasi'] = implode(', ', array_filter(array_map('trim', $request->organisasi))) ?: null;
            } else {
                $validated['organisasi'] = trim($request->organisasi) ?: null;
            }
        } else {
            $validated['organisasi'] = null;
        }

        // Tentukan jabatan
        if ($request->filled('jabatan_custom') && ($request->input('jabatan') === 'Lainnya' || empty($request->input('jabatan')))) {
            $validated['jabatan'] = trim($request->input('jabatan_custom'));
        }
        unset($validated['jabatan_custom']);

        if ($request->boolean('remove_photo')) {
            if ($user->photo_profile && Storage::disk('public')->exists($user->photo_profile)) {
                Storage::disk('public')->delete($user->photo_profile);
            }
            $validated['photo_profile'] = null;
        } elseif ($request->hasFile('photo_profile')) {
            if ($user->photo_profile && Storage::disk('public')->exists($user->photo_profile)) {
                Storage::disk('public')->delete($user->photo_profile);
            }
            $file = $request->file('photo_profile');
            $fileName = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $validated['photo_profile'] = $file->storeAs('profiles', $fileName, 'public');
        }

        $user->update($validated);

        return redirect()->back()->with('success', 'Profil Anda berhasil diperbarui.');
    }
}
