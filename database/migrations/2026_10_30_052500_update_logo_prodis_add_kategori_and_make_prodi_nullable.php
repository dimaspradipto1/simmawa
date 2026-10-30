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
        Schema::table('logo_prodis', function (Blueprint $table) {
            $table->string('kategori')->default('Himpunan Mahasiswa (HIMA)')->after('id');
            $table->string('program_studi')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logo_prodis', function (Blueprint $table) {
            $table->dropColumn('kategori');
            $table->string('program_studi')->nullable(false)->change();
        });
    }
};

