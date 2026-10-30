  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="{{ route('dashboard.index') }}" class="logo d-flex align-items-center">
        <img src="{{ asset('assets/img/logo_uis.png') }}" alt="Logo UIS" style="max-height: 32px; margin-right: 6px;">
        <span class="d-none d-lg-block" style="font-family: 'Nunito', sans-serif; font-weight: 800; color: #1e3c72; letter-spacing: 0.5px;">SIMAWA UIS</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-bell"></i>
            @if(isset($headerUnreadCount) && $headerUnreadCount > 0)
              <span class="badge bg-primary badge-number">{{ $headerUnreadCount }}</span>
            @endif
          </a><!-- End Notification Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications" style="max-width: 340px; min-width: 300px;">
            <li class="dropdown-header">
              @if(isset($headerUnreadCount) && $headerUnreadCount > 0)
                Ada {{ $headerUnreadCount }} aktivitas pengajuan terbaru
              @else
                Tidak ada pemberitahuan baru
              @endif
            </li>

            @if(isset($headerNotifications) && count($headerNotifications) > 0)
              @foreach($headerNotifications as $notif)
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li class="notification-item py-2 px-3">
                  <a href="{{ $notif['url'] }}" class="d-flex text-decoration-none text-dark">
                    @if(in_array($notif['status'], ['Terverifikasi', 'Verified']))
                      <i class="bi bi-check-circle text-success fs-5 me-2 flex-shrink-0 mt-1"></i>
                    @elseif(in_array($notif['status'], ['Ditolak', 'Rejected']))
                      <i class="bi bi-x-circle text-danger fs-5 me-2 flex-shrink-0 mt-1"></i>
                    @elseif(in_array($notif['status'], ['Submitted', 'Verifikasi', 'Draft']))
                      <i class="bi bi-exclamation-circle text-warning fs-5 me-2 flex-shrink-0 mt-1"></i>
                    @else
                      <i class="bi bi-info-circle text-primary fs-5 me-2 flex-shrink-0 mt-1"></i>
                    @endif
                    <div class="overflow-hidden">
                      <h4 class="fs-6 fw-bold mb-1 text-truncate" style="font-size: 0.85rem !important;">{{ $notif['title'] }}</h4>
                      <p class="mb-1 text-muted small text-truncate" style="font-size: 0.78rem;">{{ $notif['sub'] }}</p>
                      <p class="mb-0 text-secondary" style="font-size: 0.72rem;"><i class="bi bi-clock me-1"></i>{{ $notif['time'] }}</p>
                    </div>
                  </a>
                </li>
              @endforeach
            @else
              <li>
                <hr class="dropdown-divider">
              </li>
              <li class="p-3 text-center text-muted small">
                Belum ada aktivitas pengajuan terbaru.
              </li>
            @endif

            <li>
              <hr class="dropdown-divider">
            </li>
            <li class="dropdown-footer">
              <a href="{{ route('prestasi-mandiri.index') }}">Lihat Semua Pengajuan</a>
            </li>

          </ul><!-- End Notification Dropdown Items -->

        </li><!-- End Notification Nav -->

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="{{ Auth::user()->avatar_url }}" alt="Profile" class="rounded-circle" style="width: 36px; height: 36px; object-fit: cover;">
            <span class="d-none d-md-block dropdown-toggle ps-2">{{ Auth::user()->name }}</span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6>{{ Auth::user()->name }}</h6>
              <span class="text-capitalize">{{ Auth::user()->role }}</span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.show') }}">
                <i class="bi bi-person"></i>
                <span>Profil Saya</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{ route('password.edit') }}">
                <i class="bi bi-key"></i>
                <span>Ubah Password</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

             <li>
              <a class="dropdown-item d-flex align-items-center" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
              </form>
            </li>

          </ul>
        </li>

      </ul>
    </nav>

  </header>