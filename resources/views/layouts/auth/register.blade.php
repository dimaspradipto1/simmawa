<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Pendaftaran Mahasiswa | SIPENA - Universitas Ibnu Sina</title>
    <meta content="Pendaftaran Akun Mahasiswa Baru Sistem Informasi Kinerja dan Tata Kelola Kemahasiswaan (SIPENA)" name="description">
    <meta content="sipena, kemahasiswaan, register, mahasiswa, uis" name="keywords">

    <!-- Favicons -->
    <link href="{{ asset('assets/img/logo_uis.png') }}" rel="icon">
    <link href="{{ asset('assets/img/logo_uis.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|Nunito:300,400,600,700|Poppins:300,400,500,600,700" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">

    <!-- Custom Glassmorphism Style -->
    <style>
        :root {
            --primary-green: #046B26;
            --accent-yellow: #FED802;
            --primary-gradient: linear-gradient(135deg, #046B26 0%, #034f1c 100%);
            --accent-gradient: linear-gradient(135deg, #FED802 0%, #cca902 100%);
            --glass-bg: rgba(255, 255, 255, 0.08);
            --glass-border: rgba(255, 255, 255, 0.15);
            --text-muted: #cbd5e1;
            --slate-dark: #0f172a;
            --slate-medium: #64748b;
            --slate-light: #f8fafc;
            --border-color: #e2e8f0;
        }

        html, body {
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: radial-gradient(circle at 10% 20%, #03210b 0%, #050d07 90%);
        }

        .register-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        /* Abstract glowing blobs */
        .register-wrapper::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(254, 216, 2, 0.08) 0%, transparent 70%);
            top: -20%;
            right: -10%;
            z-index: 0;
            pointer-events: none;
        }

        .register-wrapper::after {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(4, 107, 38, 0.12) 0%, transparent 70%);
            bottom: -15%;
            left: -10%;
            z-index: 0;
            pointer-events: none;
        }

        /* Dual-Pane Floating Card */
        .main-card {
            width: 1060px;
            max-width: 100%;
            min-height: 620px;
            display: flex;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 35px 70px rgba(0, 0, 0, 0.45);
            border: 1px solid rgba(254, 216, 2, 0.12);
            z-index: 1;
            background: #ffffff;
        }

        /* Left Side */
        .card-left {
            flex: 1.1;
            background: linear-gradient(135deg, rgba(4, 107, 38, 0.95) 0%, rgba(2, 60, 21, 0.98) 100%),
                        radial-gradient(circle at 80% 20%, rgba(254, 216, 2, 0.15) 0%, transparent 50%);
            padding: 2.75rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: #ffffff;
            position: relative;
            overflow: hidden;
        }

        .card-left::after {
            content: '';
            position: absolute;
            bottom: -50px;
            right: -50px;
            width: 320px;
            height: 320px;
            background: radial-gradient(circle, rgba(254, 216, 2, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            z-index: 1;
        }

        .brand-header {
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 2;
        }

        .brand-logo {
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2));
        }

        .brand-name {
            font-size: 1.45rem;
            font-weight: 800;
            color: #ffffff;
            font-family: 'Nunito', sans-serif;
            letter-spacing: 0.5px;
        }

        .brand-body-content {
            z-index: 2;
            margin: 1.5rem 0;
        }

        .badge-tag {
            display: inline-block;
            background: rgba(254, 216, 2, 0.18);
            border: 1px solid rgba(254, 216, 2, 0.35);
            color: var(--accent-yellow);
            padding: 0.35rem 0.9rem;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 600;
            margin-bottom: 1.25rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .brand-title {
            font-size: 2.1rem;
            font-weight: 800;
            line-height: 1.25;
            margin-bottom: 1rem;
            font-family: 'Nunito', sans-serif;
        }

        .brand-title .text-gradient {
            background: linear-gradient(135deg, #FED802 0%, #ffffff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .brand-desc {
            color: rgba(255, 255, 255, 0.85);
            font-size: 0.88rem;
            line-height: 1.55;
            margin-bottom: 1.5rem;
            font-weight: 300;
        }

        .benefit-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 10px;
            font-size: 0.84rem;
            color: rgba(255, 255, 255, 0.9);
        }

        .benefit-list li {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .benefit-list li i {
            color: #FED802;
            font-size: 1rem;
        }

        .brand-footer {
            font-size: 0.72rem;
            color: rgba(255, 255, 255, 0.55);
            z-index: 2;
        }

        /* Right Side: Register Form */
        .card-right {
            flex: 1.2;
            background: #ffffff;
            padding: 2.2rem 2.75rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }

        .register-header {
            margin-bottom: 1rem;
        }

        .register-title {
            font-size: 1.55rem;
            font-weight: 800;
            color: var(--slate-dark);
            margin-bottom: 0.2rem;
            font-family: 'Nunito', sans-serif;
        }

        .register-desc {
            color: var(--slate-medium);
            font-size: 0.82rem;
            margin-bottom: 0;
        }

        /* Step Progress Indicator */
        .step-progress-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.15rem;
            padding: 0.4rem 0.6rem;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #edf2f7;
        }

        .step-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--slate-medium);
            user-select: none;
            transition: all 0.25s ease;
        }

        .step-item.active {
            color: var(--primary-green);
        }

        .step-badge {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #e2e8f0;
            color: var(--slate-medium);
            font-size: 0.72rem;
            font-weight: 800;
            transition: all 0.25s ease;
        }

        .step-item.active .step-badge {
            background: var(--primary-gradient);
            color: #ffffff;
            box-shadow: 0 0 0 3px rgba(4, 107, 38, 0.15);
        }

        .step-item.completed .step-badge {
            background: #166534;
            color: #ffffff;
        }

        .step-connector {
            flex: 1;
            height: 2px;
            background-color: #e2e8f0;
            margin: 0 12px;
            transition: all 0.25s ease;
        }

        .step-connector.active {
            background-color: var(--primary-green);
        }

        /* Step Contents with smooth display */
        .step-content {
            display: none;
            animation: fadeInStep 0.28s ease-in-out forwards;
        }

        .step-content.active {
            display: block;
        }

        @keyframes fadeInStep {
            from {
                opacity: 0;
                transform: translateY(5px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.25rem;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 0.9rem;
            color: var(--slate-medium);
            font-size: 0.95rem;
            pointer-events: none;
            transition: color 0.2s ease;
            z-index: 2;
        }

        .form-input {
            width: 100%;
            padding: 0.65rem 0.85rem 0.65rem 2.5rem;
            font-size: 0.84rem;
            color: var(--slate-dark);
            background-color: #f8fafc;
            border: 1.5px solid var(--border-color);
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .form-input:focus {
            background-color: #ffffff;
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(4, 107, 38, 0.12);
            outline: none;
        }

        .form-input.is-invalid {
            border-color: #dc3545;
            background-color: #fff8f8;
        }

        .form-input:focus + .input-icon {
            color: var(--primary-green);
        }

        select.form-input {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.85rem center;
            background-size: 14px 10px;
            padding-right: 2.2rem;
            cursor: pointer;
        }

        select.form-input optgroup {
            font-weight: 700;
            color: #046B26;
            background: #ffffff;
        }

        select.form-input option {
            font-weight: 400;
            color: #0f172a;
            background: #ffffff;
        }

        .password-toggle {
            position: absolute;
            right: 0.9rem;
            cursor: pointer;
            padding-left: 0.5rem;
            color: var(--slate-medium);
            font-size: 0.95rem;
            transition: color 0.2s ease;
            z-index: 2;
        }

        .password-toggle:hover {
            color: var(--slate-dark);
        }

        .btn-register {
            background: var(--primary-gradient);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem 1rem;
            border-radius: 11px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(4, 107, 38, 0.2);
            text-align: center;
            width: 100%;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(4, 107, 38, 0.35);
            background: linear-gradient(135deg, #034f1c 0%, #046B26 100%);
            color: white;
        }

        .btn-prev-step {
            border-radius: 11px;
            font-weight: 600;
            font-size: 0.88rem;
            padding: 0.72rem 1.1rem;
            color: #475569;
            border: 1.5px solid #cbd5e1;
            background: #ffffff;
            transition: all 0.2s ease;
        }

        .btn-prev-step:hover {
            background-color: #f1f5f9;
            color: #0f172a;
            border-color: #94a3b8;
        }

        .alert-custom {
            border-radius: 10px;
            font-size: 0.82rem;
            padding: 0.65rem 0.85rem;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        }

        .field-feedback {
            font-size: 0.72rem;
            color: #dc3545;
            margin-top: 0.2rem;
            display: none;
        }

        .field-feedback.show {
            display: block;
        }

        /* Custom Multi-select Dropdown for Organisasi */
        .org-multiselect-container {
            position: relative;
        }

        .org-multiselect-trigger {
            width: 100%;
            min-height: 38px;
            padding: 0.35rem 2.2rem 0.35rem 2.5rem;
            background-color: #f8fafc;
            border: 1.5px solid var(--border-color);
            border-radius: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 4px;
            transition: all 0.2s ease;
            user-select: none;
            position: relative;
        }

        .org-multiselect-trigger:hover,
        .org-multiselect-trigger:focus {
            background-color: #ffffff;
            border-color: var(--primary-green);
        }

        .org-multiselect-trigger.active {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(4, 107, 38, 0.12);
            background-color: #ffffff;
        }

        .org-multiselect-arrow {
            position: absolute;
            right: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 0.85rem;
            pointer-events: none;
            transition: transform 0.2s ease;
        }

        .org-multiselect-trigger.active .org-multiselect-arrow {
            transform: translateY(-50%) rotate(180deg);
        }

        .org-badge-pill {
            background-color: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
            border-radius: 6px;
            padding: 0.1rem 0.45rem;
            font-size: 0.72rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            max-width: 170px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .org-badge-pill .pill-remove {
            color: #059669;
            cursor: pointer;
            font-size: 0.8rem;
            line-height: 1;
        }

        .org-badge-pill .pill-remove:hover {
            color: #dc2626;
        }

        .org-badge-more {
            background-color: #f1f5f9;
            color: #475569;
            border-radius: 6px;
            padding: 0.1rem 0.4rem;
            font-size: 0.7rem;
            font-weight: 700;
        }

        .org-dropdown-menu {
            display: none;
            position: absolute;
            top: calc(100% + 4px);
            left: 0;
            right: 0;
            background: #ffffff;
            border: 1.5px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.18);
            z-index: 1050;
            overflow: hidden;
        }

        .org-dropdown-menu.show {
            display: block;
            animation: fadeInDown 0.2s ease-in-out forwards;
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .org-search-header {
            padding: 0.5rem 0.75rem;
            background-color: #f8fafc;
            border-bottom: 1px solid var(--border-color);
        }

        .org-search-input {
            width: 100%;
            padding: 0.35rem 0.65rem 0.35rem 2rem;
            font-size: 0.8rem;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background-color: #ffffff;
            outline: none;
        }

        .org-search-input:focus {
            border-color: var(--primary-green);
        }

        .org-options-scroll {
            max-height: 200px;
            overflow-y: auto;
            padding: 0.25rem 0;
        }

        .org-category-title {
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--primary-green);
            background: #f0fdf4;
            padding: 0.3rem 0.85rem;
            margin: 0.2rem 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-top: 1px solid #edf2f7;
            border-bottom: 1px solid #edf2f7;
        }

        .org-category-title:first-child {
            margin-top: 0;
            border-top: none;
        }

        .org-checkbox-item {
            display: flex;
            align-items: center;
            padding: 0.35rem 0.85rem;
            cursor: pointer;
            margin: 0;
            transition: background-color 0.15s ease;
            user-select: none;
        }

        .org-checkbox-item:hover {
            background-color: #f8fafc;
        }

        .org-checkbox-item input[type="checkbox"] {
            margin-right: 0.6rem;
            cursor: pointer;
            accent-color: var(--primary-green);
            width: 15px;
            height: 15px;
        }

        .org-checkbox-item span {
            font-size: 0.8rem;
            color: #1e293b;
            flex: 1;
        }

        .org-dropdown-footer {
            padding: 0.45rem 0.75rem;
            background-color: #f8fafc;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Responsive Breakpoints */
        @media (max-width: 991.98px) {
            .card-left {
                display: none;
            }
            .main-card {
                max-width: 490px;
                min-height: auto;
                border-radius: 24px;
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
                display: block;
            }
            .card-right {
                padding: 2.2rem 1.5rem;
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="register-wrapper">
        <div class="main-card">
            <!-- Left Side: Brand and Benefits -->
            <div class="card-left">
                <div class="brand-header">
                    <img src="{{ asset('assets/img/logo_uis.png') }}" class="brand-logo" alt="Logo UIS" height="42" width="42">
                    <span class="brand-name">SIPENA UIS</span>
                </div>

                <div class="brand-body-content">
                    <span class="badge-tag">Registrasi Mahasiswa</span>
                    <h1 class="brand-title">
                        Catat & Buktikan<br>
                        <span class="text-gradient">Prestasi Terbaikmu</span>
                    </h1>
                    <p class="brand-desc">
                        Daftarkan akun SIPENA untuk mendokumentasikan kejuaraan, sertifikasi, rekognisi, serta keikutsertaan organisasi kemahasiswaan di Universitas Ibnu Sina.
                    </p>

                    <ul class="benefit-list">
                        <li>
                            <i class="bi bi-patch-check-fill"></i>
                            Pencatatan prestasi mandiri & Belmawa resmi
                        </li>
                        <li>
                            <i class="bi bi-person-vcard-fill"></i>
                            Pembuatan Kartu Tanda Anggota (KTM / KTA) otomatis
                        </li>
                        <li>
                            <i class="bi bi-award-fill"></i>
                            Terhubung langsung dengan HIMA & UKM UIS
                        </li>
                    </ul>
                </div>

                <div class="brand-footer">
                    &copy; {{ date('Y') }} Universitas Ibnu Sina. Hak Cipta Dilindungi Undang-Undang.
                </div>
            </div>

            <!-- Right Side: Multi-Step Registration Form -->
            <div class="card-right">
                <div class="register-card">
                    <!-- Mobile Header (Show only on small screens) -->
                    <div class="text-center d-lg-none mb-3">
                        <img src="{{ asset('assets/img/logo_uis.png') }}" alt="Logo UIS" height="54" width="54" class="mb-1">
                        <h2 class="fw-bold" style="color: var(--primary-green); font-family: 'Nunito', sans-serif; margin-bottom: 0; font-size: 1.45rem;">SIPENA UIS</h2>
                        <p class="text-secondary small mb-0" style="font-weight: 500;">Pendaftaran Akun Mahasiswa</p>
                    </div>

                    <!-- Step Header -->
                    <div class="register-header">
                        <h2 class="register-title" id="page-title">Daftar Akun Baru</h2>
                        <p class="register-desc" id="page-subtitle">Langkah 1/2: Lengkapi informasi akun login Anda.</p>
                    </div>

                    <!-- Step Progress Indicator -->
                    <div class="step-progress-wrapper">
                        <div class="step-item active" id="indicator-step-1">
                            <span class="step-badge" id="badge-step-1">1</span>
                            <span>Informasi Akun</span>
                        </div>
                        <div class="step-connector" id="connector-line"></div>
                        <div class="step-item" id="indicator-step-2">
                            <span class="step-badge" id="badge-step-2">2</span>
                            <span>Data Mahasiswa & Ormawa</span>
                        </div>
                    </div>

                    <!-- Alert Messages -->
                    @if ($errors->any())
                        <div class="alert alert-danger alert-custom mb-3" role="alert">
                            <div class="d-flex align-items-center mb-1">
                                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                                <span class="fw-bold">Pendaftaran Belum Berhasil</span>
                            </div>
                            <ul class="mb-0 ps-3 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.post') }}" id="register-form" novalidate>
                        @csrf

                        <!-- ============================================== -->
                        <!-- STEP 1: INFORMASI AKUN                         -->
                        <!-- ============================================== -->
                        <div class="step-content active" id="step-pane-1">
                            <!-- Nama Lengkap -->
                            <div class="mb-2">
                                <label for="name" class="form-label">Nama Lengkap Mahasiswa <span class="text-danger">*</span></label>
                                <div class="input-wrapper">
                                    <span class="input-icon"><i class="bi bi-person"></i></span>
                                    <input type="text" name="name" id="name" 
                                        class="form-input @error('name') is-invalid @enderror" 
                                        placeholder="Contoh: Frida Ayu Wulandari" 
                                        value="{{ old('name') }}" 
                                        required autofocus>
                                </div>
                                <div class="field-feedback" id="feedback-name">Nama lengkap wajib diisi.</div>
                            </div>

                            <!-- Email -->
                            <div class="mb-2">
                                <label for="email" class="form-label">Alamat Email Aktif <span class="text-danger">*</span></label>
                                <div class="input-wrapper">
                                    <span class="input-icon"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" id="email" 
                                        class="form-input @error('email') is-invalid @enderror" 
                                        placeholder="nama@student.uis.ac.id" 
                                        value="{{ old('email') }}" 
                                        required autocomplete="email">
                                </div>
                                <div class="field-feedback" id="feedback-email">Format alamat email tidak valid.</div>
                                <div class="form-text text-muted" style="font-size: 0.72rem; margin-top: 0.15rem;">
                                    Dianjurkan menggunakan email institusi mahasiswa UIS.
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="mb-2">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <div class="input-wrapper">
                                    <span class="input-icon"><i class="bi bi-lock"></i></span>
                                    <input type="password" name="password" id="password" 
                                        class="form-input password-input @error('password') is-invalid @enderror" 
                                        placeholder="Minimal 6 karakter" 
                                        required autocomplete="new-password">
                                    <span class="password-toggle" id="togglePassword">
                                        <i class="bi bi-eye" id="toggleIcon"></i>
                                    </span>
                                </div>
                                <div class="field-feedback" id="feedback-password">Password minimal terdiri dari 6 karakter.</div>
                            </div>

                            <!-- Konfirmasi Password -->
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                <div class="input-wrapper">
                                    <span class="input-icon"><i class="bi bi-shield-check"></i></span>
                                    <input type="password" name="password_confirmation" id="password_confirmation" 
                                        class="form-input password-input" 
                                        placeholder="Ulangi password di atas" 
                                        required autocomplete="new-password">
                                    <span class="password-toggle" id="toggleConfirmPassword">
                                        <i class="bi bi-eye" id="toggleConfirmIcon"></i>
                                    </span>
                                </div>
                                <div class="field-feedback" id="feedback-confirm-password">Konfirmasi password tidak cocok.</div>
                            </div>

                            <button class="btn btn-register" type="button" id="btn-next-step">
                                Lanjut: Data Mahasiswa & Organisasi <i class="bi bi-arrow-right ms-1"></i>
                            </button>
                        </div>

                        <!-- ============================================== -->
                        <!-- STEP 2: DATA MAHASISWA & ORGANISASI             -->
                        <!-- ============================================== -->
                        <div class="step-content" id="step-pane-2">
                            <!-- NIM -->
                            <div class="mb-2">
                                <label for="nim" class="form-label">NIM <small class="text-muted">(Nomor Induk Mahasiswa)</small></label>
                                <div class="input-wrapper">
                                    <span class="input-icon"><i class="bi bi-card-heading"></i></span>
                                    <input type="text" name="nim" id="nim" 
                                        class="form-input @error('nim') is-invalid @enderror" 
                                        placeholder="Contoh: 2021001234" 
                                        value="{{ old('nim') }}">
                                </div>
                            </div>

                            <div class="row g-2 mb-2">
                                <!-- Fakultas -->
                                <div class="col-md-6">
                                    <label for="fakultas" class="form-label">Fakultas</label>
                                    <div class="input-wrapper">
                                        <span class="input-icon"><i class="bi bi-buildings"></i></span>
                                        <select name="fakultas" id="fakultas" class="form-input">
                                            <option value="">-- Pilih Fakultas --</option>
                                            @foreach($fakultas as $key => $label)
                                                <option value="{{ $key }}" {{ old('fakultas') == $key ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Program Studi -->
                                <div class="col-md-6">
                                    <label for="program_studi" class="form-label">Program Studi</label>
                                    <div class="input-wrapper">
                                        <span class="input-icon"><i class="bi bi-mortarboard"></i></span>
                                        <select name="program_studi" id="program_studi" class="form-input">
                                            <option value="">-- Pilih Program Studi --</option>
                                            @foreach($prodis as $fakName => $listProdi)
                                                <optgroup label="{{ $fakName }}" data-fakultas="{{ $fakName }}">
                                                    @foreach($listProdi as $pVal => $pLabel)
                                                        <option value="{{ $pVal }}" data-fakultas="{{ $fakName }}" {{ old('program_studi') == $pVal ? 'selected' : '' }}>
                                                            {{ $pLabel }}
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Organisasi / Ormawa Multi-select -->
                            <div class="mb-2">
                                <label class="form-label d-flex justify-content-between align-items-center mb-1">
                                    <span>Organisasi / Ormawa UIS <small class="text-muted">(Bisa pilih lebih dari 1)</small></span>
                                    <span class="badge bg-light text-success border border-success-subtle fw-semibold" id="org-counter-badge" style="font-size: 0.7rem;">0 dipilih</span>
                                </label>
                                <div class="org-multiselect-container" id="org-container">
                                    <!-- Trigger box -->
                                    <div class="org-multiselect-trigger" id="org-trigger" tabindex="0">
                                        <span class="input-icon"><i class="bi bi-people"></i></span>
                                        <div id="org-badges-wrapper" class="d-flex align-items-center flex-wrap gap-1">
                                            <span class="text-muted small" id="org-placeholder-text">-- Klik untuk memilih Organisasi / Ormawa --</span>
                                        </div>
                                        <i class="bi bi-chevron-down org-multiselect-arrow"></i>
                                    </div>

                                    <!-- Dropdown menu -->
                                    <div class="org-dropdown-menu" id="org-dropdown-menu">
                                        <div class="org-search-header">
                                            <div class="position-relative">
                                                <i class="bi bi-search text-muted position-absolute" style="left: 0.6rem; top: 50%; transform: translateY(-50%); font-size: 0.8rem;"></i>
                                                <input type="text" id="org-search-box" class="org-search-input" placeholder="Cari BEM, HIMA, atau UKM..." autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="org-options-scroll" id="org-options-container">
                                            @foreach($organisasi as $kategori => $daftarOrg)
                                                <div class="org-category-title">{{ $kategori }}</div>
                                                @foreach($daftarOrg as $orgKey => $orgLabel)
                                                    @php
                                                        $isOldChecked = (is_array(old('organisasi')) && in_array($orgKey, old('organisasi'))) || 
                                                                        (is_string(old('organisasi')) && in_array($orgKey, array_map('trim', explode(',', old('organisasi')))));
                                                    @endphp
                                                    <label class="org-checkbox-item" data-search="{{ strtolower($orgKey . ' ' . $orgLabel . ' ' . $kategori) }}">
                                                        <input type="checkbox" name="organisasi[]" value="{{ $orgKey }}" class="org-item-checkbox" data-label="{{ $orgLabel }}" {{ $isOldChecked ? 'checked' : '' }}>
                                                        <span>{{ $orgLabel }}</span>
                                                    </label>
                                                @endforeach
                                            @endforeach
                                        </div>
                                        <div class="org-dropdown-footer">
                                            <button type="button" class="btn btn-link btn-sm text-danger text-decoration-none p-0 small" id="btn-reset-org" style="font-size: 0.76rem;">Reset Pilihan</button>
                                            <button type="button" class="btn btn-success btn-sm px-3 py-1" id="btn-done-org" style="font-size: 0.76rem; border-radius: 6px;">Selesai</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Jabatan di Organisasi -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label for="jabatan" class="form-label mb-0">Jabatan di Organisasi</label>
                                    <span id="jabatan-context-hint" class="small text-muted" style="font-size: 0.75rem;">Sesuai SK Pengurus 2026-2027</span>
                                </div>
                                <div class="input-wrapper">
                                    <span class="input-icon"><i class="bi bi-briefcase"></i></span>
                                    <select name="jabatan" id="jabatan" class="form-input">
                                        <option value="">-- Pilih Jabatan / Posisi --</option>
                                        @foreach($jabatan as $groupLabel => $items)
                                            <optgroup label="{{ $groupLabel }}">
                                                @foreach($items as $val => $text)
                                                    <option value="{{ $val }}" {{ old('jabatan') == $val ? 'selected' : '' }}>{{ $text }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="wrapper-jabatan-custom" class="mt-2" style="display: {{ old('jabatan') === 'Lainnya' || old('jabatan_custom') ? 'block' : 'none' }};">
                                    <input type="text" name="jabatan_custom" id="jabatan_custom" class="form-input" style="font-size: 0.85rem;" placeholder="Ketik nama jabatan / departemen spesifik..." value="{{ old('jabatan_custom') }}">
                                    <span class="form-text small text-muted" style="font-size: 0.74rem;">Contoh: <em>Kepala Departemen Advokasi dan Kajian Hukum</em> atau <em>PIC Publikasi dan Dokumentasi</em></span>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button class="btn btn-prev-step" type="button" id="btn-prev-step">
                                    <i class="bi bi-arrow-left me-1"></i> Kembali
                                </button>
                                <button class="btn btn-register flex-grow-1" type="submit" id="btn-submit-register">
                                    <i class="bi bi-person-plus-fill me-1"></i> Buat Akun Mahasiswa
                                </button>
                            </div>
                        </div>

                        <!-- Login Redirect Link -->
                        <div class="text-center mt-3 pt-2 border-top">
                            <p class="text-muted small mb-0">
                                Sudah memiliki akun? 
                                <a href="{{ route('login') }}" class="fw-bold text-success text-decoration-none">Masuk ke Sistem</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Multi-step & Form Scripts -->
    <script>
        // Password toggles
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const toggleIcon = document.querySelector('#toggleIcon');

        if (togglePassword && password && toggleIcon) {
            togglePassword.addEventListener('click', function () {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                toggleIcon.classList.toggle('bi-eye');
                toggleIcon.classList.toggle('bi-eye-slash');
            });
        }

        const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
        const passwordConfirmation = document.querySelector('#password_confirmation');
        const toggleConfirmIcon = document.querySelector('#toggleConfirmIcon');

        if (toggleConfirmPassword && passwordConfirmation && toggleConfirmIcon) {
            toggleConfirmPassword.addEventListener('click', function () {
                const type = passwordConfirmation.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordConfirmation.setAttribute('type', type);
                toggleConfirmIcon.classList.toggle('bi-eye');
                toggleConfirmIcon.classList.toggle('bi-eye-slash');
            });
        }

        // Multi-step elements
        const stepPane1 = document.getElementById('step-pane-1');
        const stepPane2 = document.getElementById('step-pane-2');
        const btnNext = document.getElementById('btn-next-step');
        const btnPrev = document.getElementById('btn-prev-step');
        const indicator1 = document.getElementById('indicator-step-1');
        const indicator2 = document.getElementById('indicator-step-2');
        const badge1 = document.getElementById('badge-step-1');
        const badge2 = document.getElementById('badge-step-2');
        const connectorLine = document.getElementById('connector-line');
        const pageTitle = document.getElementById('page-title');
        const pageSubtitle = document.getElementById('page-subtitle');

        const inputName = document.getElementById('name');
        const inputEmail = document.getElementById('email');
        const inputPassword = document.getElementById('password');
        const inputPasswordConfirm = document.getElementById('password_confirmation');

        const feedbackName = document.getElementById('feedback-name');
        const feedbackEmail = document.getElementById('feedback-email');
        const feedbackPassword = document.getElementById('feedback-password');
        const feedbackConfirmPassword = document.getElementById('feedback-confirm-password');

        function goToStep(step) {
            if (step === 2) {
                stepPane1.classList.remove('active');
                stepPane2.classList.add('active');

                indicator1.classList.remove('active');
                indicator1.classList.add('completed');
                badge1.innerHTML = '<i class="bi bi-check-lg"></i>';

                indicator2.classList.add('active');
                connectorLine.classList.add('active');

                pageTitle.textContent = 'Data Akademik & Ormawa';
                pageSubtitle.textContent = 'Langkah 2/2: Lengkapi fakultas, prodi, dan keikutsertaan organisasi.';
            } else {
                stepPane2.classList.remove('active');
                stepPane1.classList.add('active');

                indicator1.classList.add('active');
                indicator1.classList.remove('completed');
                badge1.textContent = '1';

                indicator2.classList.remove('active');
                connectorLine.classList.remove('active');

                pageTitle.textContent = 'Daftar Akun Baru';
                pageSubtitle.textContent = 'Langkah 1/2: Lengkapi informasi akun login Anda.';
            }
        }

        function validateStep1() {
            let isValid = true;

            // Reset feedbacks
            [inputName, inputEmail, inputPassword, inputPasswordConfirm].forEach(el => el.classList.remove('is-invalid'));
            [feedbackName, feedbackEmail, feedbackPassword, feedbackConfirmPassword].forEach(el => el.classList.remove('show'));

            // Name
            if (!inputName.value.trim()) {
                inputName.classList.add('is-invalid');
                feedbackName.classList.add('show');
                isValid = false;
            }

            // Email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!inputEmail.value.trim() || !emailRegex.test(inputEmail.value.trim())) {
                inputEmail.classList.add('is-invalid');
                feedbackEmail.classList.add('show');
                isValid = false;
            }

            // Password
            if (!inputPassword.value || inputPassword.value.length < 6) {
                inputPassword.classList.add('is-invalid');
                feedbackPassword.classList.add('show');
                isValid = false;
            }

            // Confirm Password
            if (!inputPasswordConfirm.value || inputPasswordConfirm.value !== inputPassword.value) {
                inputPasswordConfirm.classList.add('is-invalid');
                feedbackConfirmPassword.classList.add('show');
                isValid = false;
            }

            return isValid;
        }

        if (btnNext) {
            btnNext.addEventListener('click', function () {
                if (validateStep1()) {
                    goToStep(2);
                }
            });
        }

        if (btnPrev) {
            btnPrev.addEventListener('click', function () {
                goToStep(1);
            });
        }

        // Allow clicking indicator to switch if valid
        if (indicator1) {
            indicator1.addEventListener('click', function () {
                goToStep(1);
            });
        }
        if (indicator2) {
            indicator2.addEventListener('click', function () {
                if (validateStep1()) {
                    goToStep(2);
                }
            });
        }

        // Dynamic Filtering: Fakultas -> Program Studi
        const selectFakultas = document.getElementById('fakultas');
        const selectProdi = document.getElementById('program_studi');

        function filterProdiByFakultas() {
            const selectedFak = selectFakultas.value;
            const optgroups = selectProdi.querySelectorAll('optgroup');
            const options = selectProdi.querySelectorAll('option[data-fakultas]');

            if (!selectedFak) {
                // Show all optgroups
                optgroups.forEach(og => og.style.display = '');
                options.forEach(opt => opt.style.display = '');
                return;
            }

            let firstMatch = '';
            optgroups.forEach(og => {
                if (og.getAttribute('data-fakultas') === selectedFak) {
                    og.style.display = '';
                } else {
                    og.style.display = 'none';
                }
            });

            options.forEach(opt => {
                if (opt.getAttribute('data-fakultas') === selectedFak) {
                    opt.style.display = '';
                    if (!firstMatch) firstMatch = opt.value;
                } else {
                    opt.style.display = 'none';
                }
            });

            // If current prodi value does not belong to selected fakultas, reset it
            const currentSelected = selectProdi.selectedOptions[0];
            if (currentSelected && currentSelected.getAttribute('data-fakultas') && currentSelected.getAttribute('data-fakultas') !== selectedFak) {
                selectProdi.value = '';
            }
        }

        if (selectFakultas && selectProdi) {
            selectFakultas.addEventListener('change', filterProdiByFakultas);

            // Auto-select Fakultas when user chooses Program Studi
            selectProdi.addEventListener('change', function () {
                const opt = selectProdi.selectedOptions[0];
                if (opt && opt.getAttribute('data-fakultas')) {
                    selectFakultas.value = opt.getAttribute('data-fakultas');
                }
            });

            // Initial filter run if old values exist
            if (selectFakultas.value) {
                filterProdiByFakultas();
            }
        }

        // Organisasi Multi-select Logic
        const orgTrigger = document.getElementById('org-trigger');
        const orgDropdownMenu = document.getElementById('org-dropdown-menu');
        const orgBadgesWrapper = document.getElementById('org-badges-wrapper');
        const orgPlaceholder = document.getElementById('org-placeholder-text');
        const orgCounterBadge = document.getElementById('org-counter-badge');
        const orgCheckboxes = document.querySelectorAll('.org-item-checkbox');
        const orgSearchBox = document.getElementById('org-search-box');
        const btnResetOrg = document.getElementById('btn-reset-org');
        const btnDoneOrg = document.getElementById('btn-done-org');
        const orgContainer = document.getElementById('org-container');

        function updateOrgDisplay() {
            const checked = Array.from(orgCheckboxes).filter(cb => cb.checked);
            orgBadgesWrapper.innerHTML = '';

            if (checked.length === 0) {
                orgBadgesWrapper.appendChild(orgPlaceholder);
                orgCounterBadge.textContent = '0 dipilih';
                orgCounterBadge.className = 'badge bg-light text-muted border fw-semibold';
            } else {
                orgCounterBadge.textContent = checked.length + ' dipilih';
                orgCounterBadge.className = 'badge bg-success-subtle text-success border border-success-subtle fw-semibold';

                const maxDisplay = 2;
                checked.slice(0, maxDisplay).forEach(cb => {
                    const pill = document.createElement('span');
                    pill.className = 'org-badge-pill';
                    const shortText = cb.getAttribute('data-label').split('(')[0].trim();
                    pill.innerHTML = `<span>${shortText}</span><i class="bi bi-x pill-remove" data-val="${cb.value}"></i>`;
                    orgBadgesWrapper.appendChild(pill);
                });

                if (checked.length > maxDisplay) {
                    const moreBadge = document.createElement('span');
                    moreBadge.className = 'org-badge-more';
                    moreBadge.textContent = `+${checked.length - maxDisplay} lainnya`;
                    orgBadgesWrapper.appendChild(moreBadge);
                }
            }
        }

        if (orgTrigger && orgDropdownMenu) {
            orgTrigger.addEventListener('click', function(e) {
                if (e.target.classList.contains('pill-remove')) {
                    const val = e.target.getAttribute('data-val');
                    const targetCb = document.querySelector(`.org-item-checkbox[value="${CSS.escape(val)}"]`);
                    if (targetCb) {
                        targetCb.checked = false;
                        updateOrgDisplay();
                    }
                    return;
                }
                orgDropdownMenu.classList.toggle('show');
                orgTrigger.classList.toggle('active');
                if (orgDropdownMenu.classList.contains('show') && orgSearchBox) {
                    orgSearchBox.focus();
                }
            });

            document.addEventListener('click', function(e) {
                if (orgContainer && !orgContainer.contains(e.target)) {
                    orgDropdownMenu.classList.remove('show');
                    orgTrigger.classList.remove('active');
                }
            });

            if (btnDoneOrg) {
                btnDoneOrg.addEventListener('click', function() {
                    orgDropdownMenu.classList.remove('show');
                    orgTrigger.classList.remove('active');
                });
            }

            if (btnResetOrg) {
                btnResetOrg.addEventListener('click', function() {
                    orgCheckboxes.forEach(cb => cb.checked = false);
                    updateOrgDisplay();
                });
            }

            orgCheckboxes.forEach(cb => {
                cb.addEventListener('change', updateOrgDisplay);
            });

            if (orgSearchBox) {
                orgSearchBox.addEventListener('input', function() {
                    const q = this.value.toLowerCase().trim();
                    document.querySelectorAll('.org-checkbox-item').forEach(item => {
                        const searchTxt = item.getAttribute('data-search') || '';
                        item.style.display = searchTxt.includes(q) ? 'flex' : 'none';
                    });
                });
            }

            // Initial display update
            updateOrgDisplay();
        }

        // ==========================================
        // Dynamic Jabatan Options Logic (SK 2026-2027)
        // ==========================================
        const JABATAN_BY_ORG = @json($jabatan_by_org ?? []);
        const JABATAN_GENERAL = @json($jabatan ?? []);
        const jabatanSelect = document.getElementById('jabatan');
        const jabatanCustomWrapper = document.getElementById('wrapper-jabatan-custom');
        const jabatanCustomInput = document.getElementById('jabatan_custom');
        const jabatanHint = document.getElementById('jabatan-context-hint');
        const initialSelectedJabatan = "{{ old('jabatan') }}";

        function updateJabatanOptions() {
            if (!jabatanSelect) return;

            const checkedBoxes = Array.from(document.querySelectorAll('.org-item-checkbox:checked'));
            const checkedValues = checkedBoxes.map(cb => cb.value);

            let matchingGroups = {};
            let hasOrgMatch = false;

            checkedValues.forEach(orgKey => {
                if (JABATAN_BY_ORG[orgKey]) {
                    hasOrgMatch = true;
                    matchingGroups[orgKey] = JABATAN_BY_ORG[orgKey];
                }
            });

            const currentVal = jabatanSelect.value || initialSelectedJabatan;
            jabatanSelect.innerHTML = '<option value="">-- Pilih Jabatan / Posisi --</option>';

            if (hasOrgMatch) {
                if (jabatanHint) {
                    jabatanHint.innerHTML = '<span class="text-success fw-semibold"><i class="bi bi-shield-check me-1"></i>Sesuai SK Pengurus 2026-2027</span>';
                }

                for (const [orgName, groups] of Object.entries(matchingGroups)) {
                    const shortOrgName = orgName.split(' - ')[0].replace('Universitas Ibnu Sina', 'UIS').trim();
                    for (const [groupName, items] of Object.entries(groups)) {
                        const optgroup = document.createElement('optgroup');
                        optgroup.label = `${shortOrgName} • ${groupName}`;
                        items.forEach(item => {
                            const opt = document.createElement('option');
                            opt.value = item;
                            opt.textContent = item;
                            if (item === currentVal) {
                                opt.selected = true;
                            }
                            optgroup.appendChild(opt);
                        });
                        jabatanSelect.appendChild(optgroup);
                    }
                }
            } else {
                if (jabatanHint) {
                    jabatanHint.textContent = 'Pilihan Jabatan Standar';
                }
                for (const [groupName, items] of Object.entries(JABATAN_GENERAL)) {
                    const optgroup = document.createElement('optgroup');
                    optgroup.label = groupName;
                    for (const [val, label] of Object.entries(items)) {
                        const opt = document.createElement('option');
                        opt.value = val;
                        opt.textContent = label;
                        if (val === currentVal) {
                            opt.selected = true;
                        }
                        optgroup.appendChild(opt);
                    }
                    jabatanSelect.appendChild(optgroup);
                }
            }

            // Selalu tambahkan opsi Lainnya jika belum ada di dalam select
            const hasLainnya = Array.from(jabatanSelect.options).some(o => o.value === 'Lainnya');
            if (!hasLainnya) {
                const optLainnya = document.createElement('option');
                optLainnya.value = 'Lainnya';
                optLainnya.textContent = 'Lainnya (Ketik Manual)...';
                if (currentVal === 'Lainnya') {
                    optLainnya.selected = true;
                }
                jabatanSelect.appendChild(optLainnya);
            }

            // Jika nilai yang tersimpan sebelumnya adalah custom text yang bukan dari daftar, pertahankan
            if (currentVal && currentVal !== 'Lainnya' && !Array.from(jabatanSelect.options).some(o => o.value === currentVal)) {
                const customOpt = document.createElement('option');
                customOpt.value = currentVal;
                customOpt.textContent = currentVal;
                customOpt.selected = true;
                jabatanSelect.insertBefore(customOpt, jabatanSelect.lastElementChild);
            }

            checkCustomJabatanState();
        }

        function checkCustomJabatanState() {
            if (!jabatanSelect || !jabatanCustomWrapper) return;
            if (jabatanSelect.value === 'Lainnya') {
                jabatanCustomWrapper.style.display = 'block';
                if (jabatanCustomInput && !jabatanCustomInput.value) {
                    jabatanCustomInput.focus();
                }
            } else {
                jabatanCustomWrapper.style.display = 'none';
            }
        }

        if (jabatanSelect) {
            jabatanSelect.addEventListener('change', checkCustomJabatanState);
        }

        // Jalankan update jabatan saat ada perubahan checkbox organisasi
        orgCheckboxes.forEach(cb => {
            cb.addEventListener('change', updateJabatanOptions);
        });
        if (btnResetOrg) {
            btnResetOrg.addEventListener('click', function() {
                setTimeout(updateJabatanOptions, 10);
            });
        }

        // Inisialisasi awal
        updateJabatanOptions();

        // If backend returned errors on Step 2 fields, auto switch to Step 2
        @if ($errors->has('nim') || $errors->has('fakultas') || $errors->has('program_studi') || $errors->has('organisasi') || $errors->has('jabatan'))
            goToStep(2);
        @endif
    </script>
</body>

</html>
