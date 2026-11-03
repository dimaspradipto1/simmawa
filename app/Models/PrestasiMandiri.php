<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrestasiMandiri extends Model
{
    use HasFactory;

    protected $table = 'prestasi_mandiris';

    protected $fillable = [
        'level',
        'kategori',
        'nama_kompetisi',
        'nama_cabang',
        'peringkat',
        'nama_penyelenggara',
        'jumlah_pt_peserta',
        'kepesertaan',
        'bentuk',
        'url_kompetisi',
        'link_dokumen_sertifikat',
        'file_dokumen_sertifikat',
        'tanggal_sertifikat',
        'link_foto_upp',
        'link_dokumen_undangan',
        'link_dokumen_lpj',
        'file_dokumen_lpj',
        'no_hp',
        'file_pengajuan_reward',
        'file_ktm',
        'file_surat_tugas',
        'keterangan',
        'tahun',
        'pt',
        'status',
        'data_mahasiswa',
        'data_dosen',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_sertifikat' => 'date',
            'data_mahasiswa' => 'array',
            'data_dosen' => 'array',
        ];
    }
}
