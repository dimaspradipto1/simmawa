<?php

namespace App\Http\Controllers;

use App\Models\KtaSetting;
use App\Models\LogoProdi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KtaSettingController extends Controller
{
    /**
     * Memastikan hanya superadmin atau adminbkak yang dapat mengakses.
     */
    protected function authorizeAdmin(): void
    {
        if (!in_array(Auth::user()->role, ['superadmin', 'adminbkak'])) {
            abort(403, 'Anda tidak memiliki hak akses ke halaman pengaturan KTA / KTM.');
        }
    }

    /**
     * Halaman form pengaturan KTA / KTM beserta live preview.
     */
    public function index()
    {
        $this->authorizeAdmin();

        $setting = KtaSetting::getSetting();

        // Ambil sampel data untuk live preview di admin panel
        $sampleUser = Auth::user();
        $sampleLogo = LogoProdi::where('is_active', true)->first();

        return view('pages.kta-setting.index', compact('setting', 'sampleUser', 'sampleLogo'));
    }

    /**
     * Menyimpan pembaruan pengaturan KTA / KTM.
     */
    public function update(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'tahun_periode'      => 'required|string|max:100',
            'label_periode'      => 'required|string|max:100',
            'label_masa_berlaku' => 'required|string|max:100',
            'teks_masa_berlaku'  => 'required|string|max:150',
            'nama_universitas'   => 'required|string|max:200',
            'slogan_bawah'       => 'nullable|string|max:255',
            'catatan_keterangan' => 'nullable|string|max:500',
            'background_card'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'remove_background'  => 'nullable|boolean',
        ], [
            'tahun_periode.required'      => 'Tahun periode wajib diisi (contoh: 2026 – 2027).',
            'label_periode.required'      => 'Label periode wajib diisi (contoh: PERIODE).',
            'label_masa_berlaku.required' => 'Label masa berlaku wajib diisi (contoh: Masa Berlaku:).',
            'teks_masa_berlaku.required'  => 'Teks masa berlaku wajib diisi (contoh: Tahun Kepengurusan 2026 – 2027).',
            'nama_universitas.required'   => 'Nama universitas wajib diisi.',
            'background_card.image'       => 'File template background harus berupa gambar.',
            'background_card.mimes'       => 'Format gambar template harus berupa jpeg, png, jpg, atau webp.',
            'background_card.max'         => 'Ukuran gambar template background maksimal 4 MB.',
        ]);

        $setting = KtaSetting::getSetting();

        // Handle reset / hapus background kustom
        if ($request->boolean('remove_background')) {
            if ($setting->background_card && Storage::disk('public')->exists($setting->background_card)) {
                Storage::disk('public')->delete($setting->background_card);
            }
            $validated['background_card'] = null;
        } elseif ($request->hasFile('background_card')) {
            // Hapus file lama jika ada
            if ($setting->background_card && Storage::disk('public')->exists($setting->background_card)) {
                Storage::disk('public')->delete($setting->background_card);
            }

            $file = $request->file('background_card');
            $fileName = 'kta_bg_' . time() . '.' . $file->getClientOriginalExtension();
            $validated['background_card'] = $file->storeAs('kta', $fileName, 'public');
        } else {
            // Pertahankan nilai lama jika tidak diubah
            unset($validated['background_card']);
        }

        unset($validated['remove_background']);

        $setting->update($validated);

        return redirect()->route('kta-setting.index')
            ->with('success', 'Pengaturan Periode & Kartu Tanda Anggota (KTA / KTM) berhasil diperbarui.');
    }
}

