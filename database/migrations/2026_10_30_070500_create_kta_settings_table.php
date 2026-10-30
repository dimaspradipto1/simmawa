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
        Schema::create('kta_settings', function (Blueprint $table) {
            $table->id();
            $table->string('tahun_periode')->default('2026 – 2027');
            $table->string('label_periode')->default('PERIODE');
            $table->string('label_masa_berlaku')->default('Masa Berlaku:');
            $table->string('teks_masa_berlaku')->default('Tahun Kepengurusan 2026 – 2027');
            $table->string('nama_universitas')->default('UNIVERSITAS IBNU SINA');
            $table->string('slogan_bawah')->default('Unggul • Berintegritas • Berjiwa Entrepreneur');
            $table->string('background_card')->nullable();
            $table->text('catatan_keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kta_settings');
    }
};
