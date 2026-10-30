@extends('layouts.dashboard.template')

@section('content')
<div class="pagetitle">
    <h1>Dashboard</h1>
    <p class="text-muted small mb-0">Selamat datang di Sistem Informasi Manajemen Kemahasiswaan (SIMMAWA) Universitas Ibnu Sina.</p>
    <nav class="mt-1">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section dashboard">
    <!-- Role Welcome Banner -->
    <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); color: white;">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    @php
                        $roleLabels = [
                            'superadmin'       => 'Super Admin (Full Access)',
                            'adminbkak'        => 'Admin Utama BKAK',
                            'kabid'            => 'Kepala Bidang Kemahasiswaan',
                            'staff'            => 'Staff Pelaksana BKAK',
                            'pimpinan'         => 'Pimpinan Perguruan Tinggi',
                            'prodi'            => 'Operator Program Studi',
                            'dosenpendamping'  => 'Dosen Pendamping Mahasiswa',
                            'mahasiswa'        => 'Mahasiswa Universitas Ibnu Sina',
                        ];
                        $roleBadge = $roleLabels[auth()->user()->role] ?? strtoupper(auth()->user()->role);
                    @endphp
                    <span class="badge bg-white text-primary px-3 py-1 mb-2 fw-bold" style="font-size: 0.75rem;">
                        <i class="bi bi-shield-lock-fill me-1"></i> {{ $roleBadge }}
                    </span>
                    <h4 class="fw-bold mb-1 text-white">Selamat Datang, {{ auth()->user()->name }}!</h4>
                    <p class="mb-0 text-white-50 small">
                        @if(auth()->user()->role === 'superadmin' || auth()->user()->role === 'adminbkak')
                            Anda memiliki akses penuh untuk mengelola data user, master data, prestasi, kejuaraan, dan tata kelola SIMKATMAWA.
                        @elseif(auth()->user()->role === 'kabid')
                            Anda mengawasi supervisi, verifikasi eskalasi, dan evaluasi kinerja kemahasiswaan.
                        @elseif(auth()->user()->role === 'staff')
                            Anda bertugas melakukan verifikasi awal dan input data operasional harian kemahasiswaan.
                        @elseif(auth()->user()->role === 'pimpinan')
                            Anda memiliki akses pemantauan (Read-Only) seluruh statistik kinerja kemahasiswaan dan SIMKATMAWA.
                        @elseif(auth()->user()->role === 'prodi')
                            Anda mengelola pendataan mahasiswa dan pengajuan prestasi di tingkat Program Studi.
                        @elseif(auth()->user()->role === 'dosenpendamping')
                            Anda bertugas mendampingi dan memverifikasi awal karya & prestasi mahasiswa binaan.
                        @else
                            Anda dapat mengajukan prestasi mandiri, sertifikasi, serta memantau progres pengajuan Anda.
                        @endif
                    </p>
                </div>
                <div>
                    <span class="badge bg-light text-dark px-3 py-2 border shadow-sm">
                        <i class="bi bi-clock-history text-primary me-1"></i> {{ date('d F Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Control Panel Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h6 class="fw-bold text-dark m-0 d-flex align-items-center">
                <i class="bi bi-funnel-fill text-primary me-2 fs-5"></i> Filter Analytics Dashboard
            </h6>
            @if($selectedTahun !== 'all' || $selectedProdi !== 'all' || $selectedJenis !== 'all')
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-medium" style="font-size: 0.8rem;">
                    <i class="bi bi-filter me-1"></i> Filter Aktif
                </span>
            @endif
        </div>
        <div class="card-body p-4 pt-1">
            <form action="{{ route('dashboard.index') }}" method="GET" class="row g-3 align-items-end">
                <!-- Filter Tahun -->
                <div class="col-md-3 col-sm-6">
                    <label class="form-label text-muted small fw-semibold mb-1">
                        <i class="bi bi-calendar-event me-1 text-primary"></i> Filter Tahun
                    </label>
                    <select name="tahun" class="form-select border-primary-subtle shadow-sm" style="font-size: 0.88rem;">
                        <option value="all" {{ $selectedTahun === 'all' ? 'selected' : '' }}>-- Semua Tahun --</option>
                        @foreach($availableYears as $y)
                            <option value="{{ $y }}" {{ (string)$selectedTahun === (string)$y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Program Studi -->
                <div class="col-md-4 col-sm-6">
                    <label class="form-label text-muted small fw-semibold mb-1">
                        <i class="bi bi-building me-1 text-primary"></i> Filter Program Studi
                    </label>
                    <select name="prodi" class="form-select border-primary-subtle shadow-sm" style="font-size: 0.88rem;">
                        <option value="all" {{ $selectedProdi === 'all' ? 'selected' : '' }}>-- Semua Program Studi --</option>
                        @foreach($defaultProdis as $p)
                            <option value="{{ $p }}" {{ $selectedProdi === $p ? 'selected' : '' }}>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Akademik vs Non-Akademik -->
                <div class="col-md-3 col-sm-6">
                    <label class="form-label text-muted small fw-semibold mb-1">
                        <i class="bi bi-award me-1 text-primary"></i> Jenis Prestasi
                    </label>
                    <select name="jenis" class="form-select border-primary-subtle shadow-sm" style="font-size: 0.88rem;">
                        <option value="all" {{ $selectedJenis === 'all' ? 'selected' : '' }}>-- Semua Kategori --</option>
                        <option value="Akademik" {{ strtolower($selectedJenis) === 'akademik' ? 'selected' : '' }}>Akademik</option>
                        <option value="Non-Akademik" {{ strtolower($selectedJenis) === 'non-akademik' ? 'selected' : '' }}>Non-Akademik</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="col-md-2 col-sm-6 d-flex gap-2">
                    <button type="submit" class="btn btn-primary fw-semibold w-100 shadow-sm d-inline-flex align-items-center justify-content-center text-nowrap" style="height: 38px; font-size: 0.88rem;">
                        <i class="bi bi-search me-1"></i> Terapkan
                    </button>
                    @if($selectedTahun !== 'all' || $selectedProdi !== 'all' || $selectedJenis !== 'all')
                        <a href="{{ route('dashboard.index') }}" class="btn btn-light border shadow-sm d-inline-flex align-items-center justify-content-center" style="height: 38px; width: 42px;">
                            <i class="bi bi-arrow-counterclockwise text-secondary"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Cards Grid -->
    <div class="row g-3 mb-4">
        <!-- Prestasi Mandiri -->
        <div class="col-xxl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="p-3 rounded-3 bg-primary-subtle text-primary me-3">
                            <i class="bi bi-trophy-fill fs-3"></i>
                        </div>
                        <div>
                            <span class="text-muted small text-uppercase fw-semibold d-block">Prestasi Mandiri</span>
                            <h3 class="fw-bold text-dark mb-0">{{ number_format($stats['mandiri']) }}</h3>
                            <small class="text-muted" style="font-size: 0.75rem;">Total Pengajuan</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Prestasi Belmawa -->
        <div class="col-xxl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="p-3 rounded-3 bg-warning-subtle text-warning me-3">
                            <i class="bi bi-award-fill fs-3"></i>
                        </div>
                        <div>
                            <span class="text-muted small text-uppercase fw-semibold d-block">Prestasi Belmawa</span>
                            <h3 class="fw-bold text-dark mb-0">{{ number_format($stats['belmawa']) }}</h3>
                            <small class="text-muted" style="font-size: 0.75rem;">Ajang Kemendikbud</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kejuaraan / Ajang -->
        @if(in_array(auth()->user()->role, ['superadmin', 'adminbkak', 'kabid', 'staff', 'pimpinan', 'prodi']))
        <div class="col-xxl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="p-3 rounded-3 bg-success-subtle text-success me-3">
                            <i class="bi bi-flag-fill fs-3"></i>
                        </div>
                        <div>
                            <span class="text-muted small text-uppercase fw-semibold d-block">Kejuaraan / Ajang</span>
                            <h3 class="fw-bold text-dark mb-0">{{ number_format($stats['kejuaraan']) }}</h3>
                            <small class="text-muted" style="font-size: 0.75rem;">Laporan Penyelenggara</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Rekognisi & Sertifikasi -->
        <div class="col-xxl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="p-3 rounded-3 bg-info-subtle text-info me-3">
                            <i class="bi bi-patch-check-fill fs-3"></i>
                        </div>
                        <div>
                            <span class="text-muted small text-uppercase fw-semibold d-block">Rekognisi & Sertifikasi</span>
                            <h3 class="fw-bold text-dark mb-0">{{ number_format($stats['rekognisi'] + $stats['sertifikasi']) }}</h3>
                            <small class="text-muted" style="font-size: 0.75rem;">Capaian Akademik</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Visualizations Row -->
    <div class="row g-4 mb-4">
        <!-- Chart 1: Pemetaan per Program Studi -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-dark m-0">
                        <i class="bi bi-bar-chart-line-fill text-primary me-2"></i> Pemetaan Prestasi per Program Studi
                    </h6>
                    <span class="badge bg-light text-muted border">Bar Chart</span>
                </div>
                <div class="card-body p-3">
                    <div id="chartProdi" style="min-height: 320px;"></div>
                </div>
            </div>
        </div>

        <!-- Chart 2: Komparasi Akademik vs Non-Akademik -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-dark m-0">
                        <i class="bi bi-pie-chart-fill text-warning me-2"></i> Akademik vs Non-Akademik
                    </h6>
                    <span class="badge bg-light text-muted border">Donut Chart</span>
                </div>
                <div class="card-body p-3 d-flex align-items-center justify-content-center">
                    <div id="chartJenis" class="w-100" style="min-height: 320px;"></div>
                </div>
            </div>
        </div>

        <!-- Chart 3: Tren Prestasi per Tahun -->
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-dark m-0">
                        <i class="bi bi-graph-up-arrow text-success me-2"></i> Tren Perolehan Prestasi per Tahun
                    </h6>
                    <span class="badge bg-light text-muted border">Line / Area Chart</span>
                </div>
                <div class="card-body p-3">
                    <div id="chartTahun" style="min-height: 300px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Tables Row -->
    <div class="row g-4">
        <!-- Left Side: Latest Data -->
        <div class="col-lg-8">
            <!-- Latest Kejuaraan -->
            @if(in_array(auth()->user()->role, ['superadmin', 'adminbkak', 'kabid', 'staff', 'pimpinan', 'prodi']))
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-0">
                    <h6 class="fw-bold text-dark m-0"><i class="bi bi-flag text-primary me-2"></i> Kejuaraan / Ajang Terbaru</h6>
                    <a href="{{ route('kejuaraan.index') }}" class="btn btn-sm btn-light border small">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle m-0" style="font-size: 0.85rem;">
                            <thead class="bg-light text-secondary">
                                <tr>
                                    <th class="ps-3">Nama Ajang</th>
                                    <th>Level</th>
                                    <th>Tempat</th>
                                    <th class="text-center">Tahun</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestKejuaraan as $item)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="fw-bold text-dark">{{ $item->nama_ajang }}</div>
                                            <small class="text-muted">{{ $item->jenis_penyelenggaraan }}</small>
                                        </td>
                                        <td><span class="badge bg-info text-white">{{ $item->tingkat_level }}</span></td>
                                        <td><small class="text-muted">{{ $item->tempat }}</small></td>
                                        <td class="text-center"><span class="badge bg-light text-dark border">{{ $item->tahun }}</span></td>
                                        <td class="text-center"><span class="badge bg-success px-2 py-1">{{ $item->status }}</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">Belum ada data kejuaraan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Latest Prestasi Mandiri -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-0">
                    <h6 class="fw-bold text-dark m-0"><i class="bi bi-trophy text-warning me-2"></i> Prestasi Mandiri Terbaru</h6>
                    <a href="{{ route('prestasi-mandiri.index') }}" class="btn btn-sm btn-light border small">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle m-0" style="font-size: 0.85rem;">
                            <thead class="bg-light text-secondary">
                                <tr>
                                    <th class="ps-3">Nama Kompetisi</th>
                                    <th>Peringkat</th>
                                    <th>Mahasiswa</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestMandiri as $item)
                                    @php
                                        $mhsName = '-';
                                        if (!empty($item->data_mahasiswa) && is_array($item->data_mahasiswa)) {
                                            $mhsName = $item->data_mahasiswa[0]['nama'] ?? '-';
                                        }
                                    @endphp
                                    <tr>
                                        <td class="ps-3">
                                            <div class="fw-bold text-dark">{{ $item->nama_kompetisi }}</div>
                                            <small class="text-muted">{{ $item->nama_cabang }} &bull; {{ $item->level }}</small>
                                        </td>
                                        <td><span class="badge bg-warning text-dark">{{ $item->peringkat }}</span></td>
                                        <td><small class="text-dark fw-medium">{{ $mhsName }}</small></td>
                                        <td class="text-center"><span class="badge bg-success px-2 py-1">{{ $item->status ?? 'Terverifikasi' }}</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">Belum ada pengajuan prestasi mandiri.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Quick Action & Profile Overview (4 cols) -->
        <div class="col-lg-4">
            <!-- User Profile Card -->
            <div class="card shadow-sm border-0 mb-4 text-center">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <img src="{{ auth()->user()->avatar_url }}" alt="Profile" class="rounded-circle shadow-sm" style="width: 70px; height: 70px; object-fit: cover;">
                    </div>
                    <h6 class="fw-bold text-dark mb-0">{{ auth()->user()->name }}</h6>
                    <small class="text-muted d-block mb-2">{{ auth()->user()->email }}</small>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1 fw-semibold text-uppercase" style="font-size: 0.75rem;">
                        {{ auth()->user()->role }}
                    </span>
                    <hr class="my-3">
                    <a href="{{ route('profile.show') }}" class="btn btn-sm btn-outline-primary w-100">
                        <i class="bi bi-person-gear me-1"></i> Edit Profil Saya
                    </a>
                </div>
            </div>

            <!-- Quick Action Shortcuts -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-lightning-charge text-warning me-2"></i> Akses Cepat Menu</h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('prestasi-mandiri.create') }}" class="btn btn-light border text-start py-2">
                            <i class="bi bi-plus-circle text-primary me-2"></i> Tambah Prestasi Mandiri
                        </a>
                        <a href="{{ route('prestasi-belmawa.index') }}" class="btn btn-light border text-start py-2">
                            <i class="bi bi-award text-warning me-2"></i> Lihat Prestasi Belmawa
                        </a>
                        @if(in_array(auth()->user()->role, ['superadmin', 'adminbkak', 'kabid', 'staff', 'pimpinan', 'prodi']))
                        <a href="{{ route('kejuaraan.create') }}" class="btn btn-light border text-start py-2">
                            <i class="bi bi-flag text-success me-2"></i> Tambah Kejuaraan / Ajang
                        </a>
                        @endif
                        @if(in_array(auth()->user()->role, ['superadmin', 'adminbkak', 'kabid', 'staff', 'pimpinan']))
                        <a href="{{ route('institusi.index') }}" class="btn btn-light border text-start py-2">
                            <i class="bi bi-building text-info me-2"></i> Kelola Institusi SIMKATMAWA
                        </a>
                        @endif
                        @if(in_array(auth()->user()->role, ['superadmin', 'adminbkak']))
                        <a href="{{ route('users.index') }}" class="btn btn-light border text-start py-2">
                            <i class="bi bi-people text-dark me-2"></i> Kelola User & Role
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Data from Controller
    const prodiLabels = @json($chartProdiLabels);
    const prodiSeries = @json($chartProdiSeries);
    const jenisLabels = @json($chartJenisLabels);
    const jenisSeries = @json($chartJenisSeries);
    const timelineYears = @json($timelineYears);
    const tahunSeries = @json($chartTahunSeries);

    // 1. Chart per Program Studi (Bar Chart)
    const optionsProdi = {
        series: [{
            name: 'Total Prestasi',
            data: prodiSeries
        }],
        chart: {
            type: 'bar',
            height: 320,
            toolbar: { show: false },
            fontFamily: 'Nunito, sans-serif'
        },
        plotOptions: {
            bar: {
                borderRadius: 6,
                horizontal: true,
                distributed: true,
                barHeight: '60%',
            }
        },
        colors: ['#2563eb', '#3b82f6', '#0284c7', '#06b6d4', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899'],
        dataLabels: {
            enabled: true,
            style: { colors: ['#fff'], fontSize: '12px', fontWeight: 'bold' }
        },
        xaxis: {
            categories: prodiLabels,
            labels: { style: { fontSize: '11px', fontWeight: 600 } }
        },
        legend: { show: false },
        tooltip: {
            theme: 'dark',
            y: { formatter: (val) => val + ' Capaian Prestasi' }
        }
    };
    new ApexCharts(document.querySelector("#chartProdi"), optionsProdi).render();

    // 2. Chart Akademik vs Non-Akademik (Donut Chart)
    const optionsJenis = {
        series: jenisSeries,
        labels: jenisLabels,
        chart: {
            type: 'donut',
            height: 320,
            fontFamily: 'Nunito, sans-serif'
        },
        colors: ['#2563eb', '#f59e0b'],
        stroke: { show: true, colors: ['#ffffff'], width: 3 },
        dataLabels: { enabled: true },
        legend: { position: 'bottom', fontSize: '13px', fontWeight: 600 },
        plotOptions: {
            pie: {
                donut: {
                    size: '68%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total Kategori',
                            formatter: function (w) {
                                return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                            }
                        }
                    }
                }
            }
        },
        tooltip: {
            theme: 'dark',
            y: { formatter: (val) => val + ' Prestasi' }
        }
    };
    new ApexCharts(document.querySelector("#chartJenis"), optionsJenis).render();

    // 3. Chart Tren per Tahun (Area Chart)
    const optionsTahun = {
        series: [{
            name: 'Prestasi & Capaian',
            data: tahunSeries
        }],
        chart: {
            type: 'area',
            height: 300,
            toolbar: { show: false },
            fontFamily: 'Nunito, sans-serif'
        },
        colors: ['#10b981'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.45,
                opacityTo: 0.05,
                stops: [0, 90, 100]
            }
        },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 3 },
        xaxis: {
            categories: timelineYears,
            title: { text: 'Tahun Capaian', style: { fontWeight: 600 } }
        },
        yaxis: {
            title: { text: 'Jumlah Prestasi', style: { fontWeight: 600 } },
            allowDecimals: false
        },
        tooltip: {
            theme: 'dark',
            y: { formatter: (val) => val + ' Prestasi' }
        }
    };
    new ApexCharts(document.querySelector("#chartTahun"), optionsTahun).render();
});
</script>
@endpush
@endsection