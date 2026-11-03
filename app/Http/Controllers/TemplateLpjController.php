<?php

namespace App\Http\Controllers;

use App\DataTables\TemplateLpjDataTable;
use App\Models\TemplateLpj;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Closure;

class TemplateLpjController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            function (Request $request, Closure $next) {
                if (auth()->check() && auth()->user()->role === 'mahasiswa') {
                    abort(403, 'Akses manajemen template LPJ tidak diizinkan untuk akun mahasiswa.');
                }
                return $next($request);
            },
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(TemplateLpjDataTable $dataTable)
    {
        return $dataTable->render('pages.template-lpj.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoriList = [
            'Prestasi Mandiri' => 'Prestasi Mandiri',
            'Prestasi Belmawa' => 'Prestasi Belmawa',
            'Kegiatan Ormawa'  => 'Kegiatan Ormawa / Umum',
        ];

        return view('pages.template-lpj.create', compact('kategoriList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'          => ['required', 'string', 'max:255'],
            'kategori'      => ['required', 'string', 'max:100'],
            'tipe'          => ['required', 'in:link,file'],
            'url_link'      => ['nullable', 'url', 'max:1000', 'required_if:tipe,link'],
            'file_document' => ['nullable', 'file', 'mimes:doc,docx,pdf,odt,zip', 'max:20480', 'required_if:tipe,file'],
            'keterangan'    => ['nullable', 'string', 'max:1000'],
            'is_active'     => ['nullable', 'boolean'],
        ], [
            'nama.required'             => 'Nama dokumen template wajib diisi.',
            'kategori.required'         => 'Kategori template wajib dipilih.',
            'tipe.required'             => 'Pilih tipe sumber template (Tautan URL atau File Dokumen).',
            'url_link.required_if'      => 'Tautan link download (Google Drive / Website) wajib diisi untuk tipe Custom Link.',
            'url_link.url'              => 'Format tautan link harus berupa URL yang valid (misal: https://...).',
            'file_document.required_if' => 'File dokumen template wajib diunggah untuk tipe File Upload.',
            'file_document.mimes'       => 'Format file harus berupa dokumen (.docx, .doc, .pdf, .odt, .zip).',
            'file_document.max'         => 'Ukuran file dokumen maksimal 20 MB.',
        ]);

        $isActive = $request->boolean('is_active', false);

        $filePath = null;
        $fileName = null;

        if ($request->hasFile('file_document')) {
            $file = $request->file('file_document');
            $fileName = $file->getClientOriginalName();
            $storedName = time() . '_' . preg_replace('/[^A-Za-z0-9\._-]/', '', $fileName);
            $filePath = $file->storeAs('templates', $storedName, 'public');
        }

        TemplateLpj::create([
            'nama'       => $validated['nama'],
            'kategori'   => $validated['kategori'],
            'tipe'       => $validated['tipe'],
            'url_link'   => $validated['tipe'] === 'link' ? $validated['url_link'] : null,
            'file_path'  => $filePath,
            'file_name'  => $fileName,
            'keterangan' => $validated['keterangan'] ?? null,
            'is_active'  => $isActive,
        ]);

        Alert::success('Berhasil', 'Template LPJ berhasil ditambahkan!');
        return redirect()->route('template-lpj.index')->with('success', 'Template LPJ berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TemplateLpj $templateLpj)
    {
        $kategoriList = [
            'Prestasi Mandiri' => 'Prestasi Mandiri',
            'Prestasi Belmawa' => 'Prestasi Belmawa',
            'Kegiatan Ormawa'  => 'Kegiatan Ormawa / Umum',
        ];

        return view('pages.template-lpj.edit', compact('templateLpj', 'kategoriList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TemplateLpj $templateLpj)
    {
        $rules = [
            'nama'          => ['required', 'string', 'max:255'],
            'kategori'      => ['required', 'string', 'max:100'],
            'tipe'          => ['required', 'in:link,file'],
            'url_link'      => ['nullable', 'url', 'max:1000'],
            'file_document' => ['nullable', 'file', 'mimes:doc,docx,pdf,odt,zip', 'max:20480'],
            'keterangan'    => ['nullable', 'string', 'max:1000'],
            'is_active'     => ['nullable', 'boolean'],
        ];

        if ($request->input('tipe') === 'link' && empty($request->input('url_link')) && empty($templateLpj->url_link)) {
            $rules['url_link'][] = 'required';
        }

        if ($request->input('tipe') === 'file' && !$request->hasFile('file_document') && empty($templateLpj->file_path)) {
            $rules['file_document'][] = 'required';
        }

        $validated = $request->validate($rules, [
            'nama.required'       => 'Nama dokumen template wajib diisi.',
            'kategori.required'   => 'Kategori template wajib dipilih.',
            'url_link.required'   => 'Tautan link download wajib diisi.',
            'url_link.url'        => 'Format tautan link harus berupa URL yang valid (misal: https://...).',
            'file_document.mimes' => 'Format file harus berupa dokumen (.docx, .doc, .pdf, .odt, .zip).',
            'file_document.max'   => 'Ukuran file dokumen maksimal 20 MB.',
        ]);

        $isActive = $request->boolean('is_active', false);

        $filePath = $templateLpj->file_path;
        $fileName = $templateLpj->file_name;

        if ($request->hasFile('file_document')) {
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            $file = $request->file('file_document');
            $fileName = $file->getClientOriginalName();
            $storedName = time() . '_' . preg_replace('/[^A-Za-z0-9\._-]/', '', $fileName);
            $filePath = $file->storeAs('templates', $storedName, 'public');
        }

        $templateLpj->update([
            'nama'       => $validated['nama'],
            'kategori'   => $validated['kategori'],
            'tipe'       => $validated['tipe'],
            'url_link'   => $validated['tipe'] === 'link' ? ($validated['url_link'] ?? $templateLpj->url_link) : null,
            'file_path'  => $filePath,
            'file_name'  => $fileName,
            'keterangan' => $validated['keterangan'] ?? null,
            'is_active'  => $isActive,
        ]);

        Alert::success('Berhasil', 'Template LPJ berhasil diperbarui!');
        return redirect()->route('template-lpj.index')->with('success', 'Template LPJ berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TemplateLpj $templateLpj)
    {
        if ($templateLpj->file_path && Storage::disk('public')->exists($templateLpj->file_path)) {
            Storage::disk('public')->delete($templateLpj->file_path);
        }

        $templateLpj->delete();

        Alert::success('Berhasil', 'Template LPJ berhasil dihapus!');
        return redirect()->route('template-lpj.index')->with('success', 'Template LPJ berhasil dihapus!');
    }

    /**
     * Download or redirect to template LPJ file/link.
     */
    public function download(TemplateLpj $templateLpj)
    {
        if ($templateLpj->tipe === 'link' && !empty($templateLpj->url_link)) {
            return redirect()->away($templateLpj->url_link);
        }

        if (!empty($templateLpj->file_path)) {
            if (Storage::disk('public')->exists($templateLpj->file_path)) {
                $downloadName = $templateLpj->file_name ?? basename($templateLpj->file_path);
                return Storage::disk('public')->download($templateLpj->file_path, $downloadName);
            }

            $publicPath = public_path($templateLpj->file_path);
            if (file_exists($publicPath)) {
                $downloadName = $templateLpj->file_name ?? basename($publicPath);
                return response()->download($publicPath, $downloadName);
            }
        }

        if (!empty($templateLpj->url_link)) {
            return redirect()->away($templateLpj->url_link);
        }

        Alert::error('Gagal', 'File atau tautan template LPJ tidak ditemukan.');
        return back()->with('error', 'File atau tautan template LPJ tidak ditemukan.');
    }
}
