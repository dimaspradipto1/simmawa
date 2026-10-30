@extends('layouts.dashboard.template')

@section('content')
<div class="pagetitle">
    <h1>Edit Logo Organisasi & Prodi</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('logo-prodi.index') }}">Logo Ormawa & Prodi</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-4">
                        <h5 class="card-title fw-bold text-dark m-0">
                            <i class="bi bi-pencil-square me-2 text-primary"></i>Form Edit Logo Organisasi / Program Studi
                        </h5>
                        <span class="badge bg-light text-secondary border">Mendukung BEM, DPM, UKM, & HIMA</span>
                    </div>

                    <form action="{{ route('logo-prodi.update', $logoProdi->id) }}" method="POST" enctype="multipart/form-data" class="row g-3">
                        @csrf
                        @method('PUT')

                        <!-- Kategori Organisasi -->
                        <div class="col-12">
                            <label for="kategori" class="form-label fw-semibold">Kategori Organisasi <span class="text-danger">*</span></label>
                            <select class="form-select @error('kategori') is-invalid @enderror" id="kategori" name="kategori" required>
                                @foreach($kategori as $key => $label)
                                    <option value="{{ $key }}" {{ old('kategori', $logoProdi->kategori ?? 'Himpunan Mahasiswa (HIMA)') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text small" id="kategori-hint">
                                Pilih kategori organisasi kemahasiswaan UIS.
                            </div>
                            @error('kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Singkatan / Akronim -->
                        <div class="col-md-4">
                            <label for="singkatan" class="form-label fw-semibold">Singkatan / Akronim</label>
                            <input type="text" class="form-control @error('singkatan') is-invalid @enderror" id="singkatan" name="singkatan" value="{{ old('singkatan', $logoProdi->singkatan) }}" placeholder="Contoh: BEM / HMTI / SiPena">
                            <div class="form-text small">Teks tebal di atas kartu KTA (misal: BEM, UKM SiPena, HIMATIF).</div>
                            @error('singkatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nama Organisasi -->
                        <div class="col-md-8">
                            <label for="nama_organisasi" class="form-label fw-semibold">Nama Organisasi / Lembaga <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_organisasi') is-invalid @enderror" id="nama_organisasi" name="nama_organisasi" value="{{ old('nama_organisasi', $logoProdi->nama_organisasi) }}" placeholder="Contoh: Badan Eksekutif Mahasiswa / UKM SiPena / HIMA TI" required>
                            @error('nama_organisasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Program Studi -->
                        <div class="col-md-6" id="wrapper-prodi">
                            <label for="program_studi" class="form-label fw-semibold">Program Studi <span id="star-prodi" class="text-danger">*</span></label>
                            <input list="prodi-list" class="form-control @error('program_studi') is-invalid @enderror" id="program_studi" name="program_studi" value="{{ old('program_studi', $logoProdi->program_studi) }}" placeholder="Pilih atau ketik Program Studi">
                            <datalist id="prodi-list">
                                @foreach($prodis as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </datalist>
                            <div class="form-text small" id="prodi-hint">Wajib untuk HIMA. Untuk BEM/UKM otomatis mencakup seluruh prodi.</div>
                            @error('program_studi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Fakultas -->
                        <div class="col-md-6" id="wrapper-fakultas">
                            <label for="fakultas" class="form-label fw-semibold">Fakultas</label>
                            <input list="fakultas-list" class="form-control @error('fakultas') is-invalid @enderror" id="fakultas" name="fakultas" value="{{ old('fakultas', $logoProdi->fakultas) }}" placeholder="Pilih atau ketik Fakultas">
                            <datalist id="fakultas-list">
                                @foreach($fakultas as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </datalist>
                            <div class="form-text small" id="fakultas-hint">Dapat dikosongkan atau diisi Tingkat Universitas untuk UKM/BEM.</div>
                            @error('fakultas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Current & Replace Logo with Preview -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">File Logo <small class="text-muted">(Kosongkan jika tidak ingin mengubah file logo)</small></label>
                            <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 border">
                                <div class="bg-white rounded border d-flex align-items-center justify-content-center p-2 shadow-sm" style="width: 85px; height: 85px; flex-shrink: 0;">
                                    <img id="logo-preview" src="{{ $logoProdi->logo_url }}" alt="Preview Logo" class="img-fluid" style="max-height: 75px; object-fit: contain;">
                                </div>
                                <div class="flex-grow-1">
                                    <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo" accept="image/png, image/jpeg, image/jpg, image/webp, image/svg+xml">
                                    <div class="form-text small text-muted mt-1">
                                        Format: PNG (disarankan transparan), JPG, JPEG, WEBP, atau SVG. Maksimal ukuran file: 2 MB.
                                    </div>
                                    @error('logo')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="is_active" class="form-label fw-semibold">Status Logo</label>
                            <select class="form-select @error('is_active') is-invalid @enderror" id="is_active" name="is_active">
                                <option value="1" {{ old('is_active', $logoProdi->is_active ? '1' : '0') == '1' ? 'selected' : '' }}>Aktif (Tampilkan di Sistem & KTA)</option>
                                <option value="0" {{ old('is_active', $logoProdi->is_active ? '1' : '0') == '0' ? 'selected' : '' }}>Non-Aktif</option>
                            </select>
                            @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="keterangan" class="form-label fw-semibold">Keterangan / Catatan <small class="text-muted">(Opsional)</small></label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="2" placeholder="Catatan tambahan mengenai logo atau organisasi">{{ old('keterangan', $logoProdi->keterangan) }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mt-4 text-end">
                            <a href="{{ route('logo-prodi.index') }}" class="btn btn-secondary me-2">
                                <i class="bi bi-x-circle me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-1"></i> Perbarui Logo
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const kategoriSelect = document.getElementById('kategori');
        const prodiInput = document.getElementById('program_studi');
        const fakultasInput = document.getElementById('fakultas');
        const starProdi = document.getElementById('star-prodi');
        const prodiHint = document.getElementById('prodi-hint');
        const fakultasHint = document.getElementById('fakultas-hint');
        const singkatanInput = document.getElementById('singkatan');
        const namaOrgInput = document.getElementById('nama_organisasi');
        const logoInput = document.getElementById('logo');
        const previewImg = document.getElementById('logo-preview');

        function updateFormByKategori() {
            const val = kategoriSelect.value;

            if (val === 'Unit Kegiatan Mahasiswa (UKM)') {
                starProdi.style.display = 'none';
                if (!prodiInput.value) {
                    prodiInput.value = 'Semua Program Studi (Tingkat Universitas)';
                }
                if (!fakultasInput.value) {
                    fakultasInput.value = 'Tingkat Universitas';
                }
                prodiHint.innerHTML = '<span class="text-success"><i class="bi bi-info-circle me-1"></i>UKM mencakup minat & bakat mahasiswa seluruh program studi.</span>';
                fakultasHint.innerHTML = '<span class="text-muted">Tingkat Universitas (Lintas Fakultas).</span>';
            } else if (val === 'Lembaga Mahasiswa (BEM / DPM)') {
                starProdi.style.display = 'none';
                if (!prodiInput.value) {
                    prodiInput.value = 'Semua Program Studi (Tingkat Universitas)';
                }
                if (!fakultasInput.value) {
                    fakultasInput.value = 'Tingkat Universitas';
                }
                prodiHint.innerHTML = '<span class="text-primary"><i class="bi bi-info-circle me-1"></i>Lembaga Eksekutif / Legislatif Universitas menaungi seluruh mahasiswa kampus.</span>';
                fakultasHint.innerHTML = '<span class="text-muted">Tingkat Universitas Ibnu Sina.</span>';
            } else if (val === 'Lembaga Fakultas') {
                starProdi.style.display = 'none';
                prodiHint.innerHTML = '<span class="text-muted">Menaungi seluruh program studi dalam satu fakultas.</span>';
            } else {
                // HIMA
                starProdi.style.display = 'inline';
                prodiHint.textContent = 'Pilih Program Studi yang dinaungi oleh HIMA ini.';
                fakultasHint.textContent = 'Pilih Fakultas dari Program Studi tersebut.';
            }
        }

        kategoriSelect.addEventListener('change', updateFormByKategori);
        updateFormByKategori();

        // Preview Logo
        if (logoInput) {
            logoInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        if (previewImg) previewImg.src = event.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>
@endpush
