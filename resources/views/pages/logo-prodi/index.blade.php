@extends('layouts.dashboard.template')

@section('content')
<div class="pagetitle">
    <h1>Logo Program Studi</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Pengaturan</li>
            <li class="breadcrumb-item active">Logo Program Studi</li>
        </ol>
    </nav>
    <p class="text-muted small">Kelola logo resmi Himpunan / Program Studi yang akan otomatis ditampilkan pada Kartu Tanda Anggota (KTM / KTA) mahasiswa.</p>
</div><!-- End Page Title -->

<section class="section">
    <!-- Header Soft Green Info Banner -->
    <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 mb-2">
                        Integrasi KTM & KTA
                    </span>
                    <h5 class="fw-bold text-dark mb-1">Manajemen Logo Program Studi & HIMA</h5>
                    <p class="text-secondary small mb-0">Logo yang diunggah di sini akan otomatis dihubungkan ke <strong>Kartu Tanda Anggota (KTM)</strong> mahasiswa sesuai dengan Program Studi mereka.</p>
                </div>
                <div class="flex-shrink-0">
                    <a href="{{ route('logo-prodi.create') }}" class="btn btn-success px-3 py-2 rounded-2 shadow-sm d-inline-flex align-items-center">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Logo Prodi
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
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

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="card-title fw-bold m-0 p-0 text-dark">Daftar Logo Program Studi</h5>
                            <small class="text-muted">Setiap mahasiswa dengan program studi terkait akan otomatis memakai logo ini di kartu anggotanya.</small>
                        </div>
                    </div>

                    <div class="table-responsive">
                       {{ $dataTable->table([
                                'class' => 'table table-hover align-middle w-100',
                                'style' => 'width:100%; overflow-x: auto',
                            ]) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if(app()->environment('production'))
        {!! str_replace('http:', 'https:', $dataTable->scripts()) !!}
    @else
        {!! $dataTable->scripts() !!}
    @endif
    <script>
        $(document).on('click', '.btn-delete', function (e) {
            e.preventDefault();
            const form = $(this).closest('form');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Logo program studi ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
@endpush
