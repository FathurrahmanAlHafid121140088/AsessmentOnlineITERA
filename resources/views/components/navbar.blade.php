<nav class="navbar navbar-expand-lg navbar-dark fixed-top navbar-transparent" id="mainNav">
    <div class="container px-3">
        <div class="logo-header">
            <img src="{{ asset('assets/img/Logo_ITERA.png') }}" alt="Logo">
            <div class="logo-text">
                <h2>ANALOGY</h2>
                <h4>PPSDM ITERA</h4>
            </div>
        </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive"
            aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            Menu <i class="fas fa-bars ms-1"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ms-auto py-4 py-lg-0 text-uppercase">
                <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="/mental-health">Mental Health</a></li>
                <li class="nav-item"><a class="nav-link" href="/karir-home">Peminatan Karir</a></li>
                @auth
                    {{-- JIKA SUDAH LOGIN --}}
                    <li class="nav-item">
                        <div class="user-info" tabindex="0">
                            <div class="user-avatar">
                                {{-- Mengambil huruf pertama dari email user dan menjadikannya kapital --}}
                                {{ strtoupper(substr(Auth::user()->email, 0, 1)) }}
                            </div>
                            <i class="fas fa-caret-down user-caret"></i>

                            <div class="user-dropdown">
                                <div class="dropdown-user-details">
                                    <div class="user-username">
                                        <i class="fas fa-user-circle fa-fw"></i>
                                        <strong>{{ Auth::user()->name }}</strong><br>
                                    </div>
                                    <div class="user-email">
                                        <i class="fas fa-envelope fa-fw"></i>
                                        <span>{{ Auth::user()->email }}</span>
                                    </div>
                                </div>

                                <a href="{{ url('/user/mental-health') }}" class="dropdown-item">
                                    <i class="fas fa-tachometer-alt fa-fw"></i> Dashboard
                                </a>
                                <hr>

                                <!-- Tombol Logout -->
                                <a href="{{ route('logout') }}" class="dropdown-item logout"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt fa-fw"></i> Logout
                                </a>

                                <!-- ✅ Form Logout (Wajib Ada) -->
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>

                        </div>
                    </li>
                @else
                    {{-- BELUM LOGIN --}}
                    <li class="nav-item">
                        <a class="nav-link text-success" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
