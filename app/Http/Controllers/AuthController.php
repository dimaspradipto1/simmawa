<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard.index');
        }
        return view('layouts.auth.login');
    }

    /**
     * Master data Fakultas, Program Studi, Organisasi, dan Jabatan UIS.
     */
    public static function getMasterAcademicAndOrgData(): array
    {
        return [
            'fakultas' => [
                'Fakultas Sains dan Teknologi' => 'Fakultas Sains dan Teknologi',
                'Fakultas Ekonomi dan Bisnis'  => 'Fakultas Ekonomi dan Bisnis',
                'Fakultas Ilmu Kesehatan'      => 'Fakultas Ilmu Kesehatan',
                'Pascasarjana'                 => 'Pascasarjana',
            ],
            'prodis' => [
                'Fakultas Sains dan Teknologi' => [
                    'S1 - Teknik Informatika' => 'S1 - Teknik Informatika (HMTI)',
                    'S1 - Sistem Informasi'   => 'S1 - Sistem Informasi (HIMASI)',
                    'S1 - Teknik Industri'    => 'S1 - Teknik Industri (HIMATRI)',
                    'S1 - Teknik Logistik'    => 'S1 - Teknik Logistik (HIMANISTIK)',
                    'S1 - Teknik Perkapalan'  => 'S1 - Teknik Perkapalan (HMTP)',
                ],
                'Fakultas Ekonomi dan Bisnis' => [
                    'S1 - Manajemen'          => 'S1 - Manajemen (HMM)',
                    'S1 - Akuntansi'          => 'S1 - Akuntansi (HMA)',
                    'S2 - Magister Manajemen' => 'S2 - Magister Manajemen',
                ],
                'Fakultas Ilmu Kesehatan' => [
                    'S1 - Kesehatan dan Keselamatan Kerja' => 'S1 - Kesehatan dan Keselamatan Kerja (HIMA K3)',
                    'S1 - Kesehatan Lingkungan'            => 'S1 - Kesehatan Lingkungan (HIMA KESLING / HMKL)',
                    'S1 - Kesehatan Masyarakat'            => 'S1 - Kesehatan Masyarakat',
                    'S2 - Magister Kesehatan Masyarakat'   => 'S2 - Magister Kesehatan Masyarakat',
                ],
            ],
            'organisasi' => [
                'Lembaga Mahasiswa (Eksekutif & Legislatif)' => [
                    'BEM Universitas Ibnu Sina' => 'BEM (Badan Eksekutif Mahasiswa) UIS',
                    'DPM Universitas Ibnu Sina' => 'DPM (Dewan Perwakilan Mahasiswa) UIS',
                ],
                'Himpunan Mahasiswa Jurusan (HIMA)' => [
                    'HMTI - Teknik Informatika'                         => 'HMTI - Himpunan Mahasiswa Teknik Informatika',
                    'HIMASI - Sistem Informasi'                         => 'HIMASI - Himpunan Mahasiswa Sistem Informasi',
                    'HIMATRI - Teknik Industri'                         => 'HIMATRI - Himpunan Mahasiswa Teknik Industri',
                    'HIMANISTIK - Teknik Logistik'                      => 'HIMANISTIK - Himpunan Mahasiswa Teknik Logistik',
                    'HMTP - Teknik Perkapalan'                          => 'HMTP - Himpunan Mahasiswa Teknik Perkapalan',
                    'HMM - Manajemen'                                   => 'HMM - Himpunan Mahasiswa Manajemen',
                    'HMA - Akuntansi'                                   => 'HMA - Himpunan Mahasiswa Akuntansi',
                    'HIMA K3 - Kesehatan & Keselamatan Kerja'           => 'HIMA K3 - Kesehatan & Keselamatan Kerja',
                    'HMKL - Kesehatan Lingkungan'                       => 'HMKL - Himpunan Mahasiswa Kesehatan Lingkungan',
                    'HIMA KESMAS - Kesehatan Masyarakat'               => 'HIMA KESMAS - Kesehatan Masyarakat',
                ],
                'Unit Kegiatan Mahasiswa (UKM)' => [
                    'UKM SiPena'                    => 'UKM SiPena (Sinergi Para Mahasiswa)',
                    'UKM PERKUSI (Komputer)'        => 'UKM PERKUSI (Persatuan Komputer Mahasiswa UIS)',
                    'UKM Perkusi (Seni Musik)'      => 'UKM Perkusi (Seni Musik & Kreativitas UIS)',
                    'UKM Mapala Ibnu Sina (MAPAIS)' => 'UKM MAPAIS (Mahasiswa Pecinta Alam Ibnu Sina)',
                    'UKM Badminton'                 => 'UKM Badminton UIS',
                    'UKM Futsal'                    => 'UKM Futsal Club UIS',
                    'UKM Pencak Silat'              => 'UKM Pencak Silat UIS',
                    'UKM Atletik'                   => 'UKM Atletik UIS',
                    'UKM Sanggar Tari Rossa Primula'=> 'UKM Sanggar Tari Rossa Primula',
                    'UKM English Club'              => 'UKM English Club UIS',
                    'UKM FORSI'                     => 'UKM FORSI (Forum Studi Islam)',
                    'UKM PUSWIRA'                   => 'UKM PUSWIRA (Pusat Kewirausahaan Mahasiswa)',
                    'UKM Gerakan Pramuka Racana'    => 'UKM Gerakan Pramuka Racana Raja H Fisabilillah - Srikandi Walanda',
                ],
            ],
            'jabatan' => [
                'Anggota'                    => 'Anggota',
                'Ketua Umum'                 => 'Ketua Umum',
                'Wakil Ketua'                => 'Wakil Ketua',
                'Sekretaris'                 => 'Sekretaris',
                'Bendahara'                  => 'Bendahara',
                'Koordinator Divisi'         => 'Koordinator Divisi',
                'Anggota Divisi / Pengurus'  => 'Anggota Divisi / Pengurus',
            ],
        ];
    }

    public function register()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard.index');
        }
        $master = self::getMasterAcademicAndOrgData();
        return view('layouts.auth.register', [
            'fakultas'   => $master['fakultas'],
            'prodis'     => $master['prodis'],
            'organisasi' => $master['organisasi'],
            'jabatan'    => $master['jabatan'],
        ]);
    }

    public function storeRegister(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users,email',
            'password'      => 'required|string|min:6|confirmed',
            'nim'           => 'nullable|string|max:50',
            'fakultas'      => 'nullable|string|max:255',
            'program_studi' => 'nullable|string|max:255',
            'organisasi'    => 'nullable',
            'jabatan'       => 'nullable|string|max:255',
        ], [
            'name.required'      => 'Nama lengkap wajib diisi.',
            'email.required'     => 'Alamat email wajib diisi.',
            'email.email'        => 'Format alamat email tidak valid.',
            'email.unique'       => 'Email ini telah terdaftar, silakan gunakan email lain atau login.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal terdiri dari 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $organisasiVal = null;
        if ($request->has('organisasi')) {
            if (is_array($request->organisasi)) {
                $organisasiVal = implode(', ', array_filter(array_map('trim', $request->organisasi)));
            } else {
                $organisasiVal = trim($request->organisasi);
            }
        }

        $user = User::create([
            'name'          => trim($request->name),
            'email'         => strtolower(trim($request->email)),
            'password'      => Hash::make($request->password),
            'role'          => 'mahasiswa', // Default Role Mahasiswa
            'nim'           => $request->filled('nim') ? trim($request->nim) : null,
            'fakultas'      => $request->filled('fakultas') ? trim($request->fakultas) : null,
            'program_studi' => $request->filled('program_studi') ? trim($request->program_studi) : null,
            'organisasi'    => !empty($organisasiVal) ? $organisasiVal : null,
            'jabatan'       => $request->filled('jabatan') ? trim($request->jabatan) : null,
            'is_active'     => true,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard.index')
            ->with('success', 'Pendaftaran berhasil! Selamat datang di SIPENA UIS, ' . $user->name . '.');
    }

    public function authenticate(AuthRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                return redirect()->back()
                    ->withInput($request->only('email', 'remember'))
                    ->withErrors(['email' => 'Akun Anda dinonaktifkan. Silakan hubungi administrator.']);
            }

            $request->session()->regenerate();

            return redirect()->intended(route('dashboard.index'))
                ->with('success', 'Selamat datang kembali, ' . $user->name . '!');
        }

        return redirect()->back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors([
                'email' => 'Email atau password yang Anda masukkan salah.',
            ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil keluar.');
    }

    public function editPassword()
    {
        return view('pages.users.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->update([
            'password' => $request->password,
        ]);

        return redirect()->back()->with('success', 'Password Anda berhasil diperbarui.');
    }

    public function profile()
    {
        $user = Auth::user();
        $logoProdi = \App\Models\LogoProdi::getForProdi($user->program_studi);
        $master = self::getMasterAcademicAndOrgData();
        return view('pages.users.profile', [
            'user'       => $user,
            'logoProdi'  => $logoProdi,
            'fakultas'   => $master['fakultas'],
            'prodis'     => $master['prodis'],
            'organisasi' => $master['organisasi'],
            'jabatan'    => $master['jabatan'],
        ]);
    }

    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'nim'           => ['nullable', 'string', 'max:50'],
            'fakultas'      => ['nullable', 'string', 'max:255'],
            'program_studi' => ['nullable', 'string', 'max:255'],
            'organisasi'    => ['nullable'],
            'jabatan'       => ['nullable', 'string', 'max:255'],
            'photo_profile' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'name.required'         => 'Nama lengkap wajib diisi.',
            'email.required'        => 'Alamat email wajib diisi.',
            'email.email'           => 'Format email tidak valid.',
            'email.unique'          => 'Email sudah terdaftar pada pengguna lain.',
            'photo_profile.image'   => 'File harus berupa gambar.',
            'photo_profile.mimes'   => 'Format foto harus berupa jpeg, png, jpg, atau webp.',
            'photo_profile.max'     => 'Ukuran foto profil maksimal 2 MB.',
        ]);

        if ($request->has('organisasi')) {
            if (is_array($request->organisasi)) {
                $validated['organisasi'] = implode(', ', array_filter(array_map('trim', $request->organisasi))) ?: null;
            } else {
                $validated['organisasi'] = trim($request->organisasi) ?: null;
            }
        } else {
            $validated['organisasi'] = null;
        }

        if ($request->boolean('remove_photo')) {
            if ($user->photo_profile && Storage::disk('public')->exists($user->photo_profile)) {
                Storage::disk('public')->delete($user->photo_profile);
            }
            $validated['photo_profile'] = null;
        } elseif ($request->hasFile('photo_profile')) {
            if ($user->photo_profile && Storage::disk('public')->exists($user->photo_profile)) {
                Storage::disk('public')->delete($user->photo_profile);
            }
            $file = $request->file('photo_profile');
            $fileName = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $validated['photo_profile'] = $file->storeAs('profiles', $fileName, 'public');
        }

        $user->update($validated);

        return redirect()->back()->with('success', 'Profil Anda berhasil diperbarui.');
    }
}

