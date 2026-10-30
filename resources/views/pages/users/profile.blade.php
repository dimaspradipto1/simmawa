@extends('layouts.dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Profil Saya</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Home</a></li>
                <li class="breadcrumb-item active">Profil</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
        <div class="row">

            @if (session('success'))
                <div class="col-12">
                    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                        <div>{{ session('success') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="col-12">
                    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                        <div>{{ session('error') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            <div class="col-xl-4">
                <div class="card shadow-sm">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center text-center">
                        <img id="profile-preview-card" src="{{ $user->avatar_url }}" alt="Profile"
                            class="rounded-circle shadow-sm"
                            style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #e2e8f0;">
                        <h5 class="fw-bold mt-3 mb-1">{{ $user->name }}</h5>
                        <span class="badge bg-primary text-uppercase px-3 py-2 my-1">{{ $user->role }}</span>
                        <p class="text-muted small m-0"><i class="bi bi-envelope me-1"></i>{{ $user->email }}</p>
                        <div class="mt-2 mb-3">
                            @if ($user->is_active)
                                <span class="badge bg-success-subtle text-success border border-success"><i
                                        class="bi bi-check-circle me-1"></i> Akun Aktif</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger"><i
                                        class="bi bi-x-circle me-1"></i> Non-Aktif</span>
                            @endif
                        </div>

                        <div class="w-100 text-start border-top pt-3 mt-1">
                            @if ($user->nim)
                                <div class="d-flex align-items-center mb-2 small">
                                    <i class="bi bi-person-vcard text-primary me-2 fs-6"></i>
                                    <div>
                                        <span class="text-muted d-block">NIM</span>
                                        <span class="fw-semibold text-dark">{{ $user->nim }}</span>
                                    </div>
                                </div>
                            @endif

                            @if ($user->fakultas)
                                <div class="d-flex align-items-center mb-2 small">
                                    <i class="bi bi-buildings text-primary me-2 fs-6"></i>
                                    <div>
                                        <span class="text-muted d-block">Fakultas</span>
                                        <span class="fw-semibold text-dark">{{ $user->fakultas }}</span>
                                    </div>
                                </div>
                            @endif

                            @if ($user->program_studi)
                                <div class="d-flex align-items-center mb-2 small">
                                    <i class="bi bi-mortarboard text-primary me-2 fs-6"></i>
                                    <div>
                                        <span class="text-muted d-block">Program Studi</span>
                                        <span class="fw-semibold text-dark">{{ $user->program_studi }}</span>
                                    </div>
                                </div>
                            @endif

                            @if ($user->organisasi)
                                <div class="d-flex align-items-center mb-2 small">
                                    <i class="bi bi-people text-primary me-2 fs-6"></i>
                                    <div>
                                        <span class="text-muted d-block">Organisasi / Ormawa</span>
                                        <span class="fw-semibold text-dark">{{ $user->organisasi }}</span>
                                    </div>
                                </div>
                            @endif

                            @if ($user->jabatan)
                                <div class="d-flex align-items-center mb-1 small">
                                    <i class="bi bi-briefcase text-primary me-2 fs-6"></i>
                                    <div>
                                        <span class="text-muted d-block">Jabatan</span>
                                        <span class="fw-semibold text-dark">{{ $user->jabatan }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="card shadow-sm">
                    <div class="card-body pt-3">

                        <!-- Bordered Tabs -->
                        <ul class="nav nav-tabs nav-tabs-bordered" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-edit"
                                    role="tab"><i class="bi bi-person-gear me-1"></i> Edit Profil</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password"
                                    role="tab"><i class="bi bi-key me-1"></i> Ubah Password</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-ktm"
                                    role="tab"><i class="bi bi-card-heading me-1"></i> Kartu Anggota (KTM)</button>
                            </li>
                        </ul>

                        <div class="tab-content pt-3">

                            <!-- Edit Profile Tab -->
                            <div class="tab-pane fade show active profile-edit" id="profile-edit" role="tabpanel">
                                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
                                    class="row g-3">
                                    @csrf
                                    @method('PUT')

                                    <!-- Photo Profile Upload -->
                                    <div class="col-12">
                                        <label class="form-label fw-bold"><i class="bi bi-camera me-1 text-primary"></i>
                                            Foto Profil</label>
                                        <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 border">
                                            <img id="avatar-input-preview" src="{{ $user->avatar_url }}" alt="Preview Foto"
                                                class="rounded-circle shadow-sm"
                                                style="width: 75px; height: 75px; object-fit: cover; border: 2px solid #cbd5e1;">
                                            <div class="flex-grow-1">
                                                <input type="file"
                                                    class="form-control @error('photo_profile') is-invalid @enderror"
                                                    id="photo_profile" name="photo_profile"
                                                    accept="image/png, image/jpeg, image/jpg, image/webp">
                                                <div class="form-text small text-muted mt-1">
                                                    Format: JPG, JPEG, PNG, atau WEBP. Maksimal ukuran file: 2 MB.
                                                </div>
                                                @error('photo_profile')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror

                                                @if ($user->photo_profile)
                                                    <div class="form-check mt-2">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="remove_photo" id="remove_photo" value="1">
                                                        <label class="form-check-label text-danger small fw-medium"
                                                            for="remove_photo">
                                                            <i class="bi bi-trash me-1"></i> Hapus foto profil saat ini
                                                            (gunakan inisial nama)
                                                        </label>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="name" class="form-label">Nama Lengkap <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name', $user->name) }}"
                                            required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Alamat Email <span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email', $user->email) }}"
                                            required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="nim" class="form-label">NIM <small class="text-muted">(Nomor Induk Mahasiswa)</small></label>
                                        <input type="text" class="form-control @error('nim') is-invalid @enderror"
                                            id="nim" name="nim" value="{{ old('nim', $user->nim) }}"
                                            placeholder="Contoh: 2021001234">
                                        @error('nim')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="fakultas" class="form-label">Fakultas</label>
                                        <select class="form-select @error('fakultas') is-invalid @enderror" id="fakultas" name="fakultas">
                                            <option value="">-- Pilih Fakultas --</option>
                                            @if(isset($fakultas))
                                                @foreach($fakultas as $key => $label)
                                                    <option value="{{ $key }}" {{ old('fakultas', $user->fakultas) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('fakultas')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="program_studi" class="form-label">Program Studi</label>
                                        <select class="form-select @error('program_studi') is-invalid @enderror" id="program_studi" name="program_studi">
                                            <option value="">-- Pilih Program Studi --</option>
                                            @if(isset($prodis))
                                                @foreach($prodis as $fakName => $listProdi)
                                                    <optgroup label="{{ $fakName }}" data-fakultas="{{ $fakName }}">
                                                        @foreach($listProdi as $pVal => $pLabel)
                                                            <option value="{{ $pVal }}" data-fakultas="{{ $fakName }}" {{ old('program_studi', $user->program_studi) == $pVal ? 'selected' : '' }}>{{ $pLabel }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('program_studi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label d-flex justify-content-between align-items-center mb-1">
                                            <span>Organisasi / Ormawa UIS <small class="text-muted">(Bisa pilih lebih dari 1)</small></span>
                                            <span class="badge bg-light text-success border border-success-subtle fw-semibold" id="prof-org-counter-badge" style="font-size: 0.7rem;">0 dipilih</span>
                                        </label>
                                        <div class="position-relative" id="prof-org-container">
                                            <div class="form-select d-flex align-items-center flex-wrap gap-1" id="prof-org-trigger" tabindex="0" style="min-height: 38px; height: auto; cursor: pointer; user-select: none;">
                                                <div id="prof-org-badges-wrapper" class="d-flex align-items-center flex-wrap gap-1">
                                                    <span class="text-muted small" id="prof-org-placeholder-text">-- Klik untuk memilih Organisasi / Ormawa --</span>
                                                </div>
                                            </div>
                                            <div class="shadow" id="prof-org-dropdown-menu" style="display: none; position: absolute; top: calc(100% + 4px); left: 0; right: 0; background: #ffffff; border: 1.5px solid #cbd5e1; border-radius: 12px; z-index: 1050; max-height: 280px; overflow: hidden;">
                                                <div class="p-2 border-bottom bg-light">
                                                    <input type="text" id="prof-org-search-box" class="form-control form-control-sm" placeholder="Cari BEM, HIMA, atau UKM..." autocomplete="off">
                                                </div>
                                                <div class="p-1" id="prof-org-options-scroll" style="max-height: 190px; overflow-y: auto;">
                                                    @php
                                                        $userOrgArr = $user->organisasi ? array_map('trim', explode(',', $user->organisasi)) : [];
                                                    @endphp
                                                    @if(isset($organisasi))
                                                        @foreach($organisasi as $kategori => $daftarOrg)
                                                            <div class="dropdown-category-title">{{ $kategori }}</div>
                                                            @foreach($daftarOrg as $orgKey => $orgLabel)
                                                                @php
                                                                    $isProfChecked = (is_array(old('organisasi')) && in_array($orgKey, old('organisasi'))) || 
                                                                                    (!old('organisasi') && in_array($orgKey, $userOrgArr));
                                                                @endphp
                                                                <label class="prof-org-checkbox-item d-flex align-items-center px-3 py-1-5 cursor-pointer" data-search="{{ strtolower($orgKey . ' ' . $orgLabel . ' ' . $kategori) }}" style="cursor: pointer; user-select: none;">
                                                                    <input type="checkbox" name="organisasi[]" value="{{ $orgKey }}" class="form-check-input prof-org-item-checkbox me-2 mt-0" data-label="{{ $orgLabel }}" {{ $isProfChecked ? 'checked' : '' }}>
                                                                    <span class="small">{{ $orgLabel }}</span>
                                                                </label>
                                                            @endforeach
                                                        @endforeach
                                                    @endif
                                                </div>
                                                <div class="p-2 border-top bg-light d-flex justify-content-between align-items-center">
                                                    <button type="button" class="btn btn-link btn-sm text-danger text-decoration-none p-0 small" id="btn-reset-prof-org" style="font-size: 0.76rem;">Reset Pilihan</button>
                                                    <button type="button" class="btn btn-primary btn-sm px-3 py-1" id="btn-done-prof-org" style="font-size: 0.76rem;">Selesai</button>
                                                </div>
                                            </div>
                                        </div>
                                        @error('organisasi')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <label for="jabatan" class="form-label mb-0">Jabatan di Organisasi</label>
                                            <span id="prof-jabatan-context-hint" class="small text-muted" style="font-size: 0.75rem;">Sesuai SK Pengurus 2026-2027</span>
                                        </div>
                                        <select class="form-select @error('jabatan') is-invalid @enderror" id="jabatan" name="jabatan">
                                            <option value="">-- Pilih Jabatan / Posisi --</option>
                                            @if(isset($jabatan))
                                                @foreach($jabatan as $groupLabel => $items)
                                                    <optgroup label="{{ $groupLabel }}">
                                                        @foreach($items as $val => $text)
                                                            <option value="{{ $val }}" {{ old('jabatan', $user->jabatan) == $val ? 'selected' : '' }}>{{ $text }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endforeach
                                            @endif
                                        </select>
                                        <div id="prof-wrapper-jabatan-custom" class="mt-2" style="display: {{ old('jabatan', $user->jabatan) === 'Lainnya' ? 'block' : 'none' }};">
                                            <input type="text" name="jabatan_custom" id="prof_jabatan_custom" class="form-control form-control-sm" placeholder="Ketik jabatan / departemen spesifik..." value="{{ old('jabatan_custom') }}">
                                            <span class="form-text small text-muted" style="font-size: 0.74rem;">Contoh: <em>Kepala Departemen Advokasi dan Kajian Hukum</em> atau <em>Pimpinan Redaksi</em></span>
                                        </div>
                                        @error('jabatan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12 text-end mt-4">
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="bi bi-save me-1"></i> Simpan Perubahan Profil
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Change Password Tab -->
                            <div class="tab-pane fade profile-change-password" id="profile-change-password"
                                role="tabpanel">
                                <form action="{{ route('password.update') }}" method="POST" class="row g-3">
                                    @csrf
                                    @method('PUT')

                                    <div class="col-12">
                                        <label for="current_password" class="form-label">Password Saat Ini <span
                                                class="text-danger">*</span></label>
                                        <input type="password"
                                            class="form-control @error('current_password') is-invalid @enderror"
                                            id="current_password" name="current_password"
                                            placeholder="Masukkan password saat ini" required>
                                        @error('current_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="password" class="form-label">Password Baru <span
                                                class="text-danger">*</span></label>
                                        <input type="password"
                                            class="form-control @error('password') is-invalid @enderror" id="password"
                                            name="password" placeholder="Minimal 8 karakter" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="password_confirmation" class="form-label">Konfirmasi Password Baru
                                            <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation" placeholder="Ulangi password baru" required>
                                    </div>

                                    <div class="col-12 text-end mt-4">
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="bi bi-shield-lock me-1"></i> Perbarui Password
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- KTM / KTA Tab -->
                            <div class="tab-pane fade" id="profile-ktm" role="tabpanel">
                                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 pb-2 border-bottom gap-2">
                                    <div>
                                        <h5 class="card-title p-0 m-0">Kartu Tanda Anggota (KTM / KTA)</h5>
                                        <small class="text-muted">Data pada kartu diperbarui otomatis sesuai profil Anda.</small>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="printKtaCard()">
                                            <i class="bi bi-printer me-1"></i> Cetak Kartu
                                        </button>
                                        <button type="button" class="btn btn-success btn-sm" id="btn-download-kta" onclick="downloadKtaCard()">
                                            <i class="bi bi-download me-1"></i> Unduh Gambar (PNG)
                                        </button>
                                    </div>
                                </div>

                                <!-- Card Preview Wrapper -->
                                <div class="d-flex justify-content-center p-2 p-md-3 bg-light rounded-3 border">
                                    <div id="kta-card-print-area" class="kta-card-container">
                                        <div id="kta-card" class="kta-card-body">
                                            <!-- Top Header: Logo Prodi / BEM Emblem & Title -->
                                            <div class="kta-bem-header">
                                                @if(isset($logoProdi) && $logoProdi)
                                                    <img src="{{ $logoProdi->logo_url }}" alt="{{ $logoProdi->nama_organisasi ?? $logoProdi->program_studi }}" class="kta-bem-logo" style="object-fit: contain; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));">
                                                    <div class="kta-bem-title">
                                                        <span class="kta-bem-big">{{ $logoProdi->singkatan ?? 'ORMAWA' }}</span>
                                                        <span class="kta-bem-sub">{{ $logoProdi->nama_organisasi ?? $logoProdi->program_studi }}</span>
                                                        <span class="kta-bem-sub">UNIVERSITAS IBNU SINA</span>
                                                    </div>
                                                @else
                                                    <svg class="kta-bem-logo" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <circle cx="50" cy="50" r="46" stroke="#004724" stroke-width="3" fill="#ffffff" />
                                                        <circle cx="50" cy="50" r="41" stroke="#004724" stroke-width="1" stroke-dasharray="2 2" fill="#ffffff" />
                                                        <path id="curve" d="M 18,52 A 32,32 0 1,1 82,52" fill="transparent"/>
                                                        <text font-size="7.2" font-weight="900" fill="#004724" letter-spacing="1">
                                                            <textPath href="#curve" startOffset="50%" text-anchor="middle">BADAN EKSEKUTIF MAHASISWA</textPath>
                                                        </text>
                                                        <circle cx="50" cy="50" r="19" fill="#004724" />
                                                        <polygon points="50,37 53,45 61,45 55,50 57,58 50,53 43,58 45,50 39,45 47,45" fill="#facc15" />
                                                        <text x="50" y="74" font-size="5.8" font-weight="800" fill="#004724" text-anchor="middle" font-family="'Poppins', sans-serif">UNIVERSITAS IBNU SINA</text>
                                                    </svg>
                                                    <div class="kta-bem-title">
                                                        <span class="kta-bem-big">BEM</span>
                                                        <span class="kta-bem-sub">BADAN EKSEKUTIF MAHASISWA</span>
                                                        <span class="kta-bem-sub">UNIVERSITAS IBNU SINA</span>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Green Banner Titles -->
                                            <div class="kta-banner-left">
                                                <div class="kta-banner-main">KARTU TANDA ANGGOTA</div>
                                                <div class="kta-banner-sub">{{ (isset($logoProdi) && $logoProdi && $logoProdi->nama_organisasi) ? $logoProdi->nama_organisasi : 'BEM UNIVERSITAS IBNU SINA' }}</div>
                                            </div>

                                            <div class="kta-banner-right">
                                                <div class="kta-banner-period-label">PERIODE</div>
                                                <div class="kta-banner-period-year">2026 – 2027</div>
                                            </div>

                                            <!-- User Photo Profile -->
                                            <div class="kta-photo-wrapper">
                                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="kta-photo-img">
                                            </div>

                                            <!-- User Info Table -->
                                            <div class="kta-info-table">
                                                <table class="kta-table">
                                                    <tr>
                                                        <td class="kta-label">Nama</td>
                                                        <td class="kta-colon">:</td>
                                                        <td class="kta-value">{{ $user->name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="kta-label">NIM</td>
                                                        <td class="kta-colon">:</td>
                                                        <td class="kta-value">{{ $user->nim ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="kta-label">Fakultas</td>
                                                        <td class="kta-colon">:</td>
                                                        <td class="kta-value">{{ $user->fakultas ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="kta-label">Program Studi</td>
                                                        <td class="kta-colon">:</td>
                                                        <td class="kta-value">{{ $user->program_studi ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="kta-label">Jabatan</td>
                                                        <td class="kta-colon">:</td>
                                                        <td class="kta-value">
                                                            @if ($user->jabatan && $user->organisasi)
                                                                {{ $user->jabatan }} - {{ $user->organisasi }}
                                                            @elseif ($user->jabatan)
                                                                {{ $user->jabatan }}
                                                            @elseif ($user->organisasi)
                                                                {{ $user->organisasi }}
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>

                                            <!-- QR Code Section -->
                                            <div class="kta-qrcode-section">
                                                <div class="kta-qrcode-box">
                                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode('VERIFIKASI-KTA-BEM-UIS|' . ($user->nim ?? $user->id) . '|' . $user->name) }}"
                                                        alt="QR Code" class="kta-qrcode-img">
                                                </div>
                                                <div class="kta-qrcode-text">
                                                    <strong>Scan QR Code</strong>
                                                    <span>untuk verifikasi keanggotaan BEM Universitas Ibnu Sina</span>
                                                </div>
                                            </div>

                                            <!-- Masa Berlaku Text inside green pill -->
                                            <div class="kta-expiry-section">
                                                <span class="kta-expiry-label">Masa Berlaku:</span>
                                                <span class="kta-expiry-val">Tahun Kepengurusan 2026 – 2027</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-light border mt-3 d-flex align-items-center mb-0 py-2 small">
                                    <i class="bi bi-info-circle text-primary fs-5 me-2"></i>
                                    <div>
                                        Jika data profil (Nama, Foto, NIM, Fakultas, Prodi, atau Jabatan) diperbarui pada tab <strong>Edit Profil</strong>, Kartu Tanda Anggota ini akan otomatis ikut terupdate.
                                    </div>
                                </div>
                            </div>

                        </div><!-- End Bordered Tabs -->

                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection

@push('scripts')
    <!-- HTML2Canvas CDN for high-quality card download -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <style>
        /* Multi-select Pills Styling */
        .org-badge-pill {
            background-color: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
            border-radius: 6px;
            padding: 0.1rem 0.45rem;
            font-size: 0.72rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            max-width: 170px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .org-badge-pill .pill-remove {
            color: #059669;
            cursor: pointer;
            font-size: 0.8rem;
            line-height: 1;
        }

        .org-badge-pill .pill-remove:hover {
            color: #dc2626;
        }

        .org-badge-more {
            background-color: #f1f5f9;
            color: #475569;
            border-radius: 6px;
            padding: 0.1rem 0.4rem;
            font-size: 0.7rem;
            font-weight: 700;
        }

        .dropdown-category-title {
            font-size: 0.7rem;
            font-weight: 700;
            color: #046B26;
            background: #f0fdf4;
            padding: 0.3rem 0.85rem;
            margin: 0.2rem 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-top: 1px solid #edf2f7;
            border-bottom: 1px solid #edf2f7;
        }

        .dropdown-category-title:first-child {
            margin-top: 0;
            border-top: none;
        }

        .prof-org-checkbox-item:hover {
            background-color: #f8fafc;
        }

        /* KTM / KTA Card Responsive Styling */
        .kta-card-container {
            width: 100%;
            max-width: 720px;
            margin: 0 auto;
        }

        .kta-card-body {
            position: relative;
            width: 100%;
            aspect-ratio: 2148 / 1358;
            background-image: url('{{ asset('assets/img/ktm.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            container-type: inline-size;
            user-select: none;
            -webkit-user-select: none;
        }

        /* Top Header BEM */
        .kta-bem-header {
            position: absolute;
            top: 4.4%;
            left: 32%;
            display: flex;
            align-items: center;
            gap: 1.1cqw;
            max-height: 19%;
        }

        .kta-bem-logo {
            width: 8.5cqw;
            height: 8.5cqw;
            max-width: 8.5cqw;
            max-height: 8.5cqw;
            object-fit: contain;
            flex-shrink: 0;
            display: block;
        }

        .kta-bem-title {
            display: flex;
            flex-direction: column;
            justify-content: center;
            line-height: 1.15;
        }

        .kta-bem-big {
            font-size: 2.5cqw;
            font-weight: 900;
            color: #004724;
            letter-spacing: 0.5px;
            font-family: 'Poppins', sans-serif;
            line-height: 1;
            margin-bottom: 0.2cqw;
        }

        .kta-bem-sub {
            font-size: 0.82cqw;
            font-weight: 800;
            color: #004724;
            letter-spacing: 0.2px;
            font-family: 'Poppins', sans-serif;
            line-height: 1.25;
        }

        /* Dark Green Banner Text */
        .kta-banner-left {
            position: absolute;
            top: 25.2%;
            left: 4.1%;
            line-height: 1.2;
            color: #ffffff;
            font-family: 'Poppins', sans-serif;
        }

        .kta-banner-main {
            font-size: 2.15cqw;
            font-weight: 900;
            letter-spacing: 0.8px;
            text-shadow: 0 1px 2px rgba(0,0,0,0.25);
        }

        .kta-banner-sub {
            font-size: 1.35cqw;
            font-weight: 700;
            color: #e6f7ef;
            letter-spacing: 0.4px;
        }

        .kta-banner-right {
            position: absolute;
            top: 25.5%;
            left: 49.2%;
            line-height: 1.2;
            color: #ffffff;
            font-family: 'Poppins', sans-serif;
        }

        .kta-banner-period-label {
            font-size: 1.1cqw;
            font-weight: 700;
            color: #d1fae5;
            letter-spacing: 1px;
        }

        .kta-banner-period-year {
            font-size: 1.55cqw;
            font-weight: 900;
            letter-spacing: 0.5px;
            color: #ffffff;
        }

        /* User Photo Profile */
        .kta-photo-wrapper {
            position: absolute;
            top: 38%;
            left: 2.5%;
            width: 16.6%;
            height: 35.5%;
            border-radius: 4px;
            border: 2.5px solid #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            background: #ffffff;
            overflow: hidden;
        }

        .kta-photo-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        /* Info Table */
        .kta-info-table {
            position: absolute;
            top: 40.5%;
            left: 21.2%;
            width: 41%;
            font-family: 'Poppins', sans-serif;
            color: #004724;
        }

        .kta-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 0.55cqw;
            font-size: 1.58cqw;
        }

        .kta-label {
            font-weight: 800;
            width: 32%;
            vertical-align: top;
            white-space: nowrap;
            color: #004724;
        }

        .kta-colon {
            font-weight: 800;
            width: 4%;
            vertical-align: top;
            color: #004724;
        }

        .kta-value {
            font-weight: 800;
            vertical-align: top;
            color: #00361b;
            word-break: break-word;
        }

        /* QR Code */
        .kta-qrcode-section {
            position: absolute;
            top: 75.5%;
            left: 6.8%;
            display: flex;
            align-items: center;
            gap: 0.85cqw;
        }

        .kta-qrcode-box {
            background: #ffffff;
            padding: 0.35cqw;
            border-radius: 4px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.12);
            line-height: 0;
            flex-shrink: 0;
        }

        .kta-qrcode-img {
            width: 8.6cqw;
            height: 8.6cqw;
            display: block;
        }

        .kta-qrcode-text {
            color: #004724;
            font-family: 'Poppins', sans-serif;
            line-height: 1.2;
            display: flex;
            flex-direction: column;
            max-width: 14cqw;
        }

        .kta-qrcode-text strong {
            font-size: 0.98cqw;
            font-weight: 800;
        }

        .kta-qrcode-text span {
            font-size: 0.74cqw;
            font-weight: 500;
        }

        /* Expiry Pill */
        .kta-expiry-section {
            position: absolute;
            top: 82.1%;
            transform: translateY(-50%);
            left: 41.5%;
            color: #ffffff;
            font-family: 'Poppins', sans-serif;
            display: flex;
            flex-direction: column;
            justify-content: center;
            line-height: 1.2;
        }

        .kta-expiry-label {
            font-size: 0.9cqw;
            font-weight: 600;
            color: #d1fae5;
            opacity: 0.95;
            margin-bottom: 0.1cqw;
        }

        .kta-expiry-val {
            font-size: 1.25cqw;
            font-weight: 800;
            letter-spacing: 0.2px;
            color: #ffffff;
        }

        /* Print Specific Styling */
        @media print {
            body * {
                visibility: hidden !important;
            }

            #kta-card-print-area,
            #kta-card-print-area * {
                visibility: visible !important;
            }

            #kta-card-print-area {
                position: fixed !important;
                left: 50% !important;
                top: 50% !important;
                transform: translate(-50%, -50%) !important;
                width: 85.6mm !important;
                height: 54mm !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border-radius: 3mm !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .kta-card-body {
                width: 85.6mm !important;
                height: 54mm !important;
                box-shadow: none !important;
                border-radius: 3mm !important;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const photoInput = document.getElementById('photo_profile');
            const previewImg = document.getElementById('avatar-input-preview');
            const cardPreviewImg = document.getElementById('profile-preview-card');
            const removeCheckbox = document.getElementById('remove_photo');
            const originalSrc = previewImg ? previewImg.src : '';

            if (photoInput) {
                photoInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        if (removeCheckbox) {
                            removeCheckbox.checked = false;
                        }
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            if (previewImg) previewImg.src = event.target.result;
                            if (cardPreviewImg) cardPreviewImg.src = event.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            if (removeCheckbox) {
                removeCheckbox.addEventListener('change', function() {
                    if (this.checked) {
                        if (photoInput) photoInput.value = '';
                        const defaultAvatar =
                            "https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=cbd5e1&color=334155";
                        if (previewImg) previewImg.src = defaultAvatar;
                        if (cardPreviewImg) cardPreviewImg.src = defaultAvatar;
                    } else {
                        if (previewImg) previewImg.src = originalSrc;
                        if (cardPreviewImg) cardPreviewImg.src = originalSrc;
                    }
                });
            }

            // Dynamic filter Fakultas -> Program Studi in Profile
            const profileFakultas = document.getElementById('fakultas');
            const profileProdi = document.getElementById('program_studi');

            if (profileFakultas && profileProdi) {
                profileFakultas.addEventListener('change', function() {
                    const selFak = this.value;
                    const optgroups = profileProdi.querySelectorAll('optgroup');
                    const options = profileProdi.querySelectorAll('option[data-fakultas]');

                    if (!selFak) {
                        optgroups.forEach(og => og.style.display = '');
                        options.forEach(opt => opt.style.display = '');
                        return;
                    }

                    optgroups.forEach(og => {
                        og.style.display = (og.getAttribute('data-fakultas') === selFak) ? '' : 'none';
                    });
                    options.forEach(opt => {
                        opt.style.display = (opt.getAttribute('data-fakultas') === selFak) ? '' : 'none';
                    });

                    const currentSelected = profileProdi.selectedOptions[0];
                    if (currentSelected && currentSelected.getAttribute('data-fakultas') && currentSelected.getAttribute('data-fakultas') !== selFak) {
                        profileProdi.value = '';
                    }
                });

                profileProdi.addEventListener('change', function() {
                    const opt = this.selectedOptions[0];
                    if (opt && opt.getAttribute('data-fakultas')) {
                        profileFakultas.value = opt.getAttribute('data-fakultas');
                    }
                });
            }

            // Profile Organisasi Multi-select Logic
            const profOrgTrigger = document.getElementById('prof-org-trigger');
            const profOrgDropdownMenu = document.getElementById('prof-org-dropdown-menu');
            const profOrgBadgesWrapper = document.getElementById('prof-org-badges-wrapper');
            const profOrgPlaceholder = document.getElementById('prof-org-placeholder-text');
            const profOrgCounterBadge = document.getElementById('prof-org-counter-badge');
            const profOrgCheckboxes = document.querySelectorAll('.prof-org-item-checkbox');
            const profOrgSearchBox = document.getElementById('prof-org-search-box');
            const btnResetProfOrg = document.getElementById('btn-reset-prof-org');
            const btnDoneProfOrg = document.getElementById('btn-done-prof-org');
            const profOrgContainer = document.getElementById('prof-org-container');

            function updateProfOrgDisplay() {
                const checked = Array.from(profOrgCheckboxes).filter(cb => cb.checked);
                profOrgBadgesWrapper.innerHTML = '';

                if (checked.length === 0) {
                    profOrgBadgesWrapper.appendChild(profOrgPlaceholder);
                    profOrgCounterBadge.textContent = '0 dipilih';
                    profOrgCounterBadge.className = 'badge bg-light text-muted border fw-semibold';
                } else {
                    profOrgCounterBadge.textContent = checked.length + ' dipilih';
                    profOrgCounterBadge.className = 'badge bg-success-subtle text-success border border-success-subtle fw-semibold';

                    const maxDisplay = 2;
                    checked.slice(0, maxDisplay).forEach(cb => {
                        const pill = document.createElement('span');
                        pill.className = 'org-badge-pill';
                        const shortText = cb.getAttribute('data-label').split('(')[0].trim();
                        pill.innerHTML = `<span>${shortText}</span><i class="bi bi-x pill-remove" data-val="${cb.value}"></i>`;
                        profOrgBadgesWrapper.appendChild(pill);
                    });

                    if (checked.length > maxDisplay) {
                        const moreBadge = document.createElement('span');
                        moreBadge.className = 'org-badge-more';
                        moreBadge.textContent = `+${checked.length - maxDisplay} lainnya`;
                        profOrgBadgesWrapper.appendChild(moreBadge);
                    }
                }
            }

            if (profOrgTrigger && profOrgDropdownMenu) {
                profOrgTrigger.addEventListener('click', function(e) {
                    if (e.target.classList.contains('pill-remove')) {
                        const val = e.target.getAttribute('data-val');
                        const targetCb = document.querySelector(`.prof-org-item-checkbox[value="${CSS.escape(val)}"]`);
                        if (targetCb) {
                            targetCb.checked = false;
                            updateProfOrgDisplay();
                        }
                        return;
                    }
                    const isShown = profOrgDropdownMenu.style.display === 'block';
                    profOrgDropdownMenu.style.display = isShown ? 'none' : 'block';
                    if (!isShown && profOrgSearchBox) profOrgSearchBox.focus();
                });

                document.addEventListener('click', function(e) {
                    if (profOrgContainer && !profOrgContainer.contains(e.target)) {
                        profOrgDropdownMenu.style.display = 'none';
                    }
                });

                if (btnDoneProfOrg) {
                    btnDoneProfOrg.addEventListener('click', function() {
                        profOrgDropdownMenu.style.display = 'none';
                    });
                }

                if (btnResetProfOrg) {
                    btnResetProfOrg.addEventListener('click', function() {
                        profOrgCheckboxes.forEach(cb => cb.checked = false);
                        updateProfOrgDisplay();
                    });
                }

                profOrgCheckboxes.forEach(cb => {
                    cb.addEventListener('change', updateProfOrgDisplay);
                });

                if (profOrgSearchBox) {
                    profOrgSearchBox.addEventListener('input', function() {
                        const q = this.value.toLowerCase().trim();
                        document.querySelectorAll('.prof-org-checkbox-item').forEach(item => {
                            const searchTxt = item.getAttribute('data-search') || '';
                            item.style.display = searchTxt.includes(q) ? 'flex' : 'none';
                        });
                    });
                }

                updateProfOrgDisplay();

                // Dynamic Jabatan Options Logic for Profile (SK 2026-2027)
                const JABATAN_BY_ORG = @json($jabatan_by_org ?? []);
                const JABATAN_GENERAL = @json($jabatan ?? []);
                const profJabatanSelect = document.getElementById('jabatan');
                const profJabatanCustomWrapper = document.getElementById('prof-wrapper-jabatan-custom');
                const profJabatanCustomInput = document.getElementById('prof_jabatan_custom');
                const profJabatanHint = document.getElementById('prof-jabatan-context-hint');
                const userCurrentJabatan = "{{ old('jabatan', $user->jabatan ?? '') }}";

                function updateProfJabatanOptions() {
                    if (!profJabatanSelect) return;

                    const checkedBoxes = Array.from(document.querySelectorAll('.prof-org-item-checkbox:checked'));
                    const checkedValues = checkedBoxes.map(cb => cb.value);

                    let matchingGroups = {};
                    let hasOrgMatch = false;

                    checkedValues.forEach(orgKey => {
                        if (JABATAN_BY_ORG[orgKey]) {
                            hasOrgMatch = true;
                            matchingGroups[orgKey] = JABATAN_BY_ORG[orgKey];
                        }
                    });

                    const currentVal = profJabatanSelect.value || userCurrentJabatan;
                    profJabatanSelect.innerHTML = '<option value="">-- Pilih Jabatan / Posisi --</option>';

                    if (hasOrgMatch) {
                        if (profJabatanHint) {
                            profJabatanHint.innerHTML = '<span class="text-success fw-semibold"><i class="bi bi-shield-check me-1"></i>Sesuai SK Pengurus 2026-2027</span>';
                        }

                        for (const [orgName, groups] of Object.entries(matchingGroups)) {
                            const shortOrgName = orgName.split(' - ')[0].replace('Universitas Ibnu Sina', 'UIS').trim();
                            for (const [groupName, items] of Object.entries(groups)) {
                                const optgroup = document.createElement('optgroup');
                                optgroup.label = `${shortOrgName} • ${groupName}`;
                                items.forEach(item => {
                                    const opt = document.createElement('option');
                                    opt.value = item;
                                    opt.textContent = item;
                                    if (item === currentVal) {
                                        opt.selected = true;
                                    }
                                    optgroup.appendChild(opt);
                                });
                                profJabatanSelect.appendChild(optgroup);
                            }
                        }
                    } else {
                        if (profJabatanHint) {
                            profJabatanHint.textContent = 'Pilihan Jabatan Standar';
                        }
                        for (const [groupName, items] of Object.entries(JABATAN_GENERAL)) {
                            const optgroup = document.createElement('optgroup');
                            optgroup.label = groupName;
                            for (const [val, label] of Object.entries(items)) {
                                const opt = document.createElement('option');
                                opt.value = val;
                                opt.textContent = label;
                                if (val === currentVal) {
                                    opt.selected = true;
                                }
                                optgroup.appendChild(opt);
                            }
                            profJabatanSelect.appendChild(optgroup);
                        }
                    }

                    // Selalu tambahkan opsi Lainnya
                    const hasLainnya = Array.from(profJabatanSelect.options).some(o => o.value === 'Lainnya');
                    if (!hasLainnya) {
                        const optLainnya = document.createElement('option');
                        optLainnya.value = 'Lainnya';
                        optLainnya.textContent = 'Lainnya (Ketik Manual)...';
                        if (currentVal === 'Lainnya') {
                            optLainnya.selected = true;
                        }
                        profJabatanSelect.appendChild(optLainnya);
                    }

                    // Pertahankan jika user memiliki jabatan custom
                    if (currentVal && currentVal !== 'Lainnya' && !Array.from(profJabatanSelect.options).some(o => o.value === currentVal)) {
                        const customOpt = document.createElement('option');
                        customOpt.value = currentVal;
                        customOpt.textContent = currentVal;
                        customOpt.selected = true;
                        profJabatanSelect.insertBefore(customOpt, profJabatanSelect.lastElementChild);
                    }

                    checkProfCustomJabatan();
                }

                function checkProfCustomJabatan() {
                    if (!profJabatanSelect || !profJabatanCustomWrapper) return;
                    if (profJabatanSelect.value === 'Lainnya') {
                        profJabatanCustomWrapper.style.display = 'block';
                        if (profJabatanCustomInput && !profJabatanCustomInput.value) {
                            profJabatanCustomInput.focus();
                        }
                    } else {
                        profJabatanCustomWrapper.style.display = 'none';
                    }
                }

                if (profJabatanSelect) {
                    profJabatanSelect.addEventListener('change', checkProfCustomJabatan);
                }

                profOrgCheckboxes.forEach(cb => {
                    cb.addEventListener('change', updateProfJabatanOptions);
                });
                if (btnResetProfOrg) {
                    btnResetProfOrg.addEventListener('click', function() {
                        setTimeout(updateProfJabatanOptions, 10);
                    });
                }

                updateProfJabatanOptions();
            }
        });

        // Print Card
        function printKtaCard() {
            window.print();
        }

        // Download Card as PNG using html2canvas
        function downloadKtaCard() {
            const card = document.getElementById('kta-card');
            const btn = document.getElementById('btn-download-kta');
            if (!card) return;

            const originalBtnHtml = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Memproses...';
            btn.disabled = true;

            html2canvas(card, {
                scale: 3,
                useCORS: true,
                allowTaint: true,
                backgroundColor: null,
            }).then(function(canvas) {
                const link = document.createElement('a');
                link.download = 'KTM_BEM_{{ Str::slug($user->name, "_") }}.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            }).catch(function(err) {
                console.error('Download card error:', err);
                alert('Gagal mendownload kartu. Anda dapat menggunakan fitur Cetak Kartu.');
            }).finally(function() {
                btn.innerHTML = originalBtnHtml;
                btn.disabled = false;
            });
        }
    </script>
@endpush
