<nav class="navbar navbar-expand-lg navbar-dark fixed-top navbar-transparent" id="mainNav">
    <div class="container px-3">
        <a class="navbar-brand" href="{{ url('/home') }}">
            <img src="{{ asset('assets/img/navbar-logo.svg') }}" alt="..." />
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive"
            aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            Menu <i class="fas fa-bars ms-1"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ms-auto py-4 py-lg-0 text-uppercase">
                <li class="nav-item"><a class="nav-link" href="/home">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="/mental-health">Mental Health</a></li>
                <li class="nav-item"><a class="nav-link" href="/karir-home">Peminatan Karir</a></li>
                <li class="nav-item">
                    <a class="nav-link text-success" href="{{ route('login') }}">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
