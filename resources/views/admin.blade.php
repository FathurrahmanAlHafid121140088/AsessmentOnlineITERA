<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="{{ asset('css/style-admin.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style-footer.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
</head>

<body>

    <div class="particles" id="particles"></div>

    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <div class="container">
        <div class="header">
            <div class="welcome-icon">
                <i class="fas fa-crown"></i>
            </div>
            <h1>Selamat Datang di Panel Admin</h1>
            <p class="subtitle">Assessment Online Psychology - Institut Teknologi Sumatera</p>
        </div>

        <div class="cards-container">
            <!-- Admin Mental Health Card -->
            <div class="admin-card mental-health" onclick="handleCardClick('mental-health')">
                <div class="card-icon-container">
                    <div class="icon-bg"></div>
                    <div class="card-icon">
                        <i class="fas fa-brain"></i>
                    </div>
                </div>
                <h2 class="card-title">Admin Mental Health</h2>
                <p class="card-description">
                    Kelola dan pantau assessment kesehatan mental, analisis hasil tes psikologi,
                    dan buat laporan komprehensif untuk mendukung kesejahteraan mental pengguna.
                </p>
                <a href="/admin/mental-health" class="card-button-link">
                    <button class="card-button">
                        <i class="fas fa-rocket"></i> Masuk Dashboard
                    </button>
                </a>


                <div class="stats-counter">
                    <div class="stat-item">
                        <span class="stat-number" id="mental-users"
                            data-target="{{ $totalUsers }}">{{ $totalUsers }}</span>
                        <span class="stat-label">Total Pengguna</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number" id="mental-assessments"
                            data-target="{{ $totalTes }}">{{ $totalTes }}</span>
                        <span class="stat-label">Total Assessment</span>
                    </div>
                </div>
            </div>

            <!-- Admin Career Interest Card -->
            <div class="admin-card career-interest" onclick="handleCardClick('career-interest')">
                <div class="card-icon-container">
                    <div class="icon-bg"></div>
                    <div class="card-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                </div>
                <h2 class="card-title">Admin Peminatan Karir</h2>
                <p class="card-description">
                    Administrasi tes minat bakat, evaluasi kesesuaian karir, dan berikan
                    rekomendasi jalur profesional yang tepat untuk setiap individu.
                </p>
                <button class="card-button">
                    <i class="fas fa-rocket"></i> Masuk Dashboard
                </button>

                <div class="stats-counter">
                    <div class="stat-item">
                        <span class="stat-number" id="career-assessments">0</span>
                        <span class="stat-label">Assessment Aktif</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number" id="career-users">0</span>
                        <span class="stat-label">Pengguna</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-footer></x-footer>
</body>

<script src="{{ asset('js/script-admin.js') }}"></script>
<script src="{{ asset('js/script-footer.js') }}"></script>

</html>
