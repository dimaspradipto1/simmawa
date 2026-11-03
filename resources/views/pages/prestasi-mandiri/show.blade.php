@extends('layouts.dashboard.template')

@section('content')
<div class="pagetitle">
    <h1>Prestasi Mandiri</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('prestasi-mandiri.index') }}">Prestasi Mandiri</a></li>
            <li class="breadcrumb-item active">Form</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <h5 class="card-title fw-bold text-dark mb-4 p-0">View Data</h5>

            <div class="row g-3">
                <!-- Level & Kategori -->
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-dark mb-1">Level</label>
                    <div class="p-2 rounded bg-light border-0 text-dark small">
                        {{ $prestasiMandiri->level ?? '-' }}
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-bold text-dark mb-1">Kategori</label>
                    <div class="p-2 rounded bg-light border-0 text-dark small">
                        {{ $prestasiMandiri->kategori ?? '-' }}
                    </div>
                </div>

                <!-- Nama Kompetisi / Lomba -->
                <div class="col-12">
                    <label class="form-label small fw-bold text-dark mb-1">Nama Kompetisi / Lomba</label>
                    <div class="p-2 rounded bg-light border-0 text-dark small">
                        {{ $prestasiMandiri->nama_kompetisi ?? '-' }}
                    </div>
                </div>

                <!-- Nama Cabang & Peringkat -->
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-dark mb-1">Nama Cabang</label>
                    <div class="p-2 rounded bg-light border-0 text-dark small">
                        {{ $prestasiMandiri->nama_cabang ?? '-' }}
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-bold text-dark mb-1">Peringkat</label>
                    <div class="p-2 rounded bg-light border-0 text-dark small">
                        {{ $prestasiMandiri->peringkat ?? '-' }}
                    </div>
                </div>

                <!-- Nama Penyelenggara -->
                <div class="col-12">
                    <label class="form-label small fw-bold text-dark mb-1">Nama Penyelenggara</label>
                    <div class="p-2 rounded bg-light border-0 text-dark small">
                        {{ $prestasiMandiri->nama_penyelenggara ?? '-' }}
                    </div>
                </div>

                <!-- Jumlah PT / Negara Peserta, Kepesertaan, Bentuk -->
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-dark mb-1">Jumlah PT / Negara Peserta</label>
                    <div class="p-2 rounded bg-light border-0 text-dark small">
                        {{ $prestasiMandiri->jumlah_pt_peserta ?? '-' }}
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-bold text-dark mb-1">Kepesertaan</label>
                    <div class="p-2 rounded bg-light border-0 text-dark small">
                        {{ $prestasiMandiri->kepesertaan ?? '-' }}
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-bold text-dark mb-1">Bentuk</label>
                    <div class="p-2 rounded bg-light border-0 text-dark small">
                        {{ $prestasiMandiri->bentuk ?? '-' }}
                    </div>
                </div>

                <!-- URL Kompetisi / Lomba -->
                <div class="col-12">
                    <label class="form-label small fw-bold text-dark mb-1">URL Kompetisi / Lomba</label>
                    <div class="p-2 rounded bg-light border-0 text-dark small text-break">
                        @if($prestasiMandiri->url_kompetisi)
                            <a href="{{ $prestasiMandiri->url_kompetisi }}" target="_blank" class="text-primary text-decoration-none">{{ $prestasiMandiri->url_kompetisi }}</a>
                        @else
                            -
                        @endif
                    </div>
                </div>

                <!-- Dokumen Sertifikat & Tanggal Sertifikat -->
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-dark mb-1">Dokumen Sertifikat</label>
                    <div class="p-2 rounded bg-light border-0 text-dark small text-break">
                        @if($prestasiMandiri->file_dokumen_sertifikat)
                            <a href="{{ route('prestasi-mandiri.download-file', [$prestasiMandiri->id, 'sertifikat']) }}" class="btn btn-sm btn-outline-primary py-1 px-3 d-inline-flex align-items-center" target="_blank">
                                <i class="bi bi-file-earmark-check me-1"></i> Unduh Berkas Sertifikat
                            </a>
                        @elseif($prestasiMandiri->link_dokumen_sertifikat)
                            <a href="{{ $prestasiMandiri->link_dokumen_sertifikat }}" target="_blank" class="text-primary text-decoration-none">
                                <i class="bi bi-box-arrow-up-right me-1"></i> {{ $prestasiMandiri->link_dokumen_sertifikat }}
                            </a>
                        @else
                            -
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-bold text-dark mb-1">Tanggal Sertifikat</label>
                    <div class="p-2 rounded bg-light border-0 text-dark small">
                        {{ $prestasiMandiri->tanggal_sertifikat ? $prestasiMandiri->tanggal_sertifikat->format('Y-m-d') : '-' }}
                    </div>
                </div>

                <!-- Link Foto UPP & Link Dokumen Undangan -->
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-dark mb-1">Link Foto UPP</label>
                    <div class="p-2 rounded bg-light border-0 text-dark small text-break">
                        @if($prestasiMandiri->link_foto_upp)
                            <a href="{{ $prestasiMandiri->link_foto_upp }}" target="_blank" class="text-primary text-decoration-none">{{ $prestasiMandiri->link_foto_upp }}</a>
                        @else
                            -
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-bold text-dark mb-1">Link Dokumen Undangan</label>
                    <div class="p-2 rounded bg-light border-0 text-dark small text-break">
                        @if($prestasiMandiri->link_dokumen_undangan)
                            <a href="{{ $prestasiMandiri->link_dokumen_undangan }}" target="_blank" class="text-primary text-decoration-none">{{ $prestasiMandiri->link_dokumen_undangan }}</a>
                        @else
                            -
                        @endif
                    </div>
                </div>

                <!-- File Dokumen LPJ -->
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-dark mb-1">Berkas File LPJ</label>
                    <div class="p-2 rounded bg-light border-0 text-dark small">
                        @if($prestasiMandiri->file_dokumen_lpj)
                            <a href="{{ route('prestasi-mandiri.download-lpj', $prestasiMandiri->id) }}" class="btn btn-sm btn-outline-success py-1 px-3 d-inline-flex align-items-center" target="_blank">
                                <i class="bi bi-download me-1"></i> Unduh Berkas LPJ
                            </a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                </div>

                <!-- Dokumen Tambahan & Kontak -->
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-dark mb-1">No. HP / WhatsApp</label>
                    <div class="p-2 rounded bg-light border-0 text-dark small">
                        {{ $prestasiMandiri->no_hp ?? '-' }}
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-bold text-dark mb-1">KTM (Kartu Tanda Mahasiswa)</label>
                    <div class="p-2 rounded bg-light border-0 text-dark small">
                        @if($prestasiMandiri->file_ktm)
                            <a href="{{ route('prestasi-mandiri.download-file', [$prestasiMandiri->id, 'ktm']) }}" class="btn btn-sm btn-outline-primary py-1 px-3 d-inline-flex align-items-center" target="_blank">
                                <i class="bi bi-person-badge me-1"></i> Unduh KTM
                            </a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-bold text-dark mb-1">Surat Pengajuan Reward Prestasi</label>
                    <div class="p-2 rounded bg-light border-0 text-dark small">
                        @if($prestasiMandiri->file_pengajuan_reward)
                            <a href="{{ route('prestasi-mandiri.download-file', [$prestasiMandiri->id, 'reward']) }}" class="btn btn-sm btn-outline-success py-1 px-3 d-inline-flex align-items-center" target="_blank">
                                <i class="bi bi-file-earmark-arrow-up me-1"></i> Unduh Surat Reward
                            </a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-bold text-dark mb-1">Surat Tugas WR III / WD I</label>
                    <div class="p-2 rounded bg-light border-0 text-dark small">
                        @if($prestasiMandiri->file_surat_tugas)
                            <a href="{{ route('prestasi-mandiri.download-file', [$prestasiMandiri->id, 'surat-tugas']) }}" class="btn btn-sm btn-outline-info py-1 px-3 d-inline-flex align-items-center" target="_blank">
                                <i class="bi bi-file-earmark-check me-1"></i> Unduh Surat Tugas
                            </a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                </div>

                <!-- Keterangan -->
                <div class="col-12">
                    <label class="form-label small fw-bold text-dark mb-1">Keterangan</label>
                    <div class="p-2 rounded bg-light border-0 text-dark small">
                        {{ $prestasiMandiri->keterangan ?? '-' }}
                    </div>
                </div>

                <!-- Data Mahasiswa Table -->
                <div class="col-12 mt-4">
                    <h6 class="fw-bold text-dark mb-2">Data Mahasiswa</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 60px;">No</th>
                                    <th style="width: 250px;">NIM</th>
                                    <th>Nama Mahasiswa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($prestasiMandiri->data_mahasiswa) && count($prestasiMandiri->data_mahasiswa) > 0)
                                    @foreach($prestasiMandiri->data_mahasiswa as $idx => $mhs)
                                        <tr>
                                            <td class="fw-bold">{{ $idx + 1 }}</td>
                                            <td>{{ $mhs['nim'] ?? '-' }}</td>
                                            <td>{{ $mhs['nama'] ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3" class="text-center text-muted small py-3">Belum ada data mahasiswa</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Data Dosen Table -->
                <div class="col-12 mt-4">
                    <h6 class="fw-bold text-dark mb-2">Data Dosen</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 60px;">No</th>
                                    <th style="width: 250px;">NUPTK/NIDN</th>
                                    <th>Nama Dosen</th>
                                    <th>Surat Tugas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($prestasiMandiri->data_dosen) && count($prestasiMandiri->data_dosen) > 0)
                                    @foreach($prestasiMandiri->data_dosen as $idx => $dsn)
                                        <tr>
                                            <td class="fw-bold">{{ $idx + 1 }}</td>
                                            <td>{{ $dsn['nidn'] ?? '-' }}</td>
                                            <td>{{ $dsn['nama'] ?? '-' }}</td>
                                            <td>
                                                @if(!empty($dsn['url_surat']))
                                                    <a href="{{ $dsn['url_surat'] }}" target="_blank" class="text-primary text-decoration-none">{{ $dsn['url_surat'] }}</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center text-muted small py-3">Belum ada data dosen</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Bottom Button -->
                <div class="col-12 mt-4">
                    <a href="{{ route('prestasi-mandiri.index') }}" class="btn text-white fw-medium px-4 shadow-sm" style="background-color: #f97316; border-color: #f97316;">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
