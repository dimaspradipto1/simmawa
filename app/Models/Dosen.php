<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    protected $fillable = [
        'nidn_nuptk',
        'nama_dosen',
        'program_studi',
        'email',
        'no_hp',
        'url_surat_tugas',
        'status',
    ];
}
