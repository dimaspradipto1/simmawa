@extends('layouts.dashboard.template')

@section('content')
<div class="pagetitle">
    <h1>Pengaturan Kartu Tanda Anggota (KTA / KTM)</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Pengaturan</li>
            <li class="breadcrumb-item active">Pengaturan KTA / KTM</li>
        </ol>
    </nav>
    <p class="text-muted small">Atur tahun periode kepengurusan, teks masa berlaku kartu, dan template latar belakang Kartu Tanda Anggota (KTM / KTA) mahasiswa.</p>
</div><!-- End Page Title -->

<section class="section">
    <!-- Header Soft Green Info Banner -->
    <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 mb-2">
                        <i class="bi bi-card-heading me-1"></i> Pengaturan Periode & Kartu Ormawa
                    </span>
                    <h5 class="fw-bold text-dark mb-1">Konfigurasi Kartu Tanda Anggota (KTA / KTM)</h5>
                    <p class="text-secondary small mb-0">Perubahan periode kepengurusan dan teks masa berlaku di halaman ini akan otomatis diterapkan ke seluruh kartu KTA mahasiswa pada profil mereka.</p>
                </div>
                <div class="flex-shrink-0">
                    <a href="{{ route('profile.show') }}" target="_blank" class="btn btn-outline-success px-3 py-2 rounded-2 shadow-sm d-inline-flex align-items-center bg-white">
                        <i class="bi bi-box-arrow-up-right me-1"></i> Buka Halaman Profil
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Form Pengaturan (Kolom Kiri) -->
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                        <h5 class="card-title fw-bold m-0 p-0 text-dark">
                            <i class="bi bi-sliders me-1 text-primary"></i> Form Pengaturan Periode
                        </h5>
                        <span class="badge bg-light text-muted border small">Tersimpan</span>
                    </div>

                    <form action="{{ route('kta-setting.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Periode Kepengurusan -->
                        <div class="mb-3">
                            <label for="tahun_periode" class="form-label fw-semibold text-dark">
                                Tahun Periode <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-calendar3"></i></span>
                                <input type="text" 
                                       class="form-control @error('tahun_periode') is-invalid @enderror" 
                                       id="tahun_periode" 
                                       name="tahun_periode" 
                                       value="{{ old('tahun_periode', $setting->tahun_periode) }}" 
                                       placeholder="Contoh: 2026 – 2027" 
                                       required>
                            </div>
                            <small class="text-muted d-block mt-1">Ditampilkan pada kotak hijau atas kanan kartu: <strong>PERIODE 2026 – 2027</strong>.</small>
                            @error('tahun_periode')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Label Periode -->
                        <div class="mb-3">
                            <label for="label_periode" class="form-label fw-semibold text-dark">
                                Label Periode <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('label_periode') is-invalid @enderror" 
                                   id="label_periode" 
                                   name="label_periode" 
                                   value="{{ old('label_periode', $setting->label_periode) }}" 
                                   placeholder="PERIODE" 
                                   required>
                            @error('label_periode')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Teks Masa Berlaku -->
                        <div class="mb-3">
                            <label for="teks_masa_berlaku" class="form-label fw-semibold text-dark">
                                Teks Masa Berlaku <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-calendar-check"></i></span>
                                <input type="text" 
                                       class="form-control @error('teks_masa_berlaku') is-invalid @enderror" 
                                       id="teks_masa_berlaku" 
                                       name="teks_masa_berlaku" 
                                       value="{{ old('teks_masa_berlaku', $setting->teks_masa_berlaku) }}" 
                                       placeholder="Contoh: Tahun Kepengurusan 2026 – 2027" 
                                       required>
                            </div>
                            <small class="text-muted d-block mt-1">Ditampilkan pada kapsul hijau bawah kartu (samping ikon kalender).</small>
                            @error('teks_masa_berlaku')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Label Masa Berlaku -->
                        <div class="mb-3">
                            <label for="label_masa_berlaku" class="form-label fw-semibold text-dark">
                                Label Masa Berlaku <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('label_masa_berlaku') is-invalid @enderror" 
                                   id="label_masa_berlaku" 
                                   name="label_masa_berlaku" 
                                   value="{{ old('label_masa_berlaku', $setting->label_masa_berlaku) }}" 
                                   placeholder="Masa Berlaku:" 
                                   required>
                            @error('label_masa_berlaku')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nama Universitas -->
                        <div class="mb-3">
                            <label for="nama_universitas" class="form-label fw-semibold text-dark">
                                Nama Universitas <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('nama_universitas') is-invalid @enderror" 
                                   id="nama_universitas" 
                                   name="nama_universitas" 
                                   value="{{ old('nama_universitas', $setting->nama_universitas) }}" 
                                   placeholder="UNIVERSITAS IBNU SINA" 
                                   required>
                            @error('nama_universitas')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Upload Background KTA (Opsional) -->
                        <div class="mb-3 p-3 bg-light rounded-3 border">
                            <label for="background_card" class="form-label fw-semibold text-dark d-flex align-items-center justify-content-between">
                                <span><i class="bi bi-image me-1"></i> Template Background Kartu</span>
                                <span class="badge bg-secondary-subtle text-secondary small">Opsional</span>
                            </label>
                            <input type="file" 
                                   class="form-control form-control-sm @error('background_card') is-invalid @enderror" 
                                   id="background_card" 
                                   name="background_card" 
                                   accept="image/jpeg,image/png,image/jpg,image/webp">
                            <small class="text-muted d-block mt-1">Kosongkan jika ingin memakai template bawaan kartu UIS (ktm.png). Format: PNG / JPG, rasio kartu 85.6 × 54 mm.</small>
                            @error('background_card')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                            @if($setting->background_card)
                                <div class="form-check mt-2 pt-2 border-top">
                                    <input class="form-check-input" type="checkbox" name="remove_background" id="remove_background" value="1">
                                    <label class="form-check-label small text-danger fw-semibold" for="remove_background">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset ke background template bawaan
                                    </label>
                                </div>
                            @endif
                        </div>

                        <div class="mt-4 pt-2 border-top text-end">
                            <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold">
                                <i class="bi bi-check-lg me-1"></i> Simpan Perubahan Pengaturan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Live Preview Kartu (Kolom Kanan) -->
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                        <div>
                            <h5 class="card-title fw-bold m-0 p-0 text-dark">
                                <i class="bi bi-eye me-1 text-success"></i> Live Preview Kartu Anggota
                            </h5>
                            <small class="text-muted">Simulasi tampilan kartu secara langsung saat teks atau periode diubah.</small>
                        </div>
                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                            Realtime Preview
                        </span>
                    </div>

                    <!-- Card Container Live Preview -->
                    <div class="kta-card-container preview-mode p-2 p-md-3 bg-light rounded-3 border">
                        <div id="live-card-body" class="kta-card-body" style="background-image: url('{{ $setting->background_url }}');">
                            <!-- Top Header: Logo & Title -->
                            <div class="kta-bem-header">
                                <img src="{{ $sampleLogo ? $sampleLogo->logo_url : asset('assets/img/logo_uis.png') }}" 
                                     alt="Logo" 
                                     class="kta-bem-logo" 
                                     style="object-fit: contain; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));">
                                <div class="kta-bem-title">
                                    <span class="kta-bem-big">{{ $sampleLogo->singkatan ?? 'BEM' }}</span>
                                    <span class="kta-bem-sub">{{ $sampleLogo->nama_organisasi ?? 'BADAN EKSEKUTIF MAHASISWA' }}</span>
                                    <span class="kta-bem-sub" id="preview-univ-name">{{ $setting->nama_universitas }}</span>
                                </div>
                            </div>

                            <!-- Green Banner Titles -->
                            <div class="kta-banner-left">
                                <div class="kta-banner-main">KARTU TANDA ANGGOTA</div>
                                <div class="kta-banner-sub">{{ strtoupper($sampleLogo->nama_organisasi ?? 'BADAN EKSEKUTIF MAHASISWA') }}</div>
                            </div>

                            <div class="kta-banner-right">
                                <div class="kta-banner-period-label" id="preview-period-label">{{ $setting->label_periode }}</div>
                                <div class="kta-banner-period-year" id="preview-period-year">{{ $setting->tahun_periode }}</div>
                            </div>

                            <!-- User Photo Profile -->
                            <div class="kta-photo-wrapper">
                                <img src="{{ $sampleUser->avatar_url }}" alt="{{ $sampleUser->name }}" class="kta-photo-img">
                            </div>

                            <!-- User Info Table -->
                            <div class="kta-info-table">
                                <table class="kta-table">
                                    <tr>
                                        <td class="kta-label">Nama</td>
                                        <td class="kta-colon">:</td>
                                        <td class="kta-value">{{ $sampleUser->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="kta-label">NIM</td>
                                        <td class="kta-colon">:</td>
                                        <td class="kta-value">{{ $sampleUser->nim ?? '201055201001' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="kta-label">Fakultas</td>
                                        <td class="kta-colon">:</td>
                                        <td class="kta-value">{{ $sampleUser->fakultas ?? 'Fakultas Sains dan Teknologi' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="kta-label">Program Studi</td>
                                        <td class="kta-colon">:</td>
                                        <td class="kta-value">{{ $sampleUser->program_studi ?? 'S1 - Teknik Informatika' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="kta-label">Jabatan</td>
                                        <td class="kta-colon">:</td>
                                        <td class="kta-value fw-bold text-dark">Presiden Mahasiswa (Presma)</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- QR Code Section -->
                            <div class="kta-qrcode-section">
                                <div class="kta-qrcode-box">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=PREVIEW-UIS" 
                                         alt="QR Code" 
                                         class="kta-qrcode-img">
                                </div>
                                <div class="kta-qrcode-text">
                                    <strong>Scan QR Code</strong>
                                    <span>untuk verifikasi keanggotaan BEM UIS</span>
                                </div>
                            </div>

                            <!-- Masa Berlaku Text inside green pill -->
                            <div class="kta-expiry-section">
                                <span class="kta-expiry-label" id="preview-expiry-label">{{ $setting->label_masa_berlaku }}</span>
                                <span class="kta-expiry-val" id="preview-expiry-val">{{ $setting->teks_masa_berlaku }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-light border mt-3 d-flex align-items-center mb-0 py-2 small">
                        <i class="bi bi-info-circle text-primary fs-5 me-2"></i>
                        <div>
                            Teks pada kotak <strong>PERIODE</strong> dan <strong>Masa Berlaku</strong> di atas akan otomatis disinkronkan dengan input yang Anda ketikkan di form sebelah kiri.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<style>
    /* KTM / KTA Card Responsive Styling */
    .kta-card-container {
        width: 100%;
        max-width: 680px;
        margin: 0 auto;
    }

    .kta-card-body {
        position: relative;
        width: 100%;
        aspect-ratio: 2148 / 1358;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        container-type: inline-size;
        user-select: none;
        -webkit-user-select: none;
        transition: background-image 0.3s ease;
    }

    /* Top Header */
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

    /* Green Banner Text */
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
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputTahunPeriode = document.getElementById('tahun_periode');
        const inputLabelPeriode = document.getElementById('label_periode');
        const inputTeksMasaBerlaku = document.getElementById('teks_masa_berlaku');
        const inputLabelMasaBerlaku = document.getElementById('label_masa_berlaku');
        const inputNamaUniv = document.getElementById('nama_universitas');
        const inputBgCard = document.getElementById('background_card');

        const previewPeriodYear = document.getElementById('preview-period-year');
        const previewPeriodLabel = document.getElementById('preview-period-label');
        const previewExpiryVal = document.getElementById('preview-expiry-val');
        const previewExpiryLabel = document.getElementById('preview-expiry-label');
        const previewUnivName = document.getElementById('preview-univ-name');
        const liveCardBody = document.getElementById('live-card-body');

        if (inputTahunPeriode && previewPeriodYear) {
            inputTahunPeriode.addEventListener('input', function() {
                previewPeriodYear.textContent = this.value.trim() || '2026 – 2027';
            });
        }

        if (inputLabelPeriode && previewPeriodLabel) {
            inputLabelPeriode.addEventListener('input', function() {
                previewPeriodLabel.textContent = this.value.trim() || 'PERIODE';
            });
        }

        if (inputTeksMasaBerlaku && previewExpiryVal) {
            inputTeksMasaBerlaku.addEventListener('input', function() {
                previewExpiryVal.textContent = this.value.trim() || 'Tahun Kepengurusan 2026 – 2027';
            });
        }

        if (inputLabelMasaBerlaku && previewExpiryLabel) {
            inputLabelMasaBerlaku.addEventListener('input', function() {
                previewExpiryLabel.textContent = this.value.trim() || 'Masa Berlaku:';
            });
        }

        if (inputNamaUniv && previewUnivName) {
            inputNamaUniv.addEventListener('input', function() {
                previewUnivName.textContent = this.value.trim() || 'UNIVERSITAS IBNU SINA';
            });
        }

        // Live preview & size validation for custom background image
        if (inputBgCard && liveCardBody) {
            const originalBg = liveCardBody.style.backgroundImage;

            inputBgCard.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;

                if (file.size > 2 * 1024 * 1024) {
                    const actualMB = (file.size / (1024 * 1024)).toFixed(2);
                    inputBgCard.value = '';
                    liveCardBody.style.backgroundImage = originalBg;

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Ukuran File Terlalu Besar!',
                            html: `File background yang Anda pilih berukuran <strong>${actualMB} MB</strong>.<br>Batas maksimal ukuran file adalah <strong>2 MB</strong>.<br><br><span class="text-secondary small">Silakan pilih gambar dengan ukuran lebih kecil atau kompres terlebih dahulu.</span>`,
                            confirmButtonColor: '#046B26',
                            confirmButtonText: 'Baik, Saya Mengerti'
                        });
                    } else {
                        alert(`Ukuran file background terlalu besar (${actualMB} MB)! Maksimal 2 MB.`);
                    }
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(event) {
                    liveCardBody.style.backgroundImage = `url('${event.target.result}')`;
                };
                reader.readAsDataURL(file);
            });
        }
    });
</script>
@endpush

