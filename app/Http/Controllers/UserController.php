<?php

namespace App\Http\Controllers;

use App\DataTables\UserDataTable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('pages.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = [
            'superadmin'      => 'Super Admin',
            'adminbkak'       => 'Admin BKAK',
            'kabid'           => 'Kepala Bidang Kemahasiswaan',
            'staff'           => 'Staff Kemahasiswaan',
            'pimpinan'        => 'Pimpinan Universitas',
            'prodi'           => 'Admin Program Studi',
            'dosenpendamping' => 'Dosen Pendamping',
            'mahasiswa'       => 'Mahasiswa',
        ];

        return view('pages.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
            'role'          => ['required', 'string'],
            'nim'           => ['nullable', 'string', 'max:50'],
            'fakultas'      => ['nullable', 'string', 'max:255'],
            'program_studi' => ['nullable', 'string', 'max:255'],
            'jabatan'       => ['nullable', 'string', 'max:255'],
            'photo_profile' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'is_active'     => ['required', 'boolean'],
        ], [
            'name.required'         => 'Nama lengkap wajib diisi.',
            'email.required'        => 'Alamat email wajib diisi.',
            'email.email'           => 'Format email tidak valid.',
            'email.unique'          => 'Email sudah terdaftar.',
            'password.required'     => 'Password wajib diisi.',
            'password.min'          => 'Password minimal 8 karakter.',
            'password.confirmed'    => 'Konfirmasi password tidak cocok.',
            'role.required'         => 'Role wajib dipilih.',
            'photo_profile.image'   => 'Foto profil harus berupa file gambar.',
            'photo_profile.mimes'   => 'Format foto harus berupa jpeg, png, jpg, atau webp.',
            'photo_profile.max'     => 'Ukuran foto profil maksimal 2 MB.',
            'is_active.required'    => 'Status aktif wajib dipilih.',
        ]);

        if ($request->hasFile('photo_profile')) {
            $file = $request->file('photo_profile');
            $fileName = 'profile_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $validated['photo_profile'] = $file->storeAs('profiles', $fileName, 'public');
        }

        User::create($validated);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = [
            'superadmin'      => 'Super Admin',
            'adminbkak'       => 'Admin BKAK',
            'kabid'           => 'Kepala Bidang Kemahasiswaan',
            'staff'           => 'Staff Kemahasiswaan',
            'pimpinan'        => 'Pimpinan Universitas',
            'prodi'           => 'Admin Program Studi',
            'dosenpendamping' => 'Dosen Pendamping',
            'mahasiswa'       => 'Mahasiswa',
        ];

        return view('pages.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password'      => ['nullable', 'string', 'min:8', 'confirmed'],
            'role'          => ['required', 'string'],
            'nim'           => ['nullable', 'string', 'max:50'],
            'fakultas'      => ['nullable', 'string', 'max:255'],
            'program_studi' => ['nullable', 'string', 'max:255'],
            'jabatan'       => ['nullable', 'string', 'max:255'],
            'photo_profile' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'is_active'     => ['required', 'boolean'],
        ], [
            'name.required'         => 'Nama lengkap wajib diisi.',
            'email.required'        => 'Alamat email wajib diisi.',
            'email.email'           => 'Format email tidak valid.',
            'email.unique'          => 'Email sudah terdaftar.',
            'password.min'          => 'Password minimal 8 karakter.',
            'password.confirmed'    => 'Konfirmasi password tidak cocok.',
            'role.required'         => 'Role wajib dipilih.',
            'photo_profile.image'   => 'Foto profil harus berupa file gambar.',
            'photo_profile.mimes'   => 'Format foto harus berupa jpeg, png, jpg, atau webp.',
            'photo_profile.max'     => 'Ukuran foto profil maksimal 2 MB.',
            'is_active.required'    => 'Status aktif wajib dipilih.',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
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

        return redirect()->route('users.index')
            ->with('success', 'Data user berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $user)
    {
        if (Auth::id() === $user->id) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Anda tidak dapat menghapus akun Anda sendiri!'], 400);
            }
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        if ($user->photo_profile && Storage::disk('public')->exists($user->photo_profile)) {
            Storage::disk('public')->delete($user->photo_profile);
        }

        $user->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'User berhasil dihapus.']);
        }

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
