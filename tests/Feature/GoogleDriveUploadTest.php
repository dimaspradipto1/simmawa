<?php

namespace Tests\Feature;

use App\Models\PrestasiMandiri;
use App\Models\User;
use App\Services\GoogleDriveService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GoogleDriveUploadTest extends TestCase
{
    public function test_google_drive_service_extracts_folder_ids_correctly()
    {
        $service = app(GoogleDriveService::class);

        // URL format 1: /folders/ID?usp=sharing
        $id1 = $service->extractFolderId('https://drive.google.com/drive/folders/1ohk-0KQOG7uVv-tAyaHwCmmsoYh4kCIZ?usp=sharing');
        $this->assertEquals('1ohk-0KQOG7uVv-tAyaHwCmmsoYh4kCIZ', $id1);

        // URL format 2: id=ID
        $id2 = $service->extractFolderId('https://drive.google.com/open?id=1oNOjROyglalRn38O7dJHYRordqoZfZtk');
        $this->assertEquals('1oNOjROyglalRn38O7dJHYRordqoZfZtk', $id2);

        // Raw ID format
        $id3 = $service->extractFolderId('1HxvdtB1bzNgP8XrEF-xVpllip5KTqoS_');
        $this->assertEquals('1HxvdtB1bzNgP8XrEF-xVpllip5KTqoS_', $id3);
    }

    public function test_google_drive_status_endpoint_returns_json()
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin_test@uis.ac.id'],
            [
                'name' => 'Admin Test',
                'password' => bcrypt('password'),
                'role' => 'superadmin',
            ]
        );

        $response = $this->actingAs($admin)->get('/google-drive/status');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'is_configured',
            'client_id_set',
            'client_secret_set',
            'refresh_token_valid',
            'folders',
        ]);
    }

    public function test_prestasi_mandiri_stores_files_with_graceful_fallback_when_drive_offline()
    {
        Storage::fake('public');

        $user = User::firstOrCreate(
            ['email' => 'mhs_drive_test@uis.ac.id'],
            [
                'name' => 'Mahasiswa Test Drive',
                'password' => bcrypt('password'),
                'role' => 'mahasiswa',
            ]
        );

        $fakeFile = UploadedFile::fake()->create('sertifikat.pdf', 100, 'application/pdf');

        $response = $this->actingAs($user)->post(route('prestasi-mandiri.store'), [
            'level' => 'Nasional',
            'kategori' => 'Olahraga',
            'nama_kompetisi' => 'Pekan Olahraga Mahasiswa',
            'nama_cabang' => 'Futsal',
            'peringkat' => 'Juara I',
            'nama_penyelenggara' => 'BAPOMI',
            'jumlah_pt_peserta' => 10,
            'kepesertaan' => 'Kelompok',
            'bentuk' => 'Luring',
            'url_kompetisi' => 'https://example.com/lomba',
            'tanggal_sertifikat' => '2026-05-10',
            'link_foto_upp' => 'https://example.com/upp',
            'link_dokumen_undangan' => 'https://example.com/undangan',
            'file_dokumen_sertifikat' => $fakeFile,
            'no_hp' => '081234567890',
            'data_mahasiswa' => [
                ['nim' => '210101001', 'nama' => 'Mahasiswa Test Drive'],
            ],
        ]);

        $response->assertRedirect(route('prestasi-mandiri.index'));
        $response->assertSessionHas('success');

        $record = PrestasiMandiri::where('nama_kompetisi', 'Pekan Olahraga Mahasiswa')->first();
        $this->assertNotNull($record);
        $this->assertNotEmpty($record->file_dokumen_sertifikat);

        // Clean up
        $record->delete();
    }

    public function test_download_file_redirects_for_google_drive_urls()
    {
        $user = User::firstOrCreate(
            ['email' => 'mhs_drive_test@uis.ac.id'],
            [
                'name' => 'Mahasiswa Test Drive',
                'password' => bcrypt('password'),
                'role' => 'mahasiswa',
            ]
        );

        $record = PrestasiMandiri::create([
            'level' => 'Nasional',
            'kategori' => 'Olahraga',
            'nama_kompetisi' => 'Kompetisi Drive Test',
            'nama_cabang' => 'Badminton',
            'peringkat' => 'Juara I',
            'nama_penyelenggara' => 'PBSI',
            'kepesertaan' => 'Individu',
            'bentuk' => 'Luring',
            'file_dokumen_sertifikat' => 'https://drive.google.com/file/d/1234567890abcdef/view',
            'data_mahasiswa' => [
                ['nim' => '210101001', 'nama' => 'Mahasiswa Test Drive'],
            ],
            'pt' => 'Universitas Ibnu Sina',
            'status' => 'Submitted',
        ]);

        $response = $this->actingAs($user)->get(route('prestasi-mandiri.download-file', [$record->id, 'sertifikat']));
        $response->assertRedirect('https://drive.google.com/file/d/1234567890abcdef/view');

        // Clean up
        $record->delete();
    }
}
