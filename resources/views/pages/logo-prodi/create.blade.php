@extends('layouts.dashboard.template')

@section('content')
<div class="pagetitle">
    <h1>Tambah Logo Program Studi</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('logo-prodi.index') }}">Logo Program Studi</a></li>
            <li class="breadcrumb-item active">Tambah</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold text-dark border-bottom pb-2 mb-4">
                        <i class="bi bi-patch-check me-2 text-success"></i>Form Tambah Logo Program Studi
                    </h5>

                    <form action="{{ route('logo-prodi.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                        @csrf

                        <div class="col-md-6">
                            <label for="program_studi" class="form-label fw-semibold">Program Studi <span class="text-danger">*</span></label>
                            <input list="prodi-list" class="form-control @error('program_studi') is-invalid @enderror" id="program_studi" name="program_studi" value="{{ old('program_studi') }}" placeholder="Pilih atau ketik Program Studi" required>
                            <datalist id="prodi-list">
                                @foreach($prodis as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </datalist>
                            <div class="form-text small">Pilih dari daftar atau ketik nama prodi. Contoh: <code>S1 - Teknik Informatika</code></div>
                            @error('program_studi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="fakultas" class="form-label fw-semibold">Fakultas</label>
                            <input list="fakultas-list" class="form-control @error('fakultas') is-invalid @enderror" id="fakultas" name="fakultas" value="{{ old('fakultas') }}" placeholder="Pilih atau ketik Fakultas">
                            <datalist id="fakultas-list">
                                @foreach($fakultas as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </datalist>
                            @error('fakultas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="singkatan" class="form-label fw-semibold">Singkatan / Akronim</label>
                            <input type="text" class="form-control @error('singkatan') is-invalid @enderror" id="singkatan" name="singkatan" value="{{ old('singkatan') }}" placeholder="Contoh: HIMATIF / BEM">
                            <div class="form-text small">Teks tebal di atas kartu KTM (misal: HIMATIF).</div>
                            @error('singkatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-8">
                            <label for="nama_organisasi" class="form-label fw-semibold">Nama Organisasi / HIMA</label>
                            <input type="text" class="form-control @error('nama_organisasi') is-invalid @enderror" id="nama_organisasi" name="nama_organisasi" value="{{ old('nama_organisasi') }}" placeholder="Contoh: Himpunan Mahasiswa Teknik Informatika">
                            @error('nama_organisasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Upload Logo with Preview -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">Unggah File Logo <span class="text-danger">*</span></label>
                            <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 border">
                                <div class="bg-white rounded border d-flex align-items-center justify-content-center p-2 shadow-sm" style="width: 80px; height: 80px; flex-shrink: 0;">
                                    <img id="logo-preview" src="{{ asset('assets/img/logo_uis.png') }}" alt="Preview Logo" class="img-fluid" style="max-height: 70px; object-fit: contain;">
                                </div>
                                <div class="flex-grow-1">
                                    <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo" accept="image/png, image/jpeg, image/jpg, image/webp, image/svg+xml" required>
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
                                <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Aktif (Gunakan di KTM)</option>
                                <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Non-Aktif</option>
                            </select>
                            @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="keterangan" class="form-label fw-semibold">Keterangan / Catatan <small class="text-muted">(Opsional)</small></label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="2" placeholder="Catatan tambahan mengenai logo atau organisasi">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mt-4 text-end">
                            <a href="{{ route('logo-prodi.index') }}" class="btn btn-secondary me-2">
                                <i class="bi bi-x-circle me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-success px-4">
                                <i class="bi bi-save me-1"></i> Simpan Logo Prodi
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
        const logoInput = document.getElementById('logo');
        const previewImg = document.getElementById('logo-preview');

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
