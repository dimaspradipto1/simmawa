@extends('layouts.dashboard.template')

@section('content')
<div class="pagetitle">
  <h1>Prestasi Mandiri</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="{{ route('prestasi-mandiri.index') }}">Prestasi Mandiri</a></li>
      <li class="breadcrumb-item active">Edit Prestasi</li>
    </ol>
  </nav>
  <p class="text-muted small">Perbarui data prestasi, mahasiswa, dan dosen pendamping.</p>
</div><!-- End Page Title -->

<section class="section">
  <!-- SIMKATMAWA Soft Blue Header Banner -->
  <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);">
    <div class="card-body p-4">
      <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1">
          Edit Prestasi
        </span>
        @if(!empty($isDriveReady) && $isDriveReady)
          <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1">
            <i class="bi bi-google me-1"></i> Penyimpanan Google Drive Aktif
          </span>
          @if(auth()->check() && in_array(auth()->user()->role, ['superadmin', 'adminbkak']))
            <a href="{{ route('google-drive.auth') }}" class="btn btn-sm btn-primary py-0 px-2 fw-medium" style="font-size: 0.75rem;">
              <i class="bi bi-google me-1"></i> Hubungkan Ulang Google Drive
            </a>
          @endif
        @else
          <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-1">
            <i class="bi bi-exclamation-circle me-1"></i> Google Drive Belum Terhubung (Penyimpanan Lokal Aktif)
          </span>
          @if(auth()->check() && in_array(auth()->user()->role, ['superadmin', 'adminbkak']))
            <a href="{{ route('google-drive.auth') }}" class="btn btn-sm btn-primary py-0 px-2 fw-medium" style="font-size: 0.75rem;">
              <i class="bi bi-google me-1"></i> Hubungkan Google Drive
            </a>
          @endif
        @endif
      </div>
      <h5 class="fw-bold text-dark mb-1">Form prestasi mandiri terpadu</h5>
      <p class="text-secondary small mb-3">Data mahasiswa dan dosen kini diisi langsung di form ini, sehingga tidak perlu berpindah halaman setelah data prestasi tersimpan.</p>

      @php
        $templatesLpj = ($activeTemplatesLpj ?? collect())->isNotEmpty()
          ? $activeTemplatesLpj
          : \App\Models\TemplateLpj::getActiveTemplates('Prestasi Mandiri');

        if ($templatesLpj->isEmpty()) {
          $fallback = \App\Models\TemplateLpj::where('kategori', 'Prestasi Mandiri')->get();
          if ($fallback->isNotEmpty()) {
            $templatesLpj = $fallback;
          }
        }
      @endphp

      <div class="card border border-primary-subtle bg-white rounded-3 shadow-sm p-3 mb-0">
        <div class="d-flex align-items-center mb-2">
          <i class="bi bi-file-earmark-arrow-down text-primary fs-5 me-2"></i>
          <div>
            <span class="fw-bold text-dark d-block" style="font-size: 0.9rem;">Daftar Template Dokumen (Prestasi Mandiri)</span>
            <small class="text-muted" style="font-size: 0.75rem;">Unduh dokumen template atau buka tautan di bawah ini sebagai acuan pelaporan prestasi Anda:</small>
          </div>
        </div>
        <div class="list-group list-group-flush rounded border mt-1">
          @forelse($templatesLpj as $tpl)
            <div class="list-group-item d-flex flex-column flex-sm-row justify-content-between align-items-sm-center py-2 px-3 gap-2">
              <div class="d-flex align-items-center">
                <i class="bi {{ $tpl->tipe === 'link' ? 'bi-link-45deg text-info' : 'bi-file-earmark-word text-primary' }} fs-5 me-2 flex-shrink-0"></i>
                <div>
                  <span class="fw-semibold text-dark d-block" style="font-size: 0.85rem;">{{ $tpl->nama }}</span>
                  @if($tpl->keterangan)
                    <span class="text-muted d-block small" style="font-size: 0.75rem;">{{ $tpl->keterangan }}</span>
                  @endif
                </div>
              </div>
              <div class="flex-shrink-0">
                <a href="{{ $tpl->download_url }}" target="_blank" rel="noopener noreferrer" 
                  class="btn btn-sm {{ $tpl->tipe === 'link' ? 'btn-outline-info' : 'btn-outline-primary' }} rounded-pill px-3 py-1 fw-medium"
                  style="font-size: 0.78rem;">
                  <i class="bi {{ $tpl->tipe === 'link' ? 'bi-box-arrow-up-right' : 'bi-download' }} me-1"></i>
                  {{ $tpl->tipe === 'link' ? 'Buka Tautan' : 'Unduh Template' }}
                </a>
              </div>
            </div>
          @empty
            <div class="list-group-item d-flex flex-column flex-sm-row justify-content-between align-items-sm-center py-2 px-3 gap-2">
              <div class="d-flex align-items-center">
                <i class="bi bi-file-earmark-word text-primary fs-5 me-2 flex-shrink-0"></i>
                <div>
                  <span class="fw-semibold text-dark d-block" style="font-size: 0.85rem;">Template Dokumen LPJ Prestasi Mandiri UIS</span>
                  <span class="text-muted d-block small" style="font-size: 0.75rem;">Format resmi dokumen LPJ (.docx)</span>
                </div>
              </div>
              <div class="flex-shrink-0">
                <a href="{{ route('prestasi-mandiri.template-lpj') }}" target="_blank" rel="noopener noreferrer" 
                  class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fw-medium" style="font-size: 0.78rem;">
                  <i class="bi bi-download me-1"></i> Unduh Template
                </a>
              </div>
            </div>
          @endforelse
        </div>
      </div>
    </div>
  </div>

  @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
      <h6 class="fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i> Terdapat kesalahan input data:</h6>
      <ul class="mb-0 small">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <form action="{{ route('prestasi-mandiri.update', $prestasiMandiri->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <!-- Card 1: Data Prestasi -->
    <div class="card shadow-sm border-0 mb-4">
      <div class="card-body p-4">
        <div class="d-flex align-items-center mb-3">
          <div class="p-2 rounded-circle bg-primary-subtle text-primary me-3">
            <i class="bi bi-trophy fs-4"></i>
          </div>
          <div>
            <h5 class="fw-bold text-dark mb-0">Data Prestasi</h5>
            <small class="text-muted">Informasi kompetisi, prestasi, dan dokumen bukti utama.</small>
          </div>
        </div>

        <div class="row g-3">
          <div class="col-md-6">
            <label for="level" class="form-label fw-medium">Level<span class="text-danger">*</span></label>
            <select class="form-select @error('level') is-invalid @enderror" id="level" name="level" required>
              <option value="" disabled {{ old('level', $prestasiMandiri->level) ? '' : 'selected' }}>Pilih Level</option>
              @foreach($options['levels'] as $key => $label)
                <option value="{{ $key }}" {{ old('level', $prestasiMandiri->level) == $key ? 'selected' : '' }}>{{ $label }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-6">
            <label for="kategori" class="form-label fw-medium">Kategori<span class="text-danger">*</span></label>
            <select class="form-select @error('kategori') is-invalid @enderror" id="kategori" name="kategori" required>
              <option value="" disabled {{ old('kategori', $prestasiMandiri->kategori) ? '' : 'selected' }}>Pilih Kategori</option>
              @foreach($options['kategoris'] as $key => $label)
                <option value="{{ $key }}" {{ old('kategori', $prestasiMandiri->kategori) == $key ? 'selected' : '' }}>{{ $label }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-12">
            <label for="nama_kompetisi" class="form-label fw-medium">Nama Kompetisi / Lomba<span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-light text-secondary"><i class="bi bi-award"></i></span>
              <input type="text" class="form-control @error('nama_kompetisi') is-invalid @enderror" id="nama_kompetisi" name="nama_kompetisi" value="{{ old('nama_kompetisi', $prestasiMandiri->nama_kompetisi) }}" placeholder="Nama Kompetisi / Lomba" required>
            </div>
          </div>

          <div class="col-md-6">
            <label for="nama_cabang" class="form-label fw-medium">Nama Cabang<span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-light text-secondary"><i class="bi bi-diagram-3"></i></span>
              <input type="text" class="form-control @error('nama_cabang') is-invalid @enderror" id="nama_cabang" name="nama_cabang" value="{{ old('nama_cabang', $prestasiMandiri->nama_cabang) }}" placeholder="Nama Cabang" required>
            </div>
          </div>

          <div class="col-md-6">
            <label for="peringkat" class="form-label fw-medium">Peringkat<span class="text-danger">*</span></label>
            <select class="form-select @error('peringkat') is-invalid @enderror" id="peringkat" name="peringkat" required>
              <option value="" disabled {{ old('peringkat', $prestasiMandiri->peringkat) ? '' : 'selected' }}>Pilih Peringkat</option>
              @foreach($options['peringkats'] as $key => $label)
                <option value="{{ $key }}" {{ old('peringkat', $prestasiMandiri->peringkat) == $key ? 'selected' : '' }}>{{ $label }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-12">
            <label for="nama_penyelenggara" class="form-label fw-medium">Nama Penyelenggara<span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-light text-secondary"><i class="bi bi-building"></i></span>
              <input type="text" class="form-control @error('nama_penyelenggara') is-invalid @enderror" id="nama_penyelenggara" name="nama_penyelenggara" value="{{ old('nama_penyelenggara', $prestasiMandiri->nama_penyelenggara) }}" placeholder="Penyelenggara" required>
            </div>
          </div>

          <div class="col-md-4">
            <label for="jumlah_pt_peserta" class="form-label fw-medium">Jumlah PT Peserta<span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-light text-secondary"><i class="bi bi-people"></i></span>
              <input type="number" class="form-control @error('jumlah_pt_peserta') is-invalid @enderror" id="jumlah_pt_peserta" name="jumlah_pt_peserta" value="{{ old('jumlah_pt_peserta', $prestasiMandiri->jumlah_pt_peserta) }}" placeholder="Jumlah">
            </div>
          </div>

          <div class="col-md-4">
            <label for="kepesertaan" class="form-label fw-medium">Kepesertaan<span class="text-danger">*</span></label>
            <select class="form-select @error('kepesertaan') is-invalid @enderror" id="kepesertaan" name="kepesertaan" required>
              <option value="" disabled {{ old('kepesertaan', $prestasiMandiri->kepesertaan) ? '' : 'selected' }}>Pilih Kepesertaan</option>
              @foreach($options['kepesertaans'] as $key => $label)
                <option value="{{ $key }}" {{ old('kepesertaan', $prestasiMandiri->kepesertaan) == $key ? 'selected' : '' }}>{{ $label }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-4">
            <label for="bentuk" class="form-label fw-medium">Bentuk<span class="text-danger">*</span></label>
            <select class="form-select @error('bentuk') is-invalid @enderror" id="bentuk" name="bentuk" required>
              <option value="" disabled {{ old('bentuk', $prestasiMandiri->bentuk) ? '' : 'selected' }}>Pilih Bentuk</option>
              @foreach($options['bentuks'] as $key => $label)
                <option value="{{ $key }}" {{ old('bentuk', $prestasiMandiri->bentuk) == $key ? 'selected' : '' }}>{{ $label }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-12">
            <label for="url_kompetisi" class="form-label fw-medium">URL Kompetisi / Lomba<span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-light text-secondary"><i class="bi bi-link-45deg"></i></span>
              <input type="url" class="form-control @error('url_kompetisi') is-invalid @enderror" id="url_kompetisi" name="url_kompetisi" value="{{ old('url_kompetisi', $prestasiMandiri->url_kompetisi) }}" placeholder="URL">
            </div>
          </div>

          <div class="col-md-6">
            <label for="file_dokumen_sertifikat" class="form-label fw-medium">Upload File Sertifikat<span class="text-danger">*</span></label>
            @if($prestasiMandiri->file_dokumen_sertifikat)
              @php $isDrive = str_contains($prestasiMandiri->file_dokumen_sertifikat, 'drive.google.com'); @endphp
              <div class="mb-1 d-flex align-items-center justify-content-between p-1 px-2 bg-light rounded border">
                <span class="small text-truncate text-secondary" style="max-width: 250px;">
                  <i class="bi {{ $isDrive ? 'bi-google text-success' : 'bi-file-earmark-check text-success' }} me-1"></i>
                  {{ $isDrive ? 'Berkas Google Drive' : basename($prestasiMandiri->file_dokumen_sertifikat) }}
                </span>
                <a href="{{ route('prestasi-mandiri.download-file', [$prestasiMandiri->id, 'sertifikat']) }}" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size: 0.75rem;" target="_blank">
                  <i class="bi {{ $isDrive ? 'bi-box-arrow-up-right' : 'bi-download' }}"></i> {{ $isDrive ? 'Buka Drive' : 'Unduh' }}
                </a>
              </div>
            @elseif($prestasiMandiri->link_dokumen_sertifikat)
              <div class="mb-1 d-flex align-items-center justify-content-between p-1 px-2 bg-light rounded border">
                <span class="small text-truncate text-secondary" style="max-width: 250px;">
                  <i class="bi bi-link-45deg text-info me-1"></i> {{ $prestasiMandiri->link_dokumen_sertifikat }}
                </span>
                <a href="{{ $prestasiMandiri->link_dokumen_sertifikat }}" class="btn btn-sm btn-outline-info py-0 px-2" style="font-size: 0.75rem;" target="_blank">
                  <i class="bi bi-box-arrow-up-right"></i> Buka
                </a>
              </div>
            @endif
            <div class="input-group">
              <span class="input-group-text bg-light text-secondary"><i class="bi bi-upload"></i></span>
              <input type="file" class="form-control @error('file_dokumen_sertifikat') is-invalid @enderror" id="file_dokumen_sertifikat" name="file_dokumen_sertifikat" accept=".pdf,.jpg,.jpeg,.png">
            </div>
            <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">Format: .pdf, .jpg, .jpeg, .png (Maks. 20 MB). Kosongkan jika tidak ingin mengganti.</small>
          </div>

          <div class="col-md-6">
            <label for="tanggal_sertifikat" class="form-label fw-medium">Tanggal Sertifikat<span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-light text-secondary"><i class="bi bi-calendar-event"></i></span>
              <input type="date" class="form-control @error('tanggal_sertifikat') is-invalid @enderror" id="tanggal_sertifikat" name="tanggal_sertifikat" value="{{ old('tanggal_sertifikat', $prestasiMandiri->tanggal_sertifikat ? $prestasiMandiri->tanggal_sertifikat->format('Y-m-d') : '') }}">
            </div>
          </div>

          <div class="col-md-6">
            <label for="link_foto_upp" class="form-label fw-medium">Link Foto UPP<span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-light text-secondary"><i class="bi bi-link-45deg"></i></span>
              <input type="url" class="form-control @error('link_foto_upp') is-invalid @enderror" id="link_foto_upp" name="link_foto_upp" value="{{ old('link_foto_upp', $prestasiMandiri->link_foto_upp) }}" placeholder="URL">
            </div>
          </div>

          <div class="col-md-6">
            <label for="link_dokumen_undangan" class="form-label fw-medium">Link Dokumen Undangan<span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-light text-secondary"><i class="bi bi-link-45deg"></i></span>
              <input type="url" class="form-control @error('link_dokumen_undangan') is-invalid @enderror" id="link_dokumen_undangan" name="link_dokumen_undangan" value="{{ old('link_dokumen_undangan', $prestasiMandiri->link_dokumen_undangan) }}" placeholder="URL">
            </div>
          </div>

          <div class="col-md-6">
            <label for="file_dokumen_lpj" class="form-label fw-medium">Upload File LPJ<span class="text-danger">*</span></label>
            @if($prestasiMandiri->file_dokumen_lpj)
              @php $isDrive = str_contains($prestasiMandiri->file_dokumen_lpj, 'drive.google.com'); @endphp
              <div class="mb-1 d-flex align-items-center justify-content-between p-1 px-2 bg-light rounded border">
                <span class="small text-truncate text-secondary" style="max-width: 250px;">
                  <i class="bi {{ $isDrive ? 'bi-google text-success' : 'bi-file-earmark-check text-success' }} me-1"></i>
                  {{ $isDrive ? 'Berkas Google Drive' : basename($prestasiMandiri->file_dokumen_lpj) }}
                </span>
                <a href="{{ route('prestasi-mandiri.download-file', [$prestasiMandiri->id, 'lpj']) }}" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size: 0.75rem;" target="_blank">
                  <i class="bi {{ $isDrive ? 'bi-box-arrow-up-right' : 'bi-download' }}"></i> {{ $isDrive ? 'Buka Drive' : 'Unduh' }}
                </a>
              </div>
            @endif
            <div class="input-group">
              <span class="input-group-text bg-light text-secondary"><i class="bi bi-upload"></i></span>
              <input type="file" class="form-control @error('file_dokumen_lpj') is-invalid @enderror" id="file_dokumen_lpj" name="file_dokumen_lpj" accept=".pdf,.doc,.docx,.zip">
            </div>
            <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">Format: .pdf, .docx, .doc, .zip (Maks. 20 MB). Kosongkan jika tidak ingin mengganti.</small>
          </div>

          <div class="col-md-6">
            <label for="no_hp" class="form-label fw-medium">No. HP / WhatsApp<span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-light text-secondary"><i class="bi bi-telephone"></i></span>
              <input type="text" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp" name="no_hp" value="{{ old('no_hp', $prestasiMandiri->no_hp ?? '') }}" placeholder="Contoh: 081234567890">
            </div>
            <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">Nomor WhatsApp aktif mahasiswa.</small>
          </div>

          <div class="col-md-6">
            <label for="file_ktm" class="form-label fw-medium">Upload KTM (Kartu Tanda Mahasiswa)<span class="text-danger">*</span></label>
            @if($prestasiMandiri->file_ktm)
              @php $isDrive = str_contains($prestasiMandiri->file_ktm, 'drive.google.com'); @endphp
              <div class="mb-1 d-flex align-items-center justify-content-between p-1 px-2 bg-light rounded border">
                <span class="small text-truncate text-secondary" style="max-width: 250px;">
                  <i class="bi {{ $isDrive ? 'bi-google text-success' : 'bi-file-earmark-check text-success' }} me-1"></i>
                  {{ $isDrive ? 'Berkas Google Drive' : basename($prestasiMandiri->file_ktm) }}
                </span>
                <a href="{{ route('prestasi-mandiri.download-file', [$prestasiMandiri->id, 'ktm']) }}" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size: 0.75rem;" target="_blank">
                  <i class="bi {{ $isDrive ? 'bi-box-arrow-up-right' : 'bi-download' }}"></i> {{ $isDrive ? 'Buka Drive' : 'Unduh' }}
                </a>
              </div>
            @endif
            <div class="input-group">
              <span class="input-group-text bg-light text-secondary"><i class="bi bi-person-badge"></i></span>
              <input type="file" class="form-control @error('file_ktm') is-invalid @enderror" id="file_ktm" name="file_ktm" accept=".pdf,.jpg,.jpeg,.png">
            </div>
            <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">Format: .pdf, .jpg, .jpeg, .png (Maks. 10 MB). Kosongkan jika tidak ingin mengganti.</small>
          </div>

          <div class="col-md-6">
            <label for="file_pengajuan_reward" class="form-label fw-medium">Upload Surat Pengajuan Reward Prestasi<span class="text-danger">*</span></label>
            @if($prestasiMandiri->file_pengajuan_reward)
              @php $isDrive = str_contains($prestasiMandiri->file_pengajuan_reward, 'drive.google.com'); @endphp
              <div class="mb-1 d-flex align-items-center justify-content-between p-1 px-2 bg-light rounded border">
                <span class="small text-truncate text-secondary" style="max-width: 250px;">
                  <i class="bi {{ $isDrive ? 'bi-google text-success' : 'bi-file-earmark-check text-success' }} me-1"></i>
                  {{ $isDrive ? 'Berkas Google Drive' : basename($prestasiMandiri->file_pengajuan_reward) }}
                </span>
                <a href="{{ route('prestasi-mandiri.download-file', [$prestasiMandiri->id, 'reward']) }}" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size: 0.75rem;" target="_blank">
                  <i class="bi {{ $isDrive ? 'bi-box-arrow-up-right' : 'bi-download' }}"></i> {{ $isDrive ? 'Buka Drive' : 'Unduh' }}
                </a>
              </div>
            @endif
            <div class="input-group">
              <span class="input-group-text bg-light text-secondary"><i class="bi bi-file-earmark-arrow-up"></i></span>
              <input type="file" class="form-control @error('file_pengajuan_reward') is-invalid @enderror" id="file_pengajuan_reward" name="file_pengajuan_reward" accept=".pdf,.doc,.docx">
            </div>
            <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">Format: .pdf, .docx, .doc (Maks. 20 MB). Kosongkan jika tidak ingin mengganti.</small>
          </div>

          <div class="col-md-6">
            <label for="file_surat_tugas" class="form-label fw-medium">Upload Surat Tugas dari WR III / WD I<span class="text-danger">*</span></label>
            @if($prestasiMandiri->file_surat_tugas)
              @php $isDrive = str_contains($prestasiMandiri->file_surat_tugas, 'drive.google.com'); @endphp
              <div class="mb-1 d-flex align-items-center justify-content-between p-1 px-2 bg-light rounded border">
                <span class="small text-truncate text-secondary" style="max-width: 250px;">
                  <i class="bi {{ $isDrive ? 'bi-google text-success' : 'bi-file-earmark-check text-success' }} me-1"></i>
                  {{ $isDrive ? 'Berkas Google Drive' : basename($prestasiMandiri->file_surat_tugas) }}
                </span>
                <a href="{{ route('prestasi-mandiri.download-file', [$prestasiMandiri->id, 'surat-tugas']) }}" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size: 0.75rem;" target="_blank">
                  <i class="bi {{ $isDrive ? 'bi-box-arrow-up-right' : 'bi-download' }}"></i> {{ $isDrive ? 'Buka Drive' : 'Unduh' }}
                </a>
              </div>
            @endif
            <div class="input-group">
              <span class="input-group-text bg-light text-secondary"><i class="bi bi-file-earmark-check"></i></span>
              <input type="file" class="form-control @error('file_surat_tugas') is-invalid @enderror" id="file_surat_tugas" name="file_surat_tugas" accept=".pdf,.doc,.docx">
            </div>
            <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">Surat Tugas WR III atau Wakil Dekan I. Format: .pdf, .docx, .doc (Maks. 20 MB). Kosongkan jika tidak ingin mengganti.</small>
          </div>

          <div class="col-12">
            <label for="keterangan" class="form-label fw-medium">Keterangan</label>
            <div class="input-group">
              <span class="input-group-text bg-light text-secondary"><i class="bi bi-card-text"></i></span>
              <input type="text" class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" value="{{ old('keterangan', $prestasiMandiri->keterangan) }}" placeholder="Keterangan tambahan">
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Card 2: Data Mahasiswa -->
    <div class="card shadow-sm border-0 mb-4">
      <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="d-flex align-items-center">
            <div class="p-2 rounded-circle bg-primary-subtle text-primary me-3">
              <i class="bi bi-person fs-4"></i>
            </div>
            <div>
              <h5 class="fw-bold text-dark mb-0">Data Mahasiswa</h5>
              <small class="text-muted">Minimal satu mahasiswa wajib diisi sebelum data dapat disimpan.</small>
            </div>
          </div>
          <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" id="btn-add-mahasiswa">
            <i class="bi bi-plus-lg me-1"></i> Tambah
          </button>
        </div>

        <div id="container-mahasiswa">
          @php
            $mahasiswaList = old('data_mahasiswa', $prestasiMandiri->data_mahasiswa ?? [['nim' => '', 'nama' => '']]);
            if (empty($mahasiswaList)) {
              $mahasiswaList = [['nim' => '', 'nama' => '']];
            }
          @endphp

          @foreach($mahasiswaList as $index => $mhs)
            <div class="row g-3 align-items-end mb-2 row-mahasiswa">
              <div class="col-md-5">
                <label class="form-label small fw-medium text-secondary mb-1">NIM <span class="text-danger">*</span></label>
                <input type="text" name="data_mahasiswa[{{ $index }}][nim]" class="form-control form-control-sm" placeholder="NIM" value="{{ $mhs['nim'] ?? '' }}">
              </div>
              <div class="col-md-6">
                <label class="form-label small fw-medium text-secondary mb-1">Nama Mahasiswa <span class="text-danger">*</span></label>
                <input type="text" name="data_mahasiswa[{{ $index }}][nama]" class="form-control form-control-sm" placeholder="Nama Mahasiswa" value="{{ $mhs['nama'] ?? '' }}">
              </div>
              <div class="col-md-1 text-end">
                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row" style="padding: 4px 10px;">
                  <i class="bi bi-trash"></i>
                </button>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>

    <!-- Card 3: Data Dosen Pendamping -->
    <div class="card shadow-sm border-0 mb-4">
      <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="d-flex align-items-center">
            <div class="p-2 rounded-circle bg-primary-subtle text-primary me-3">
              <i class="bi bi-person-badge fs-4"></i>
            </div>
            <div>
              <h5 class="fw-bold text-dark mb-0">Data Dosen Pendamping</h5>
              <small class="text-muted">Isi dosen pendamping beserta tautan surat tugasnya.</small>
            </div>
          </div>
          <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" id="btn-add-dosen">
            <i class="bi bi-plus-lg me-1"></i> Tambah
          </button>
        </div>

        <div id="container-dosen">
          @php
            $dosenList = old('data_dosen', $prestasiMandiri->data_dosen ?? [['nidn' => '', 'nama' => '', 'url_surat' => '']]);
            if (empty($dosenList)) {
              $dosenList = [['nidn' => '', 'nama' => '', 'url_surat' => '']];
            }
          @endphp

          @foreach($dosenList as $index => $dsn)
            <div class="row g-3 align-items-end mb-2 row-dosen">
              <div class="col-md-3">
                <label class="form-label small fw-medium text-secondary mb-1">NIDN/NUPTK</label>
                <input type="text" name="data_dosen[{{ $index }}][nidn]" class="form-control form-control-sm" placeholder="NIDN/NUPTK" value="{{ $dsn['nidn'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label class="form-label small fw-medium text-secondary mb-1">Nama Dosen</label>
                <input type="text" name="data_dosen[{{ $index }}][nama]" class="form-control form-control-sm" placeholder="Nama Dosen" value="{{ $dsn['nama'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label class="form-label small fw-medium text-secondary mb-1">URL Surat Tugas</label>
                <input type="text" name="data_dosen[{{ $index }}][url_surat]" class="form-control form-control-sm" placeholder="URL Surat Tugas" value="{{ $dsn['url_surat'] ?? '' }}">
              </div>
              <div class="col-md-1 text-end">
                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row" style="padding: 4px 10px;">
                  <i class="bi bi-trash"></i>
                </button>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>

    <!-- Action Footer -->
    <div class="d-flex justify-content-between align-items-center mb-5">
      <a href="{{ route('prestasi-mandiri.index') }}" class="btn btn-light border px-4 shadow-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
      </a>
      <button type="submit" class="btn btn-dark px-4 shadow-sm" style="background-color: #0f172a; border-color: #0f172a;">
        <i class="bi bi-save me-1"></i> Perbarui Data
      </button>
    </div>
  </form>
</section>

<!-- Dynamic Rows JS -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  let mhsIndex = {{ count($mahasiswaList) }};
  let dsnIndex = {{ count($dosenList) }};

  // Add Mahasiswa Row
  document.getElementById('btn-add-mahasiswa').addEventListener('click', function () {
    const container = document.getElementById('container-mahasiswa');
    const row = document.createElement('div');
    row.className = 'row g-3 align-items-end mb-2 row-mahasiswa';
    row.innerHTML = `
      <div class="col-md-5">
        <label class="form-label small fw-medium text-secondary mb-1">NIM <span class="text-danger">*</span></label>
        <input type="text" name="data_mahasiswa[${mhsIndex}][nim]" class="form-control form-control-sm" placeholder="NIM">
      </div>
      <div class="col-md-6">
        <label class="form-label small fw-medium text-secondary mb-1">Nama Mahasiswa <span class="text-danger">*</span></label>
        <input type="text" name="data_mahasiswa[${mhsIndex}][nama]" class="form-control form-control-sm" placeholder="Nama Mahasiswa">
      </div>
      <div class="col-md-1 text-end">
        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row" style="padding: 4px 10px;">
          <i class="bi bi-trash"></i>
        </button>
      </div>
    `;
    container.appendChild(row);
    mhsIndex++;
  });

  // Add Dosen Row
  document.getElementById('btn-add-dosen').addEventListener('click', function () {
    const container = document.getElementById('container-dosen');
    const row = document.createElement('div');
    row.className = 'row g-3 align-items-end mb-2 row-dosen';
    row.innerHTML = `
      <div class="col-md-3">
        <label class="form-label small fw-medium text-secondary mb-1">NIDN/NUPTK</label>
        <input type="text" name="data_dosen[${dsnIndex}][nidn]" class="form-control form-control-sm" placeholder="NIDN/NUPTK">
      </div>
      <div class="col-md-4">
        <label class="form-label small fw-medium text-secondary mb-1">Nama Dosen</label>
        <input type="text" name="data_dosen[${dsnIndex}][nama]" class="form-control form-control-sm" placeholder="Nama Dosen">
      </div>
      <div class="col-md-4">
        <label class="form-label small fw-medium text-secondary mb-1">URL Surat Tugas</label>
        <input type="text" name="data_dosen[${dsnIndex}][url_surat]" class="form-control form-control-sm" placeholder="URL Surat Tugas">
      </div>
      <div class="col-md-1 text-end">
        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row" style="padding: 4px 10px;">
          <i class="bi bi-trash"></i>
        </button>
      </div>
    `;
    container.appendChild(row);
    dsnIndex++;
  });

  // Delegate Remove Row
  document.addEventListener('click', function (e) {
    if (e.target.closest('.btn-remove-row')) {
      const row = e.target.closest('.row-mahasiswa') || e.target.closest('.row-dosen');
      if (row) {
        row.remove();
      }
    }
  });
});
</script>
@endsection
