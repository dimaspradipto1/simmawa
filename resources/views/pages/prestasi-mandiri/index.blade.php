@extends('layouts.dashboard.template')

@section('content')
    <div class="pagetitle">
        <h1>Prestasi Mandiri</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Daftar Prestasi Mandiri</li>
            </ol>
        </nav>
        <p class="text-muted small">Semua prestasi mandiri, peserta mahasiswa, dan dosen pendamping.</p>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <!-- SIMKATMAWA Soft Blue Info Banner -->
            <div class="col-12 mb-3">
                <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-7">
                                <span
                                    class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1 mb-2">Prestasi
                                    Mandiri</span>
                                <h5 class="fw-bold text-dark mb-1">Form terpadu untuk data prestasi dan peserta</h5>
                                <p class="text-secondary small mb-0">Gunakan tombol tambah atau edit untuk mengisi data
                                    lomba, mahasiswa, dan dosen dalam satu form.</p>
                            </div>
                            <div class="col-md-5 mt-3 mt-md-0">
                                <div class="d-flex gap-2 justify-content-md-end">
                                    <input type="text" id="filter-search" class="form-control form-control-sm bg-white"
                                        placeholder="Cari lomba, penyelenggara..." style="max-width: 200px;">
                                    <select id="filter-level" class="form-select form-select-sm bg-white"
                                        style="max-width: 140px;">
                                        <option value="">Semua Level</option>
                                        <option value="Kabupaten/Kota">Kabupaten/Kota</option>
                                        <option value="Provinsi">Provinsi</option>
                                        <option value="Nasional">Nasional</option>
                                        <option value="Internasional">Internasional</option>
                                    </select>
                                    <button type="button" id="btn-apply-filter"
                                        class="btn btn-sm btn-light border shadow-sm">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                        <div>{{ session('success') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h5 class="card-title fw-bold m-0 p-0 text-dark">Daftar Prestasi Mandiri</h5>
                                <small class="text-muted">Menampilkan data sesuai cakupan perguruan tinggi pengguna untuk
                                    kemudahan.</small>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                @php
                                    $templatesLpj = \App\Models\TemplateLpj::where('kategori', 'Prestasi Mandiri')
                                        ->where('is_active', true)
                                        ->get();
                                    if ($templatesLpj->isEmpty()) {
                                        $templatesLpj = \App\Models\TemplateLpj::where('kategori', 'Prestasi Mandiri')->get();
                                    }
                                @endphp

                                @if($templatesLpj->isEmpty())
                                    <a href="{{ route('prestasi-mandiri.template-lpj') }}" target="_blank" rel="noopener noreferrer"
                                        class="btn btn-outline-primary btn-sm px-3 rounded-2 shadow-sm d-inline-flex align-items-center"
                                        title="Download Template LPJ">
                                        <i class="bi bi-file-earmark-word me-1"></i> Download Template LPJ
                                    </a>
                                @elseif($templatesLpj->count() === 1)
                                    @php $tpl = $templatesLpj->first(); @endphp
                                    <a href="{{ $tpl->download_url }}" target="_blank" rel="noopener noreferrer"
                                        class="btn btn-outline-primary btn-sm px-3 rounded-2 shadow-sm d-inline-flex align-items-center"
                                        title="{{ $tpl->nama }}">
                                        <i class="bi {{ $tpl->tipe === 'link' ? 'bi-box-arrow-up-right' : 'bi-file-earmark-word' }} me-1"></i> {{ $tpl->nama }}
                                    </a>
                                @else
                                    <div class="dropdown">
                                        <button class="btn btn-outline-primary btn-sm px-3 rounded-2 shadow-sm d-inline-flex align-items-center dropdown-toggle" 
                                                type="button" id="dropdownIndexLpj" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-file-earmark-arrow-down me-1"></i> Unduh Template ({{ $templatesLpj->count() }})
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 py-2" aria-labelledby="dropdownIndexLpj" style="min-width: 260px;">
                                            <li><h6 class="dropdown-header text-uppercase small fw-bold text-muted pb-1">Template Prestasi Mandiri</h6></li>
                                            @foreach($templatesLpj as $tpl)
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center py-2 px-3" href="{{ $tpl->download_url }}" target="_blank" rel="noopener noreferrer">
                                                        <i class="bi {{ $tpl->tipe === 'link' ? 'bi-link-45deg text-info' : 'bi-file-earmark-word text-primary' }} fs-5 me-2 flex-shrink-0"></i>
                                                        <div class="overflow-hidden">
                                                            <span class="d-block fw-semibold text-dark text-truncate" style="font-size: 0.82rem;" title="{{ $tpl->nama }}">{{ $tpl->nama }}</span>
                                                            <small class="text-muted d-block" style="font-size: 0.72rem;">{{ $tpl->tipe === 'link' ? 'Tautan Kustom' : 'File Dokumen' }}</small>
                                                        </div>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <a href="{{ route('prestasi-mandiri.create') }}"
                                    class="btn btn-dark btn-sm px-3 rounded-2 shadow-sm"
                                    style="background-color: #0f172a; border-color: #0f172a;">
                                    <i class="bi bi-plus-lg me-1"></i>Tambah Data
                                </a>
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
    @if (app()->environment('production'))
        {!! str_replace('http:', 'https:', $dataTable->scripts()) !!}
    @else
        {!! $dataTable->scripts() !!}
    @endif
    <script>
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data prestasi mandiri ini akan dihapus permanen!",
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
