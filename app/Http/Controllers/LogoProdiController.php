<?php

namespace App\Http\Controllers;

use App\DataTables\LogoProdiDataTable;
use App\Models\LogoProdi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LogoProdiController extends Controller
{
    /**
     * Master data kategori, program studi & fakultas bawaan UIS.
     */
    protected function getMasterData(): array
    {
        return [
            'kategori' => [
                'Himpunan Mahasiswa (HIMA)'        => 'Himpunan Mahasiswa Jurusan (HIMA)',
                'Lembaga Mahasiswa (BEM / DPM)'    => 'Lembaga Mahasiswa (BEM / DPM Universitas)',
                'Unit Kegiatan Mahasiswa (UKM)'    => 'Unit Kegiatan Mahasiswa (UKM)',
                'Lembaga Fakultas'                 => 'Lembaga Tingkat Fakultas (BEM-F / DPM-F)',
            ],
            'prodis' => [
                'Semua Program Studi (Tingkat Universitas)' => 'Semua Program Studi (Tingkat Universitas)',
                'S1 - Teknik Informatika'              => 'S1 - Teknik Informatika',
                'S1 - Sistem Informasi'                => 'S1 - Sistem Informasi',
                'S1 - Teknik Industri'                 => 'S1 - Teknik Industri',
                'S1 - Teknik Logistik'                 => 'S1 - Teknik Logistik',
                'S1 - Teknik Perkapalan'               => 'S1 - Teknik Perkapalan',
                'S1 - Manajemen'                       => 'S1 - Manajemen',
                'S1 - Akuntansi'                       => 'S1 - Akuntansi',
                'S1 - Kesehatan Masyarakat'            => 'S1 - Kesehatan Masyarakat',
                'S1 - Kesehatan dan Keselamatan Kerja' => 'S1 - Kesehatan dan Keselamatan Kerja',
                'S1 - Kesehatan Lingkungan'            => 'S1 - Kesehatan Lingkungan',
                'S2 - Magister Manajemen'              => 'S2 - Magister Manajemen',
                'S2 - Magister Kesehatan Masyarakat'   => 'S2 - Magister Kesehatan Masyarakat',
            ],
            'fakultas' => [
                'Tingkat Universitas'                   => 'Tingkat Universitas (Lintas Fakultas)',
                'Fakultas Sains dan Teknologi'          => 'Fakultas Sains dan Teknologi',
                'Fakultas Ekonomi dan Bisnis'           => 'Fakultas Ekonomi dan Bisnis',
                'Fakultas Ilmu Kesehatan'               => 'Fakultas Ilmu Kesehatan',
                'Pascasarjana'                          => 'Pascasarjana',
            ],
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(LogoProdiDataTable $dataTable)
    {
        return $dataTable->render('pages.logo-prodi.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $master = $this->getMasterData();
        return view('pages.logo-prodi.create', [
            'kategori' => $master['kategori'],
            'prodis'   => $master['prodis'],
            'fakultas' => $master['fakultas'],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori'        => ['required', 'string', 'max:255'],
            'program_studi'   => ['nullable', 'string', 'max:255'],
            'fakultas'        => ['nullable', 'string', 'max:255'],
            'nama_organisasi' => ['required', 'string', 'max:255'],
            'singkatan'       => ['nullable', 'string', 'max:50'],
            'logo'            => ['required', 'image', 'mimes:png,jpg,jpeg,webp,svg', 'max:2048'],
            'keterangan'      => ['nullable', 'string', 'max:1000'],
            'is_active'       => ['nullable', 'boolean'],
        ], [
            'kategori.required'        => 'Kategori organisasi wajib dipilih.',
            'nama_organisasi.required' => 'Nama organisasi / hima / lembaga wajib diisi.',
            'logo.required'            => 'File logo wajib diunggah.',
            'logo.image'               => 'File harus berupa gambar.',
            'logo.mimes'               => 'Format file harus berupa PNG, JPG, JPEG, WEBP, atau SVG.',
            'logo.max'                 => 'Ukuran file logo maksimal 2 MB.',
        ]);

        // Default prodi & fakultas untuk Lembaga / UKM jika dikosongkan
        if (in_array($validated['kategori'], ['Lembaga Mahasiswa (BEM / DPM)', 'Unit Kegiatan Mahasiswa (UKM)'])) {
            if (empty($validated['fakultas'])) {
                $validated['fakultas'] = 'Tingkat Universitas';
            }
            if (empty($validated['program_studi'])) {
                $validated['program_studi'] = 'Semua Program Studi (Tingkat Universitas)';
            }
        }

        $file = $request->file('logo');
        $fileName = 'logo_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $validated['logo'] = $file->storeAs('logo-prodi', $fileName, 'public');
        $validated['is_active'] = $request->boolean('is_active', true);

        LogoProdi::create($validated);

        return redirect()->route('logo-prodi.index')
            ->with('success', 'Logo Organisasi / Program Studi berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LogoProdi $logoProdi)
    {
        $master = $this->getMasterData();
        return view('pages.logo-prodi.edit', [
            'logoProdi' => $logoProdi,
            'kategori'  => $master['kategori'],
            'prodis'    => $master['prodis'],
            'fakultas'  => $master['fakultas'],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LogoProdi $logoProdi)
    {
        $validated = $request->validate([
            'kategori'        => ['required', 'string', 'max:255'],
            'program_studi'   => ['nullable', 'string', 'max:255'],
            'fakultas'        => ['nullable', 'string', 'max:255'],
            'nama_organisasi' => ['required', 'string', 'max:255'],
            'singkatan'       => ['nullable', 'string', 'max:50'],
            'logo'            => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp,svg', 'max:2048'],
            'keterangan'      => ['nullable', 'string', 'max:1000'],
            'is_active'       => ['nullable', 'boolean'],
        ], [
            'kategori.required'        => 'Kategori organisasi wajib dipilih.',
            'nama_organisasi.required' => 'Nama organisasi / hima / lembaga wajib diisi.',
            'logo.image'               => 'File harus berupa gambar.',
            'logo.mimes'               => 'Format file harus berupa PNG, JPG, JPEG, WEBP, atau SVG.',
            'logo.max'                 => 'Ukuran file logo maksimal 2 MB.',
        ]);

        if (in_array($validated['kategori'], ['Lembaga Mahasiswa (BEM / DPM)', 'Unit Kegiatan Mahasiswa (UKM)'])) {
            if (empty($validated['fakultas'])) {
                $validated['fakultas'] = 'Tingkat Universitas';
            }
            if (empty($validated['program_studi'])) {
                $validated['program_studi'] = 'Semua Program Studi (Tingkat Universitas)';
            }
        }

        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($logoProdi->logo && Storage::disk('public')->exists($logoProdi->logo)) {
                Storage::disk('public')->delete($logoProdi->logo);
            }
            $file = $request->file('logo');
            $fileName = 'logo_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $validated['logo'] = $file->storeAs('logo-prodi', $fileName, 'public');
        } else {
            unset($validated['logo']);
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        $logoProdi->update($validated);

        return redirect()->route('logo-prodi.index')
            ->with('success', 'Logo Organisasi / Program Studi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, LogoProdi $logoProdi)
    {
        if ($logoProdi->logo && Storage::disk('public')->exists($logoProdi->logo)) {
            Storage::disk('public')->delete($logoProdi->logo);
        }

        $logoProdi->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Logo Organisasi / Program Studi berhasil dihapus.']);
        }

        return redirect()->route('logo-prodi.index')
            ->with('success', 'Logo Organisasi / Program Studi berhasil dihapus.');
    }
}
