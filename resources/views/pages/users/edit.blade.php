@extends('layouts.dashboard.template')

@section('content')
<div class="pagetitle">
    <h1>Edit User</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Manajemen Pengguna</a></li>
            <li class="breadcrumb-item active">Edit User</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Form Edit Data User</h5>

                    <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="row g-3">
                        @csrf
                        @method('PUT')

                        <div class="col-md-6">
                            <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" placeholder="Masukkan nama lengkap" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Alamat Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" placeholder="contoh@uis.ac.id" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="role" class="form-label">Role Akses <span class="text-danger">*</span></label>
                            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                <option value="" disabled>-- Pilih Role --</option>
                                @foreach($roles as $key => $label)
                                    <option value="{{ $key }}" {{ old('role', $user->role) == $key ? 'selected' : '' }}>{{ $label }} ({{ $key }})</option>
                                @endforeach
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="is_active" class="form-label">Status Akun <span class="text-danger">*</span></label>
                            <select class="form-select @error('is_active') is-invalid @enderror" id="is_active" name="is_active" required>
                                <option value="1" {{ old('is_active', $user->is_active ? '1' : '0') == '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('is_active', $user->is_active ? '1' : '0') == '0' ? 'selected' : '' }}>Non-Aktif</option>
                            </select>
                            @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12"><hr class="my-2 text-muted"></div>

                        <div class="col-md-6">
                            <label for="nim" class="form-label">NIM <small class="text-muted">(Opsional)</small></label>
                            <input type="text" class="form-control @error('nim') is-invalid @enderror" id="nim" name="nim" value="{{ old('nim', $user->nim) }}" placeholder="Nomor Induk Mahasiswa">
                            @error('nim')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="jabatan" class="form-label">Jabatan <small class="text-muted">(Opsional)</small></label>
                            <input type="text" class="form-control @error('jabatan') is-invalid @enderror" id="jabatan" name="jabatan" value="{{ old('jabatan', $user->jabatan) }}" placeholder="Contoh: Dosen Pembimbing">
                            @error('jabatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="fakultas" class="form-label">Fakultas <small class="text-muted">(Opsional)</small></label>
                            <input type="text" class="form-control @error('fakultas') is-invalid @enderror" id="fakultas" name="fakultas" value="{{ old('fakultas', $user->fakultas) }}" placeholder="Contoh: Fakultas Ilmu Komputer">
                            @error('fakultas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="program_studi" class="form-label">Program Studi <small class="text-muted">(Opsional)</small></label>
                            <input type="text" class="form-control @error('program_studi') is-invalid @enderror" id="program_studi" name="program_studi" value="{{ old('program_studi', $user->program_studi) }}" placeholder="Contoh: Teknik Informatika">
                            @error('program_studi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Foto Profil <small class="text-muted">(Opsional - JPG, PNG, WEBP, Maks. 2MB)</small></label>
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $user->avatar_url }}" alt="Avatar" class="rounded-circle shadow-sm" style="width: 50px; height: 50px; object-fit: cover; border: 2px solid #cbd5e1;">
                                <div class="flex-grow-1">
                                    <input type="file" class="form-control @error('photo_profile') is-invalid @enderror" id="photo_profile" name="photo_profile" accept="image/*">
                                    @error('photo_profile')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if($user->photo_profile)
                                        <div class="form-check mt-1">
                                            <input class="form-check-input" type="checkbox" name="remove_photo" id="remove_photo" value="1">
                                            <label class="form-check-label text-danger small" for="remove_photo">
                                                <i class="bi bi-trash me-1"></i> Hapus foto profil pengguna ini
                                            </label>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-12"><hr class="my-2 text-muted"></div>

                        <div class="col-md-6">
                            <label for="password" class="form-label">Password Baru <small class="text-muted">(Kosongkan jika tidak diubah)</small></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Minimal 8 karakter">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru">
                        </div>

                        <div class="col-12 mt-4 text-end">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary me-2">
                                <i class="bi bi-x-circle me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Perbarui User
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</section>
@endsection
