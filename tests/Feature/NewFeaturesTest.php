<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewFeaturesTest extends TestCase
{
    public function test_register_page_loads_successfully(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Daftar Akun Baru');
        $response->assertSee('Nomor WhatsApp / HP');
    }

    public function test_student_can_register_with_default_mahasiswa_role(): void
    {
        $uniqueEmail = 'mhs_' . time() . '@student.uis.ac.id';
        $response = $this->post('/register', [
            'name'                  => 'Budi Mahasiswa Baru',
            'email'                 => $uniqueEmail,
            'no_hp'                 => '081234567890',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard.index'));
        $this->assertAuthenticated();

        $user = User::where('email', $uniqueEmail)->first();
        $this->assertNotNull($user);
        $this->assertEquals('mahasiswa', $user->role);
        $this->assertEquals('Budi Mahasiswa Baru', $user->name);
        $this->assertTrue((bool)$user->is_active);

        // Clean up
        $user->delete();
    }

    public function test_rekapitulasi_pdf_and_excel_exports_render_new_columns(): void
    {
        // Login as superadmin or admin
        $admin = User::firstOrCreate(
            ['email' => 'admin_test@uis.ac.id'],
            [
                'name'      => 'Admin Test',
                'password'  => bcrypt('password'),
                'role'      => 'superadmin',
                'is_active' => true,
            ]
        );

        $this->actingAs($admin);

        // Test PDF Export
        $pdfResponse = $this->get(route('rekapitulasi.pdf', ['tahun' => 'all']));
        $pdfResponse->assertStatus(200);
        $pdfResponse->assertSee('KATEGORI');
        $pdfResponse->assertSee('KEPESERTAAN');
        $pdfResponse->assertSee('TGL SERTIFIKAT');

        // Test Excel Export
        $excelResponse = $this->get(route('rekapitulasi.excel', ['tahun' => 'all']));
        $excelResponse->assertStatus(200);
        $excelResponse->assertSee('KATEGORI');
        $excelResponse->assertSee('KEPESERTAAN');
        $excelResponse->assertSee('TANGGAL SERTIFIKAT');

        // Clean up
        $admin->delete();
    }

    public function test_rekapitulasi_without_filter_shows_all_data_and_filtered_when_filter_applied(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin_rekap_test@uis.ac.id'],
            [
                'name'      => 'Admin Rekap Filter Test',
                'password'  => bcrypt('password'),
                'role'      => 'superadmin',
                'is_active' => true,
            ]
        );

        $this->actingAs($admin);

        // 1. Without query params (no filter selected):
        // Web index, PDF, and Excel should have selectedTahun = 'all' and return all data
        $indexResponse = $this->get(route('rekapitulasi.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertViewHas('selectedTahun', 'all');
        $indexResponse->assertViewHas('selectedProdi', 'all');

        $pdfResponseNoFilter = $this->get(route('rekapitulasi.pdf'));
        $pdfResponseNoFilter->assertStatus(200);
        $pdfResponseNoFilter->assertSee('Semua Tahun');

        $excelResponseNoFilter = $this->get(route('rekapitulasi.excel'));
        $excelResponseNoFilter->assertStatus(200);
        $excelResponseNoFilter->assertSee('Semua Tahun');

        // 2. With filter applied (e.g. specific prodi and year):
        $filteredPdf = $this->get(route('rekapitulasi.pdf', ['tahun' => 2026, 'prodi' => 'S1 - Teknik Informatika']));
        $filteredPdf->assertStatus(200);
        $filteredPdf->assertSee('Tahun 2026');
        $filteredPdf->assertSee('S1 - Teknik Informatika');

        $admin->delete();
    }

    public function test_standardized_prodi_names_are_available(): void
    {
        $officialProdis = \App\Http\Controllers\RekapitulasiController::getOfficialProdiList();
        $this->assertContains('S1 - Teknik Informatika', $officialProdis);
        $this->assertContains('S1 - Sistem Informasi', $officialProdis);
        $this->assertContains('S1 - Teknik Industri', $officialProdis);
        $this->assertContains('S1 - Teknik Logistik', $officialProdis);
        $this->assertContains('S1 - Teknik Perkapalan', $officialProdis);
        $this->assertContains('S1 - Manajemen', $officialProdis);
        $this->assertContains('S1 - Akuntansi', $officialProdis);
        $this->assertContains('S1 - Kesehatan Masyarakat', $officialProdis);
    }

    public function test_template_lpj_is_hidden_and_blocked_for_mahasiswa(): void
    {
        $mhs = User::firstOrCreate(
            ['email' => 'mhs_sidebar_test@student.uis.ac.id'],
            [
                'name'      => 'Mahasiswa Sidebar Test',
                'password'  => bcrypt('password'),
                'role'      => 'mahasiswa',
                'is_active' => true,
            ]
        );

        $this->actingAs($mhs);

        // Sidebar should NOT display Template LPJ link for mahasiswa
        $dashResponse = $this->get(route('dashboard.index'));
        $dashResponse->assertStatus(200);
        $dashResponse->assertDontSee(route('template-lpj.index'));

        // Accessing /template-lpj directly should return 403 Forbidden
        $lpjResponse = $this->get(route('template-lpj.index'));
        $lpjResponse->assertStatus(403);

        $mhs->delete();
    }

    public function test_mahasiswa_only_sees_and_accesses_own_records(): void
    {
        $studentA = User::firstOrCreate(
            ['email' => 'student_a@student.uis.ac.id'],
            [
                'name'      => 'Siti Mahasiswa A',
                'password'  => bcrypt('password'),
                'role'      => 'mahasiswa',
                'is_active' => true,
            ]
        );

        $studentB = User::firstOrCreate(
            ['email' => 'student_b@student.uis.ac.id'],
            [
                'name'      => 'Joko Mahasiswa B',
                'password'  => bcrypt('password'),
                'role'      => 'mahasiswa',
                'is_active' => true,
            ]
        );

        // Record owned by Student A
        $recordA = \App\Models\PrestasiMandiri::create([
            'level'              => 'Nasional',
            'kategori'           => 'Sains',
            'nama_kompetisi'     => 'Lomba Sains Nasional Mahasiswa A',
            'nama_cabang'        => 'Fisika Terapan',
            'peringkat'          => 'Juara I',
            'nama_penyelenggara' => 'DIKTI',
            'kepesertaan'        => 'Individu',
            'bentuk'             => 'Daring',
            'tahun'              => 2026,
            'status'             => 'Submitted',
            'data_mahasiswa'     => [
                ['nim' => '11223344', 'nama' => 'Siti Mahasiswa A', 'prodi' => 'S1 - Teknik Informatika']
            ],
        ]);

        // When Student B attempts to access Student A's record
        $this->actingAs($studentB);

        $showResponse = $this->get(route('prestasi-mandiri.show', $recordA->id));
        $showResponse->assertStatus(403);

        $editResponse = $this->get(route('prestasi-mandiri.edit', $recordA->id));
        $editResponse->assertStatus(403);

        $deleteResponse = $this->delete(route('prestasi-mandiri.destroy', $recordA->id));
        $deleteResponse->assertStatus(403);

        // When Student A accesses their own record
        $this->actingAs($studentA);

        $ownShowResponse = $this->get(route('prestasi-mandiri.show', $recordA->id));
        $ownShowResponse->assertStatus(200);

        $ownEditResponse = $this->get(route('prestasi-mandiri.edit', $recordA->id));
        $ownEditResponse->assertStatus(200);

        // Clean up
        $recordA->delete();
        $studentA->delete();
        $studentB->delete();
    }

    public function test_action_buttons_are_standardized_and_styled()
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin_btn_test@uis.ac.id'],
            [
                'name' => 'Admin Button Test',
                'password' => bcrypt('password'),
                'role' => 'superadmin',
                'is_active' => true,
            ]
        );

        $this->actingAs($admin);

        // Verify index pages load correctly with action styling
        $menus = [
            route('prestasi-mandiri.index'),
            route('prestasi-belmawa.index'),
            route('kejuaraan.index'),
            route('rekognisi.index'),
            route('sertifikasi.index'),
            route('template-lpj.index'),
            route('institusi.index'),
            route('dosen.index'),
            route('users.index'),
        ];

        foreach ($menus as $url) {
            $response = $this->get($url);
            $response->assertStatus(200);
        }

        $admin->delete();
    }

    public function test_prestasi_mandiri_peringkat_options_include_new_ranks()
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin_rank_test@uis.ac.id'],
            [
                'name' => 'Admin Rank Test',
                'password' => bcrypt('password'),
                'role' => 'superadmin',
                'is_active' => true,
            ]
        );

        $this->actingAs($admin);

        $response = $this->get(route('prestasi-mandiri.create'));
        $response->assertStatus(200);
        $response->assertSee('Juara Umum');
        $response->assertSee('Apresiasi Kejuaraan');
        $response->assertSee('Penghargaan');

        $admin->delete();
    }

    public function test_dashboard_displays_kta_summary_for_mahasiswa_and_admin(): void
    {
        // 1. Test as Mahasiswa
        $student = User::firstOrCreate(
            ['email' => 'mhs_kta_dash_test@uis.ac.id'],
            [
                'name' => 'Mhs KTA Dash Test',
                'password' => bcrypt('password'),
                'role' => 'mahasiswa',
                'organisasi' => 'BEM Universitas Ibnu Sina',
                'jabatan' => 'Anggota',
                'is_active' => true,
            ]
        );

        $this->actingAs($student);
        $resStudent = $this->get(route('dashboard.index'));
        $resStudent->assertStatus(200);
        $resStudent->assertSee('Ringkasan KTA Ormawa Mahasiswa');
        $resStudent->assertSee('KTA Ormawa Anda');

        // 2. Test as Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin_kta_dash_test@uis.ac.id'],
            [
                'name' => 'Admin KTA Dash Test',
                'password' => bcrypt('password'),
                'role' => 'superadmin',
                'is_active' => true,
            ]
        );

        $this->actingAs($admin);
        $resAdmin = $this->get(route('dashboard.index'));
        $resAdmin->assertStatus(200);
        $resAdmin->assertSee('Mahasiswa Ber-KTA');
        $resAdmin->assertSee('Mahasiswa Pemilik KTA Ormawa');

        $student->delete();
        $admin->delete();
    }
}
