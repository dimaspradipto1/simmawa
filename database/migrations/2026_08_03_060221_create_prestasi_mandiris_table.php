<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('prestasi_mandiris', function (Blueprint $table) {
            $table->id();
            $table->string('level');
            $table->string('kategori');
            $table->string('nama_kompetisi');
            $table->string('nama_cabang');
            $table->string('peringkat');
            $table->string('nama_penyelenggara');
            $table->integer('jumlah_pt_peserta')->nullable();
            $table->string('kepesertaan')->nullable();
            $table->string('bentuk')->nullable();
            $table->string('url_kompetisi')->nullable();
            $table->string('link_dokumen_sertifikat')->nullable();
            $table->date('tanggal_sertifikat')->nullable();
            $table->string('link_foto_upp')->nullable();
            $table->string('link_dokumen_undangan')->nullable();
            $table->string('link_dokumen_lpj')->nullable();
            $table->string('file_dokumen_lpj')->nullable();
            $table->text('keterangan')->nullable();
            $table->integer('tahun')->nullable();
            $table->string('pt')->default('Universitas Ibnu Sina');
            $table->string('status')->default('Terverifikasi');
            $table->json('data_mahasiswa')->nullable();
            $table->json('data_dosen')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestasi_mandiris');
    }
};
