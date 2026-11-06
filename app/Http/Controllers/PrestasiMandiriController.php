<?php

namespace App\Http\Controllers;

use App\DataTables\PrestasiMandiriDataTable;
use App\Models\PrestasiMandiri;
use App\Models\TemplateLpj;
use App\Services\GoogleDriveService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PrestasiMandiriController extends Controller
{
    public function __construct(
        protected GoogleDriveService $driveService
    ) {}
    /**
     * Helper to get dropdown options matching SIMKATMAWA specs
     */
    private function getFormOptions(): array
    {
        return [
            'levels' => [
                'Kabupaten/Kota' => 'Kabupaten/Kota',
                'Provinsi'       => 'Provinsi',
                'Nasional'       => 'Nasional',
                'Internasional'  => 'Internasional',
            ],
            'kategoris' => [
                'Seni dan Budaya'                      => 'Seni dan Budaya',
                'Olahraga'                             => 'Olahraga',
                'Sains, Teknologi dan Inovasi / SSI'   => 'Sains, Teknologi dan Inovasi / SSI',
                'Keagamaan'                            => 'Keagamaan',
                'Wirausaha'                            => 'Wirausaha',
                'Lainnya'                              => 'Lainnya',
            ],
            'peringkats' => [
                'Juara Umum'                                     => 'Juara Umum',
                'Juara I'                                        => 'Juara I',
                'Juara II'                                       => 'Juara II',
                'Juara III'                                      => 'Juara III',
                'Harapan I'                                      => 'Harapan I',
                'Harapan II'                                     => 'Harapan II',
                'Harapan III'                                    => 'Harapan III',
                'Apresiasi Kejuaraan'                            => 'Apresiasi Kejuaraan',
                'Penghargaan'                                    => 'Penghargaan',
                'Apresiasi Kejuaraan / Penghargaan / Juara Umum' => 'Apresiasi Kejuaraan / Penghargaan / Juara Umum',
                'Peserta / Finalis'                              => 'Peserta / Finalis',
            ],
            'kepesertaans' => [
                'Individu' => 'Individu',
                'Kelompok' => 'Kelompok',
            ],
            'bentuks' => [
                'Luring' => 'Luring',
                'Daring' => 'Daring',
                'Hybrid' => 'Hybrid',
            ],
        ];
    }

    /**
     * Download Template Dokumen LPJ (.docx / custom link) Prestasi Mandiri
     */
    public function downloadTemplateLpj()
    {
        $activeTemplate = TemplateLpj::getActiveTemplate('Prestasi Mandiri');

        if ($activeTemplate) {
            if ($activeTemplate->tipe === 'link' && !empty($activeTemplate->url_link)) {
                return redirect()->away($activeTemplate->url_link);
            }

            if (!empty($activeTemplate->file_path)) {
                if (Storage::disk('public')->exists($activeTemplate->file_path)) {
                    $downloadName = $activeTemplate->file_name ?? basename($activeTemplate->file_path);
                    return Storage::disk('public')->download($activeTemplate->file_path, $downloadName);
                }

                $publicPath = public_path($activeTemplate->file_path);
                if (file_exists($publicPath)) {
                    $downloadName = $activeTemplate->file_name ?? basename($publicPath);
                    return response()->download($publicPath, $downloadName);
                }
            }

            if (!empty($activeTemplate->url_link)) {
                return redirect()->away($activeTemplate->url_link);
            }
        }

        $fallbackPath = public_path('templates/template_lpj_prestasi_mandiri.docx');
        if (file_exists($fallbackPath)) {
            return response()->download($fallbackPath, 'Template_LPJ_Prestasi_Mandiri.docx', [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ]);
        }

        abort(404, 'File template LPJ tidak ditemukan.');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(PrestasiMandiriDataTable $dataTable)
    {
        return $dataTable->render('pages.prestasi-mandiri.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $options = $this->getFormOptions();
        $prestasiMandiri = new PrestasiMandiri();
        $activeTemplateLpj = TemplateLpj::getActiveTemplate('Prestasi Mandiri');
        $activeTemplatesLpj = TemplateLpj::getActiveTemplates('Prestasi Mandiri');
        $isDriveReady = $this->driveService->isConfigured();
        return view('pages.prestasi-mandiri.create', compact('options', 'prestasiMandiri', 'activeTemplateLpj', 'activeTemplatesLpj', 'isDriveReady'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'level'                   => ['required', 'string'],
            'kategori'                => ['required', 'string'],
            'nama_kompetisi'          => ['required', 'string', 'max:255'],
            'nama_cabang'             => ['required', 'string', 'max:255'],
            'peringkat'               => ['required', 'string'],
            'nama_penyelenggara'      => ['required', 'string', 'max:255'],
            'jumlah_pt_peserta'       => ['nullable', 'integer', 'min:1'],
            'kepesertaan'             => ['required', 'string'],
            'bentuk'                  => ['required', 'string'],
            'url_kompetisi'           => ['nullable', 'url', 'max:255'],
            'link_dokumen_sertifikat' => ['nullable', 'string', 'max:255'],
            'file_dokumen_sertifikat' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:20480'],
            'tanggal_sertifikat'      => ['nullable', 'date'],
            'link_foto_upp'           => ['nullable', 'string', 'max:255'],
            'file_foto_upp'           => ['nullable', 'file', 'mimes:png,jpg,jpeg', 'max:10240'],
            'link_dokumen_undangan'   => ['nullable', 'url', 'max:255'],
            'link_dokumen_lpj'        => ['nullable', 'url', 'max:255'],
            'file_dokumen_lpj'        => ['nullable', 'file', 'mimes:pdf,doc,docx,zip', 'max:20480'],
            'no_hp'                   => ['nullable', 'string', 'max:30'],
            'file_pengajuan_reward'   => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:20480'],
            'file_ktm'                => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'file_surat_tugas'        => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:20480'],
            'keterangan'              => ['nullable', 'string'],
            'data_mahasiswa'          => ['nullable', 'array'],
            'data_mahasiswa.*.nim'    => ['nullable', 'string'],
            'data_mahasiswa.*.nama'   => ['nullable', 'string'],
            'data_dosen'              => ['nullable', 'array'],
            'data_dosen.*.nidn'       => ['nullable', 'string'],
            'data_dosen.*.nama'       => ['nullable', 'string'],
            'data_dosen.*.url_surat'  => ['nullable', 'string'],
        ], [
            'level.required'            => 'Level wajib dipilih.',
            'kategori.required'         => 'Kategori wajib dipilih.',
            'nama_kompetisi.required'   => 'Nama Kompetisi wajib diisi.',
            'nama_cabang.required'      => 'Nama Cabang wajib diisi.',
            'peringkat.required'        => 'Peringkat wajib dipilih.',
            'nama_penyelenggara.required' => 'Nama Penyelenggara wajib diisi.',
            'file_dokumen_lpj.mimes'    => 'Format file LPJ harus berupa .pdf, .doc, .docx, atau .zip.',
            'file_dokumen_lpj.max'      => 'Ukuran file LPJ maksimal 20 MB.',
            'file_foto_upp.mimes'       => 'Format file Foto UPP harus berupa .png, .jpg, atau .jpeg.',
            'file_foto_upp.max'         => 'Ukuran file Foto UPP maksimal 10 MB.',
        ]);

        $mahasiswa = array_values(array_filter($request->input('data_mahasiswa', []), function ($item) {
            return !empty($item['nim']) || !empty($item['nama']);
        }));

        $dosen = array_values(array_filter($request->input('data_dosen', []), function ($item) {
            return !empty($item['nidn']) || !empty($item['nama']);
        }));

        $user = auth()->user();
        if ($user && $user->role === 'mahasiswa') {
            $hasCurrentStudent = false;
            foreach ($mahasiswa as $mhs) {
                if (!empty($mhs['nama']) && strcasecmp(trim($mhs['nama']), trim($user->name)) === 0) {
                    $hasCurrentStudent = true;
                    break;
                }
            }
            if (!$hasCurrentStudent) {
                $mahasiswa[] = [
                    'nama'  => $user->name,
                    'nim'   => $request->input('nim', ''),
                    'prodi' => 'Teknik Informatika'
                ];
            }
        }

        $submitterName = auth()->check() ? auth()->user()->name : ($user?->name ?? (!empty($mahasiswa[0]['nama']) ? $mahasiswa[0]['nama'] : 'user'));

        if ($request->hasFile('file_dokumen_sertifikat')) {
            $file = $request->file('file_dokumen_sertifikat');
            $upload = $this->driveService->upload($file, 'GOOGLE_DRIVE_UPLOAD_FILE_SERTIFIKAT', 'sertifikat', $submitterName);
            $validated['file_dokumen_sertifikat'] = $upload['url'];
        }

        if ($request->hasFile('file_dokumen_lpj')) {
            $file = $request->file('file_dokumen_lpj');
            $upload = $this->driveService->upload($file, 'GOOGLE_DRIVE_UPLOAD_FILE_LPJ', 'dokumen_lpj', $submitterName);
            $validated['file_dokumen_lpj'] = $upload['url'];
        }

        if ($request->hasFile('file_pengajuan_reward')) {
            $file = $request->file('file_pengajuan_reward');
            $upload = $this->driveService->upload($file, 'GOOGLE_DRIVE_UPLOAD_SURAT_PENGAJUAN_REWARD_PRESTASI', 'surat_reward', $submitterName);
            $validated['file_pengajuan_reward'] = $upload['url'];
        }

        if ($request->hasFile('file_ktm')) {
            $file = $request->file('file_ktm');
            $upload = $this->driveService->upload($file, 'GOOGLE_DRIVE_UPLOAD_KTM', 'ktm', $submitterName);
            $validated['file_ktm'] = $upload['url'];
        }

        if ($request->hasFile('file_surat_tugas')) {
            $file = $request->file('file_surat_tugas');
            $upload = $this->driveService->upload($file, 'GOOGLE_DRIVE_UPLOAD_SURAT_WRIII_ATAU_WDI', 'surat_tugas', $submitterName);
            $validated['file_surat_tugas'] = $upload['url'];
        }

        if ($request->hasFile('file_foto_upp')) {
            $file = $request->file('file_foto_upp');
            $upload = $this->driveService->upload($file, 'GOOGLE_DRIVE_UPLOAD_FOTO_UPP', 'foto_upp', $submitterName);
            $validated['file_foto_upp'] = $upload['url'];
            $validated['link_foto_upp'] = $upload['url'];
        }

        $validated['data_mahasiswa'] = $mahasiswa;
        $validated['data_dosen'] = $dosen;
        $validated['tahun'] = !empty($validated['tanggal_sertifikat']) 
            ? Carbon::parse($validated['tanggal_sertifikat'])->year 
            : date('Y');
        $validated['pt'] = 'Universitas Ibnu Sina';
        $validated['status'] = (auth()->check() && in_array(auth()->user()->role, ['superadmin', 'adminbkak']))
            ? ($request->input('status', 'Terverifikasi'))
            : 'Submitted';

        PrestasiMandiri::create($validated);

        return redirect()->route('prestasi-mandiri.index')
            ->with('success', 'Data Prestasi Mandiri terpadu berhasil disimpan.');
    }

    /**
     * Authorize that the current student owns or is a member of the record
     */
    private function authorizeStudentAccess(PrestasiMandiri $prestasiMandiri): void
    {
        $user = auth()->user();
        if ($user && $user->role === 'mahasiswa') {
            $studentName = strtolower(trim($user->name));
            $isOwner = false;
            if (!empty($prestasiMandiri->data_mahasiswa) && is_array($prestasiMandiri->data_mahasiswa)) {
                foreach ($prestasiMandiri->data_mahasiswa as $mhs) {
                    if (!empty($mhs['nama']) && str_contains(strtolower($mhs['nama']), $studentName)) {
                        $isOwner = true;
                        break;
                    }
                }
            }
            if (!$isOwner) {
                abort(403, 'Akses tidak diizinkan. Anda hanya dapat melihat dan mengelola data prestasi Anda sendiri.');
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PrestasiMandiri $prestasiMandiri)
    {
        $this->authorizeStudentAccess($prestasiMandiri);
        return view('pages.prestasi-mandiri.show', compact('prestasiMandiri'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PrestasiMandiri $prestasiMandiri)
    {
        $this->authorizeStudentAccess($prestasiMandiri);
        $options = $this->getFormOptions();
        $activeTemplateLpj = TemplateLpj::getActiveTemplate('Prestasi Mandiri');
        $activeTemplatesLpj = TemplateLpj::getActiveTemplates('Prestasi Mandiri');
        $isDriveReady = $this->driveService->isConfigured();
        return view('pages.prestasi-mandiri.edit', compact('options', 'prestasiMandiri', 'activeTemplateLpj', 'activeTemplatesLpj', 'isDriveReady'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PrestasiMandiri $prestasiMandiri)
    {
        $this->authorizeStudentAccess($prestasiMandiri);

        $validated = $request->validate([
            'level'                   => ['required', 'string'],
            'kategori'                => ['required', 'string'],
            'nama_kompetisi'          => ['required', 'string', 'max:255'],
            'nama_cabang'             => ['required', 'string', 'max:255'],
            'peringkat'               => ['required', 'string'],
            'nama_penyelenggara'      => ['required', 'string', 'max:255'],
            'jumlah_pt_peserta'       => ['nullable', 'integer', 'min:1'],
            'kepesertaan'             => ['required', 'string'],
            'bentuk'                  => ['required', 'string'],
            'url_kompetisi'           => ['nullable', 'url', 'max:255'],
            'link_dokumen_sertifikat' => ['nullable', 'string', 'max:255'],
            'file_dokumen_sertifikat' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:20480'],
            'tanggal_sertifikat'      => ['nullable', 'date'],
            'link_foto_upp'           => ['nullable', 'string', 'max:255'],
            'file_foto_upp'           => ['nullable', 'file', 'mimes:png,jpg,jpeg', 'max:10240'],
            'link_dokumen_undangan'   => ['nullable', 'url', 'max:255'],
            'link_dokumen_lpj'        => ['nullable', 'url', 'max:255'],
            'file_dokumen_lpj'        => ['nullable', 'file', 'mimes:pdf,doc,docx,zip', 'max:20480'],
            'no_hp'                   => ['nullable', 'string', 'max:30'],
            'file_pengajuan_reward'   => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:20480'],
            'file_ktm'                => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'file_surat_tugas'        => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:20480'],
            'keterangan'              => ['nullable', 'string'],
            'data_mahasiswa'          => ['nullable', 'array'],
            'data_dosen'              => ['nullable', 'array'],
        ], [
            'level.required'            => 'Level wajib dipilih.',
            'kategori.required'         => 'Kategori wajib dipilih.',
            'nama_kompetisi.required'   => 'Nama Kompetisi wajib diisi.',
            'nama_cabang.required'      => 'Nama Cabang wajib diisi.',
            'peringkat.required'        => 'Peringkat wajib dipilih.',
            'nama_penyelenggara.required' => 'Nama Penyelenggara wajib diisi.',
            'file_dokumen_lpj.mimes'    => 'Format file LPJ harus berupa .pdf, .doc, .docx, atau .zip.',
            'file_dokumen_lpj.max'      => 'Ukuran file LPJ maksimal 20 MB.',
            'file_foto_upp.mimes'       => 'Format file Foto UPP harus berupa .png, .jpg, atau .jpeg.',
            'file_foto_upp.max'         => 'Ukuran file Foto UPP maksimal 10 MB.',
        ]);

        $mahasiswa = array_values(array_filter($request->input('data_mahasiswa', []), function ($item) {
            return !empty($item['nim']) || !empty($item['nama']);
        }));

        $dosen = array_values(array_filter($request->input('data_dosen', []), function ($item) {
            return !empty($item['nidn']) || !empty($item['nama']);
        }));

        $user = auth()->user();
        if ($user && $user->role === 'mahasiswa') {
            $hasCurrentStudent = false;
            foreach ($mahasiswa as $mhs) {
                if (!empty($mhs['nama']) && str_contains(strtolower($mhs['nama']), strtolower(trim($user->name)))) {
                    $hasCurrentStudent = true;
                    break;
                }
            }
            if (!$hasCurrentStudent) {
                $mahasiswa[] = [
                    'nama'  => $user->name,
                    'nim'   => $request->input('nim', ''),
                    'prodi' => 'S1 - Teknik Informatika'
                ];
            }
        }

        $submitterName = auth()->check() ? auth()->user()->name : ($user?->name ?? (!empty($mahasiswa[0]['nama']) ? $mahasiswa[0]['nama'] : 'user'));

        if ($request->hasFile('file_dokumen_sertifikat')) {
            if ($prestasiMandiri->file_dokumen_sertifikat) {
                $this->driveService->deleteFile($prestasiMandiri->file_dokumen_sertifikat);
            }
            $file = $request->file('file_dokumen_sertifikat');
            $upload = $this->driveService->upload($file, 'GOOGLE_DRIVE_UPLOAD_FILE_SERTIFIKAT', 'sertifikat', $submitterName);
            $validated['file_dokumen_sertifikat'] = $upload['url'];
        }

        if ($request->hasFile('file_dokumen_lpj')) {
            if ($prestasiMandiri->file_dokumen_lpj) {
                $this->driveService->deleteFile($prestasiMandiri->file_dokumen_lpj);
            }
            $file = $request->file('file_dokumen_lpj');
            $upload = $this->driveService->upload($file, 'GOOGLE_DRIVE_UPLOAD_FILE_LPJ', 'dokumen_lpj', $submitterName);
            $validated['file_dokumen_lpj'] = $upload['url'];
        }

        if ($request->hasFile('file_pengajuan_reward')) {
            if ($prestasiMandiri->file_pengajuan_reward) {
                $this->driveService->deleteFile($prestasiMandiri->file_pengajuan_reward);
            }
            $file = $request->file('file_pengajuan_reward');
            $upload = $this->driveService->upload($file, 'GOOGLE_DRIVE_UPLOAD_SURAT_PENGAJUAN_REWARD_PRESTASI', 'surat_reward', $submitterName);
            $validated['file_pengajuan_reward'] = $upload['url'];
        }

        if ($request->hasFile('file_ktm')) {
            if ($prestasiMandiri->file_ktm) {
                $this->driveService->deleteFile($prestasiMandiri->file_ktm);
            }
            $file = $request->file('file_ktm');
            $upload = $this->driveService->upload($file, 'GOOGLE_DRIVE_UPLOAD_KTM', 'ktm', $submitterName);
            $validated['file_ktm'] = $upload['url'];
        }

        if ($request->hasFile('file_surat_tugas')) {
            if ($prestasiMandiri->file_surat_tugas) {
                $this->driveService->deleteFile($prestasiMandiri->file_surat_tugas);
            }
            $file = $request->file('file_surat_tugas');
            $upload = $this->driveService->upload($file, 'GOOGLE_DRIVE_UPLOAD_SURAT_WRIII_ATAU_WDI', 'surat_tugas', $submitterName);
            $validated['file_surat_tugas'] = $upload['url'];
        }

        if ($request->hasFile('file_foto_upp')) {
            $oldFoto = $prestasiMandiri->file_foto_upp ?? $prestasiMandiri->link_foto_upp;
            if ($oldFoto) {
                $this->driveService->deleteFile($oldFoto);
            }
            $file = $request->file('file_foto_upp');
            $upload = $this->driveService->upload($file, 'GOOGLE_DRIVE_UPLOAD_FOTO_UPP', 'foto_upp', $submitterName);
            $validated['file_foto_upp'] = $upload['url'];
            $validated['link_foto_upp'] = $upload['url'];
        }

        // If student name changed and no new file was uploaded, synchronize name on Google Drive
        $oldOwnerName = !empty($prestasiMandiri->data_mahasiswa[0]['nama']) ? $prestasiMandiri->data_mahasiswa[0]['nama'] : '';
        if (!empty($oldOwnerName) && strcasecmp(trim($oldOwnerName), trim($fileOwnerName)) !== 0) {
            $existingFields = [
                'file_dokumen_sertifikat' => 'sertifikat',
                'file_dokumen_lpj'        => 'lpj',
                'file_ktm'                => 'ktm',
                'file_pengajuan_reward'   => 'reward',
                'file_surat_tugas'        => 'surat_tugas',
                'file_foto_upp'           => 'foto_upp',
            ];

            foreach ($existingFields as $fld => $lbl) {
                $val = $prestasiMandiri->{$fld} ?? ($fld === 'file_foto_upp' ? $prestasiMandiri->link_foto_upp : null);
                if (!$request->hasFile($fld) && !empty($val) && str_contains($val, 'drive.google.com')) {
                    $this->driveService->renameExistingFile($val, $fileOwnerName, $lbl);
                }
            }
        }

        $validated['data_mahasiswa'] = $mahasiswa;
        $validated['data_dosen'] = $dosen;
        if (!empty($validated['tanggal_sertifikat'])) {
            $validated['tahun'] = Carbon::parse($validated['tanggal_sertifikat'])->year;
        }

        $prestasiMandiri->update($validated);

        return redirect()->route('prestasi-mandiri.index')
            ->with('success', 'Data Prestasi Mandiri berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, PrestasiMandiri $prestasiMandiri)
    {
        $this->authorizeStudentAccess($prestasiMandiri);

        if ($prestasiMandiri->file_dokumen_sertifikat) {
            $this->driveService->deleteFile($prestasiMandiri->file_dokumen_sertifikat);
        }
        if ($prestasiMandiri->file_dokumen_lpj) {
            $this->driveService->deleteFile($prestasiMandiri->file_dokumen_lpj);
        }
        if ($prestasiMandiri->file_pengajuan_reward) {
            $this->driveService->deleteFile($prestasiMandiri->file_pengajuan_reward);
        }
        if ($prestasiMandiri->file_ktm) {
            $this->driveService->deleteFile($prestasiMandiri->file_ktm);
        }
        if ($prestasiMandiri->file_surat_tugas) {
            $this->driveService->deleteFile($prestasiMandiri->file_surat_tugas);
        }
        $fotoUpp = $prestasiMandiri->file_foto_upp ?? $prestasiMandiri->link_foto_upp;
        if ($fotoUpp) {
            $this->driveService->deleteFile($fotoUpp);
        }

        $prestasiMandiri->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Data Prestasi Mandiri dan seluruh berkas di Google Drive berhasil dihapus.']);
        }

        return redirect()->route('prestasi-mandiri.index')
            ->with('success', 'Data Prestasi Mandiri dan seluruh berkas di Google Drive berhasil dihapus.');
    }

    /**
     * Download uploaded LPJ file for Prestasi Mandiri
     */
    public function downloadUploadedLpj(PrestasiMandiri $prestasiMandiri)
    {
        return $this->downloadFile($prestasiMandiri, 'lpj');
    }

    /**
     * Download uploaded document files for Prestasi Mandiri
     */
    public function downloadFile(PrestasiMandiri $prestasiMandiri, string $type)
    {
        $this->authorizeStudentAccess($prestasiMandiri);

        $filePath = match($type) {
            'lpj'         => $prestasiMandiri->file_dokumen_lpj,
            'sertifikat'  => $prestasiMandiri->file_dokumen_sertifikat,
            'reward'      => $prestasiMandiri->file_pengajuan_reward,
            'ktm'                  => $prestasiMandiri->file_ktm,
            'surat-tugas'          => $prestasiMandiri->file_surat_tugas,
            'foto-upp', 'foto_upp' => $prestasiMandiri->file_foto_upp ?? $prestasiMandiri->link_foto_upp,
            default                => null,
        };

        if ($filePath) {
            if (str_starts_with($filePath, 'http://') || str_starts_with($filePath, 'https://')) {
                return redirect()->away($filePath);
            }

            if (Storage::disk('public')->exists($filePath)) {
                return Storage::disk('public')->download($filePath);
            }

            $publicPath = public_path($filePath);
            if (file_exists($publicPath)) {
                return response()->download($publicPath);
            }
        }

        abort(404, 'Berkas dokumen tidak ditemukan.');
    }
}
