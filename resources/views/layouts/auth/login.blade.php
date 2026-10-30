<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Login | SIMAWA - Sistem Informasi Manajemen Kemahasiswaan</title>
    <meta content="Portal Login Sistem Informasi Manajemen Kemahasiswaan (SIMAWA)" name="description">
    <meta content="simawa, kemahasiswaan, login, tata kelola, kinerja, uis" name="keywords">

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

    <!-- Custom Pinterest-inspired Glassmorphism Style -->
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
            height: 100vh;
            overflow: hidden;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: radial-gradient(circle at 10% 20%, #03210b 0%, #050d07 90%);
        }

        .login-wrapper {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        /* Abstract glowing blobs for premium design depth */
        .login-wrapper::before {
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

        .login-wrapper::after {
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

        /* Centered Dual-Pane Floating Card */
        .main-card {
            width: 1020px;
            max-width: 100%;
            height: 590px;
            display: flex;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 35px 70px rgba(0, 0, 0, 0.45);
            border: 1px solid rgba(254, 216, 2, 0.12);
            z-index: 1;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(15px);
        }

        /* Left Side: Modern Brand and Illustration */
        .card-left {
            flex: 1.25;
            background: linear-gradient(135deg, rgba(4, 107, 38, 0.85) 0%, rgba(5, 13, 7, 0.95) 100%);
            padding: 2rem 2.2rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: #ffffff;
            position: relative;
            border-right: 1px solid rgba(254, 216, 2, 0.08);
        }

        .brand-header {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .brand-logo {
            filter: drop-shadow(0 4px 10px rgba(254, 216, 2, 0.25));
        }

        .brand-name {
            font-family: 'Nunito', sans-serif;
            font-weight: 800;
            font-size: 1.5rem;
            letter-spacing: 0.5px;
            color: #ffffff;
        }

        .brand-body-content {
            position: relative;
            z-index: 2;
            max-width: 58%;
            margin: auto 0;
        }

        .badge-tag {
            display: inline-block;
            background: rgba(254, 216, 2, 0.12);
            color: #FED802;
            border: 1px solid rgba(254, 216, 2, 0.25);
            padding: 0.4rem 1.2rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 1rem;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }

        .brand-title {
            font-size: 1.85rem;
            font-weight: 700;
            line-height: 1.25;
            margin-bottom: 0.75rem;
            color: #ffffff;
            border-left: 3px solid #FED802;
            padding-left: 1.2rem;
        }

        .text-gradient {
            background: var(--accent-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 850;
        }

        .brand-desc {
            font-size: 0.88rem;
            color: var(--text-muted);
            line-height: 1.5;
            margin-bottom: 1.2rem;
            max-width: 460px;
        }

        .illustration-container {
            position: absolute;
            bottom: 120px; /* Moved up to display the full trophy base clearly */
            right: 10px;  /* Inset slightly for a balanced look */
            width: 48%;
            height: 75%;
            display: flex;
            align-items: flex-end;
            justify-content: flex-end;
            z-index: 1;
            pointer-events: none;
        }

        .illustration-container::before {
            content: '';
            position: absolute;
            width: 250px;
            height: 250px;
            border-radius: 50%;
            background: rgba(254, 216, 2, 0.12);
            filter: blur(60px);
            z-index: 0;
            bottom: 20%;
            right: 20%;
        }

        .trophy-illustration {
            max-width: 100%;
            height: auto;
            max-height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.25));
            animation: float-illustration 6s ease-in-out infinite;
            position: relative;
            z-index: 1;
        }

        .brand-footer {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        /* Right Side: Clean Login Form */
        .card-right {
            flex: 0.85;
            background-color: #ffffff;
            padding: 3rem 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 100%;
        }

        .login-header {
            margin-bottom: 1.8rem;
        }

        .login-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--slate-dark);
            margin-bottom: 0.4rem;
        }

        .login-desc {
            font-size: 0.88rem;
            color: var(--slate-medium);
        }

        .form-label {
            font-weight: 600;
            font-size: 0.82rem;
            color: var(--slate-medium);
            margin-bottom: 0.4rem;
        }

        /* Custom Modern Inputs */
        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            border: 1px solid var(--border-color);
            background-color: var(--slate-light);
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
            margin-bottom: 1.1rem;
        }

        .input-wrapper:focus-within {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 4px rgba(4, 107, 38, 0.1);
            background-color: #ffffff;
        }

        .input-icon {
            padding-left: 1.1rem;
            padding-right: 0.8rem;
            color: var(--slate-medium);
            font-size: 1rem;
        }

        .form-input {
            border: none;
            background-color: transparent;
            padding: 0.7rem 1rem 0.7rem 0.1rem;
            font-size: 0.9rem;
            color: var(--slate-dark);
            width: 100%;
            outline: none;
        }

        .password-toggle {
            cursor: pointer;
            padding-right: 1.1rem;
            padding-left: 0.8rem;
            color: var(--slate-medium);
            font-size: 1rem;
            transition: color 0.2s ease;
        }

        .password-toggle:hover {
            color: var(--slate-dark);
        }

        /* Button & UI Links */
        .btn-login {
            background: var(--primary-gradient);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.85rem;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(4, 107, 38, 0.2);
            text-align: center;
            width: 100%;
            margin-top: 0.5rem;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(4, 107, 38, 0.35);
            background: linear-gradient(135deg, #034f1c 0%, #046B26 100%);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert-custom {
            border-radius: 12px;
            font-size: 0.85rem;
            padding: 0.8rem 1.1rem;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        }

        /* Floating Animation */
        @keyframes float-illustration {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(1deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }

        /* Responsive Breakpoints */
        @media (max-width: 991.98px) {
            html, body {
                height: auto;
                overflow: auto;
            }
            .card-left {
                display: none;
            }
            .main-card {
                max-width: 440px;
                height: auto;
                border-radius: 24px;
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
                border: 1px solid rgba(254, 216, 2, 0.15);
                display: block; /* Disable flex on mobile to prevent layout gaps */
            }
            .card-right {
                padding: 2.5rem 2rem;
                width: 100%;
                flex: none; /* Reset flex width to take full block container */
            }
            .login-wrapper {
                background: radial-gradient(circle at 10% 20%, #03210b 0%, #050d07 90%);
                height: auto;
                min-height: 100vh;
                padding: 1.5rem 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="login-wrapper">
        <div class="main-card">
            <!-- Left Side: Brand and Illustration -->
            <div class="card-left">
                <div class="brand-header">
                    <img src="{{ asset('assets/img/logo_uis.png') }}" class="brand-logo" alt="Logo UIS" height="42" width="42">
                    <span class="brand-name">SIMAWA UIS</span>
                </div>

                <div class="brand-body-content">
                    <span class="badge-tag">Universitas Ibnu Sina</span>
                    <h1 class="brand-title">
                        Sistem Informasi Kinerja &<br>
                        <span class="text-gradient">Tata Kelola Kemahasiswaan</span>
                    </h1>
                    <p class="brand-desc">
                        Portal integratif untuk pencatatan prestasi, monitoring kinerja organisasi, serta standardisasi kegiatan kemahasiswaan Universitas Ibnu Sina.
                    </p>
                </div>

                <!-- Student Trophy Illustration - Absolute Positioned to float on the right -->
                <div class="illustration-container">
                    <img src="{{ asset('assets/img/student.png') }}" class="trophy-illustration" alt="Prestasi Mahasiswa UIS">
                </div>

                <div class="brand-footer">
                    &copy; {{ date('Y') }} Universitas Ibnu Sina. Hak Cipta Dilindungi Undang-Undang.
                </div>
            </div>

            <!-- Right Side: Login Form -->
            <div class="card-right">
                <div class="login-card">
                    <!-- Mobile Header (Show only on small screens) -->
                    <div class="text-center d-lg-none mb-4">
                        <img src="{{ asset('assets/img/logo_uis.png') }}" alt="Logo UIS" height="60" width="60" class="mb-2">
                        <h2 class="fw-bold" style="color: var(--primary-green); font-family: 'Nunito', sans-serif; margin-bottom: 0; font-size: 1.6rem;">SIMAWA UIS</h2>
                        <p class="text-secondary small mb-0" style="font-weight: 500;">Kemahasiswaan Universitas Ibnu Sina</p>
                    </div>

                    <div class="login-header">
                        <h2 class="login-title">Selamat Datang</h2>
                        <p class="login-desc">Silakan masuk menggunakan akun terdaftar Anda.</p>
                    </div>

                    <!-- Alert Messages -->
                    @if (session('success'))
                        <div class="alert alert-success alert-custom d-flex align-items-center mb-4" role="alert">
                            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-custom mb-4" role="alert">
                            <div class="d-flex align-items-center mb-1">
                                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                                <span class="fw-bold">Login Gagal</span>
                            </div>
                            <ul class="mb-0 ps-3 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.post') }}" class="needs-validation" novalidate>
                        @csrf
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Alamat Email</label>
                            <div class="input-wrapper">
                                <span class="input-icon"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" id="email" 
                                    class="form-input" 
                                    placeholder="nama@email.com" 
                                    value="{{ old('email') }}" 
                                    required autocomplete="email" autofocus>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="password" class="form-label mb-0">Password</label>
                            </div>
                            <div class="input-wrapper">
                                <span class="input-icon"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" id="password" 
                                    class="form-input password-input" 
                                    placeholder="Masukkan password" 
                                    required autocomplete="current-password">
                                <span class="password-toggle" id="togglePassword">
                                    <i class="bi bi-eye" id="toggleIcon"></i>
                                </span>
                            </div>
                        </div>
                        <button class="btn btn-login" type="submit">
                            <i class="bi bi-box-arrow-in-right me-2"></i> Masuk ke Sistem
                        </button>

                        <div class="text-center mt-3 pt-2 border-top">
                            <p class="text-muted small mb-0">
                                Belum memiliki akun mahasiswa? 
                                <a href="{{ route('register') }}" class="fw-bold text-success text-decoration-none">Daftar Sekarang</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Toggle Password Visibility Script -->
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const toggleIcon = document.querySelector('#toggleIcon');

        togglePassword.addEventListener('click', function (e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            toggleIcon.classList.toggle('bi-eye');
            toggleIcon.classList.toggle('bi-eye-slash');
        });
    </script>
</body>

</html>
