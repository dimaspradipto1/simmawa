<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rekapitulasi Prestasi Mahasiswa - Universitas Ibnu Sina</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            color: #000;
            line-height: 1.3;
            background-color: #fff;
            margin: 0;
            padding: 0;
        }

        /* Kop Surat Official */
        .header-kop {
            display: table;
            width: 100%;
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }

        .kop-logo {
            display: table-cell;
            width: 80px;
            vertical-align: middle;
            text-align: center;
        }

        .kop-logo img {
            max-width: 75px;
            height: auto;
        }

        .kop-text {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            padding-left: 10px;
        }

        .kop-text h2 {
            font-size: 14pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .kop-text h1 {
            font-size: 16pt;
            font-weight: bold;
            color: #1e3a8a;
            margin: 2px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .kop-text p {
            font-size: 9pt;
            margin: 2px 0 0 0;
            font-style: italic;
        }

        /* Report Title */
        .report-title {
            text-align: center;
            margin-bottom: 20px;
        }

        .report-title h3 {
            font-size: 13pt;
            font-weight: bold;
            text-decoration: underline;
            margin: 0 0 4px 0;
            text-transform: uppercase;
        }

        .report-title p {
            font-size: 10pt;
            margin: 0;
            color: #333;
        }

        /* Info Filter Box */
        .info-box {
            width: 100%;
            border: 1px solid #ccc;
            background-color: #f9fafb;
            padding: 8px 12px;
            margin-bottom: 15px;
            font-size: 9.5pt;
            border-radius: 4px;
        }

        .info-box table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-box td {
            padding: 2px 4px;
            vertical-align: top;
        }

        /* Data Tables */
        .table-custom {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 9.5pt;
        }

        .table-custom th, .table-custom td {
            border: 1px solid #333;
            padding: 6px 8px;
        }

        .table-custom th {
            background-color: #e2e8f0;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            font-size: 9pt;
        }

        .table-custom tfoot td {
            background-color: #f1f5f9;
            font-weight: bold;
        }

        .section-heading {
            font-size: 11pt;
            font-weight: bold;
            margin: 15px 0 6px 0;
            color: #1e3a8a;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 3px;
        }

        /* Signature Section */
        .signature-wrapper {
            margin-top: 30px;
            width: 100%;
            display: table;
            page-break-inside: avoid;
        }

        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
        }

        .signature-space {
            height: 65px;
        }

        /* Print Media Controls */
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: #fff;
            }
        }

        .btn-print-toolbar {
            position: fixed;
            top: 15px;
            right: 15px;
            background: #1e3a8a;
            color: white;
            padding: 10px 18px;
            border-radius: 6px;
            text-decoration: none;
            font-family: sans-serif;
            font-size: 13px;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0,0,0,0.2);
            cursor: pointer;
            z-index: 9999;
        }
        .btn-print-toolbar:hover {
            background: #2563eb;
        }
    </style>
</head>
<body>

    <!-- Floating Print Button -->
    <div class="no-print">
        <button onclick="window.print()" class="btn-print-toolbar">
            🖨️ Cetak Dokumen PDF / Print
        </button>
    </div>

    <!-- Kop Surat Header -->
    <div class="header-kop">
        <div class="kop-logo">
            <img src="{{ asset('assets/img/logo_uis.png') }}" alt="Logo UIS">
        </div>
        <div class="kop-text">
            <h2>YAYASAN IBNU SINA BATAM</h2>
            <h1>UNIVERSITAS IBNU SINA (UIS)</h1>
            <p>Jl. Teuku Umar, Pelita, Kec. Lubuk Baja, Kota Batam, Kepulauan Riau 29444<br>Email: bkak@uis.ac.id | Website: https://uis.ac.id</p>
        </div>
    </div>

    <!-- Title -->
    <div class="report-title">
        <h3>LAPORAN REKAPITULASI PRESTASI MAHASISWA</h3>
        <p>Biro Kemahasiswaan, Alumni, dan Konseling (BKAK)</p>
    </div>

    <!-- Info Box -->
    <div class="info-box">
        <table>
            <tr>
                <td style="width: 15%;"><strong>Periode Tahun</strong></td>
                <td style="width: 35%;">: {{ $selectedTahun === 'all' ? 'Semua Tahun' : 'Tahun ' . $selectedTahun }}</td>
                <td style="width: 15%;"><strong>Kategori</strong></td>
                <td style="width: 35%;">: {{ $selectedJenis === 'all' ? 'Semua Kategori' : $selectedJenis }}</td>
            </tr>
            <tr>
                <td><strong>Program Studi</strong></td>
                <td>: {{ $selectedProdi === 'all' ? 'Semua Program Studi' : $selectedProdi }}</td>
                <td><strong>Tanggal Cetak</strong></td>
                <td>: {{ $printedAt }}</td>
            </tr>
        </table>
    </div>

    <!-- 1. Matriks Rekapitulasi Table -->
    <div class="section-heading">I. MATRIKS REKAPITULASI PRESTASI PER PROGRAM STUDI</div>
    <table class="table-custom">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">NO</th>
                <th style="text-align: left;">PROGRAM STUDI</th>
                <th style="width: 15%;">KABUPATEN/KOTA</th>
                <th style="width: 15%;">PROVINSI</th>
                <th style="width: 15%;">NASIONAL</th>
                <th style="width: 15%;">INTERNASIONAL</th>
                <th style="width: 12%;">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $sKab = 0; $sProv = 0; $sNas = 0; $sInter = 0; $sTot = 0;
            @endphp
            @foreach($matrix as $prodiName => $c)
                @php
                    $sKab += $c['Kabupaten/Kota'];
                    $sProv += $c['Provinsi'];
                    $sNas += $c['Nasional'];
                    $sInter += $c['Internasional'];
                    $sTot += $c['total'];
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $no++ }}</td>
                    <td><strong>{{ $prodiName }}</strong></td>
                    <td style="text-align: center;">{{ $c['Kabupaten/Kota'] }}</td>
                    <td style="text-align: center;">{{ $c['Provinsi'] }}</td>
                    <td style="text-align: center;">{{ $c['Nasional'] }}</td>
                    <td style="text-align: center;">{{ $c['Internasional'] }}</td>
                    <td style="text-align: center; font-weight: bold;">{{ $c['total'] }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" style="text-align: center;">TOTAL KESELURUHAN</td>
                <td style="text-align: center;">{{ $sKab }}</td>
                <td style="text-align: center;">{{ $sProv }}</td>
                <td style="text-align: center;">{{ $sNas }}</td>
                <td style="text-align: center;">{{ $sInter }}</td>
                <td style="text-align: center; font-size: 10pt;">{{ $sTot }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- 2. Detailed Records Table -->
    <div class="section-heading">II. RINCIAN CAPAIAN PRESTASI MAHASISWA</div>
    <table class="table-custom">
        <thead>
            <tr>
                <th style="width: 3%; text-align: center;">NO</th>
                <th style="width: 16%;">MAHASISWA & NIM</th>
                <th style="width: 14%;">PROGRAM STUDI</th>
                <th style="width: 18%;">JUDUL KEGIATAN</th>
                <th style="width: 11%;">KATEGORI</th>
                <th style="width: 9%; text-align: center;">KEPESERTAAN</th>
                <th style="width: 8%; text-align: center;">TINGKAT</th>
                <th style="width: 8%; text-align: center;">CAPAIAN</th>
                <th style="width: 8%; text-align: center;">TGL SERTIFIKAT</th>
                <th style="width: 5%; text-align: center;">TAHUN</th>
            </tr>
        </thead>
        <tbody>
            @forelse($filteredRecords as $i => $r)
                <tr>
                    <td style="text-align: center;">{{ $i + 1 }}</td>
                    <td>
                        <strong>{{ $r['mahasiswa'] }}</strong><br>
                        <small style="color: #444;">NIM: {{ $r['nim'] }}</small>
                    </td>
                    <td>{{ $r['prodi'] }}</td>
                    <td>
                        <strong>{{ $r['judul_kegiatan'] }}</strong><br>
                        <small style="color: #666;">Modul: {{ $r['modul'] }}</small>
                    </td>
                    <td>{{ $r['kategori'] }}</td>
                    <td style="text-align: center;">
                        <span style="font-weight: 500;">{{ $r['kepesertaan'] }}</span>
                    </td>
                    <td style="text-align: center;">{{ $r['level'] }}</td>
                    <td style="text-align: center;"><strong>{{ $r['capaian'] }}</strong></td>
                    <td style="text-align: center;">{{ $r['tanggal_sertifikat'] }}</td>
                    <td style="text-align: center;">{{ $r['tahun'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align: center; font-style: italic; color: #666; padding: 15px;">
                        Tidak ada data rekapitulasi yang tersedia sesuai filter.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Signature Block -->
    <div class="signature-wrapper">
        <div class="signature-box">
            <p>Mengetahui,<br><strong>Kepala Bidang Kemahasiswaan (BKAK)</strong></p>
            <div class="signature-space"></div>
            <p><u><strong>Dr. H. Ahmad Dahlan, M.Pd.</strong></u><br>NIDN. 0015087802</p>
        </div>
        <div class="signature-box">
            <p>Batam, {{ date('d F Y') }}<br><strong>Administrator Pelaksana</strong></p>
            <div class="signature-space"></div>
            <p><u><strong>{{ auth()->user()->name }}</strong></u><br>SIMMAWA Universitas Ibnu Sina</p>
        </div>
    </div>

    <!-- Auto Print Script -->
    <script>
        window.addEventListener('load', function() {
            // Auto open print dialog when view loaded
            setTimeout(function() {
                window.print();
            }, 600);
        });
    </script>
</body>
</html>
