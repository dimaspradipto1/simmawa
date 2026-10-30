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
        Schema::create('logo_prodis', function (Blueprint $table) {
            $table->id();
            $table->string('kategori')->default('Himpunan Mahasiswa (HIMA)');
            $table->string('program_studi')->nullable();
            $table->string('fakultas')->nullable();
            $table->string('nama_organisasi')->nullable();
            $table->string('singkatan')->nullable();
            $table->string('logo');
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logo_prodis');
    }
};
