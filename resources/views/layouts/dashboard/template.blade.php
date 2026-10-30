<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>HOME - SIMAWA Dashboard</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{ asset('assets/img/logo_uis.png') }}" rel="icon">
    <link href="{{ asset('assets/img/logo_uis.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

    {{--  datatables CSS  --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.css">

</head>

<body>

    @include('layouts.dashboard.header')
    @include('layouts.dashboard.sidebar')

    <main id="main" class="main">
        @include('sweetalert::alert')

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center shadow-sm border-0 mb-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-5 me-2 flex-shrink-0"></i>
                <div class="flex-grow-1">
                    <strong>Peringatan:</strong> {{ session('error') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </main><!-- End #main -->

    @include('layouts.dashboard.footer')


    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/chart.js/chart.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/quill/quill.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    {{-- datatables --}}
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>

    <!-- SweetAlert2 Global -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Global Client-Side File Size Validator (Maksimal 2 MB untuk Gambar / Logo) -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. Validasi saat user memilih file
            document.addEventListener('change', function (e) {
                if (!e.target || e.target.type !== 'file') return;
                const input = e.target;
                const file = input.files && input.files[0];
                if (!file) return;

                // Tentukan batas maksimal dalam MB (default: 2 MB untuk image/logo)
                let maxMB = 2;
                if (input.dataset.maxSize) {
                    maxMB = parseFloat(input.dataset.maxSize);
                } else if (input.name && (input.name.includes('dokumen') || input.name.includes('document'))) {
                    maxMB = 20;
                } else if (input.name && input.name === 'file') {
                    maxMB = 5;
                }

                const maxBytes = maxMB * 1024 * 1024;
                if (file.size > maxBytes) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();

                    const actualMB = (file.size / (1024 * 1024)).toFixed(2);
                    input.value = ''; // Batalkan file yang dipilih

                    // Reset preview gambar jika ada
                    const previewImg = document.getElementById('logo-preview') || 
                                       document.querySelector('[data-preview-target]') ||
                                       document.getElementById('live-card-body');
                    if (previewImg) {
                        if (previewImg.dataset && previewImg.dataset.defaultSrc) {
                            previewImg.src = previewImg.dataset.defaultSrc;
                        }
                    }

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Ukuran File Terlalu Besar!',
                            html: `File yang Anda pilih berukuran <strong>${actualMB} MB</strong>.<br>Batas maksimal ukuran file adalah <strong>${maxMB} MB</strong>.<br><br><span class="text-secondary small">Silakan pilih file gambar dengan ukuran lebih kecil atau kompres terlebih dahulu.</span>`,
                            confirmButtonColor: '#046B26',
                            confirmButtonText: 'Baik, Saya Mengerti'
                        });
                    } else {
                        alert(`Ukuran file terlalu besar (${actualMB} MB)! Batas maksimal yang diizinkan adalah ${maxMB} MB.`);
                    }
                }
            }, true);

            // 2. Pengaman saat submit form
            document.addEventListener('submit', function (e) {
                const form = e.target;
                if (!form || !form.querySelectorAll) return;
                const fileInputs = form.querySelectorAll('input[type="file"]');
                for (let input of fileInputs) {
                    const file = input.files && input.files[0];
                    if (!file) continue;

                    let maxMB = input.dataset.maxSize ? parseFloat(input.dataset.maxSize) : 2;
                    if (input.name && (input.name.includes('dokumen') || input.name.includes('document'))) {
                        maxMB = 20;
                    } else if (input.name && input.name === 'file') {
                        maxMB = 5;
                    }

                    if (file.size > maxMB * 1024 * 1024) {
                        e.preventDefault();
                        const actualMB = (file.size / (1024 * 1024)).toFixed(2);
                        input.value = '';
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Gagal Mengunggah',
                                text: `Ukuran file (${actualMB} MB) melebihi batas maksimal ${maxMB} MB. Harap pilih file yang lebih kecil.`,
                                confirmButtonColor: '#046B26'
                            });
                        } else {
                            alert(`Ukuran file melebihi batas maksimal ${maxMB} MB.`);
                        }
                        return false;
                    }
                }
            }, true);
        });
    </script>

    @stack('scripts')

</body>

</html>
