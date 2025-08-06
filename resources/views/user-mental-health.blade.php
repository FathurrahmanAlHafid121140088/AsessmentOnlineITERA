<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.ico') }}" />
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/style-user-mh.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style-footer.css') }}" rel="stylesheet">

</head>

<body>
    <div class="dashboard-container">
        <!-- 📦 Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <img src="../assets/img/Logo_ITERA.png" class="img-fluid animated" alt="">
                <h2> ANLOGY</h2>
                <h4>PPSDM ITERA</h4>
            </div>
            <div class="sidebar-menu">
                <a href="#" class="menu-item active">
                    <i class="fas fa-brain"></i>
                    <span>Mental Health</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-briefcase"></i>
                    <span>Peminatan Karir</span>
                </a>
            </div>
        </nav>

        <!-- 🔲 Overlay -->
        <div class="overlay" id="overlay"></div>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Navbar -->
            <nav class="navbar">
                <div class="navbar-header">
                    <button class="mobile-menu-toggle navbar-toggle" id="menuToggle" style="color: white">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="navbar-title">Dashboard Mental Health</h1>
                </div>

                <div class="user-info" tabindex="0">
                    <div class="user-avatar">
                        <i class="fas fa-user"></i> <!-- Ikon profil -->
                    </div>
                    <span class="user-name">Ahmad Rizki</span>
                    <i class="fas fa-caret-down caret" style="color: white"></i> <!-- Ikon dropdown -->
                    <div class="user-dropdown">
                        <!-- Info user (untuk mobile) -->
                        <div class="dropdown-user-details">
                            <strong><i class="fas fa-user-circle" style="color: white"></i> Ahmad Rizki</strong><br>
                        </div>
                        <hr>
                        <a href="/logout" class="dropdown-item logout">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Content -->
            <div class="content">
                <!-- Cards -->
                <div class="cards-grid">
                    <!-- Card 1: Total Tes Diikuti -->
                    <div class="card total-tests">
                        <div class="card-header">
                            <div>
                                <div class="card-title">Tes Diikuti</div>
                                <div class="card-value">12</div>
                                <div class="card-subtitle">Total semua tes</div>
                            </div>
                            <div class="card-icon">
                                <i class="fas fa-list-ul"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Tes Selesai -->
                    <div class="card completed-tests">
                        <div class="card-header">
                            <div>
                                <div class="card-title">Tes Selesai</div>
                                <div class="card-value">9</div>
                                <div class="card-subtitle">Sudah dikerjakan</div>
                            </div>
                            <div class="card-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3: Kategori Terakhir -->
                    <div class="card avg-score">
                        <div class="card-header">
                            <div>
                                <div class="card-title">Kategori Terakhir</div>
                                <div class="card-value">Sehat</div>
                                <div class="card-subtitle">Dari tes terakhir</div>
                            </div>
                            <div class="card-icon">
                                <i class="fas fa-heartbeat"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Table -->
                <div class="table-container">
                    <div class="table-header">
                        <i class="fas fa-history"></i>
                        <h3>Riwayat Tes</h3>
                    </div>
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jenis Tes</th>
                                    <th>Tanggal Tes</th>
                                    <th>Keluhan</th> <!-- Kolom baru -->
                                    <th>Lama Keluhan</th> <!-- Kolom baru -->
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td><i class="fas fa-heart text-red-500 mr-2"></i>Tes Depresi (PHQ-9)</td>
                                    <td>15 Juli 2024</td>
                                    <td>Sering merasa sedih</td>
                                    <td>6 bulan</td>
                                    <td><span class="status-badge status-completed">Selesai</span></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td><i class="fas fa-heart text-red-500 mr-2"></i>Tes Kecemasan (GAD-7)</td>
                                    <td>20 Juli 2024</td>
                                    <td>Gelisah berlebihan</td>
                                    <td>4 bulan</td>
                                    <td><span class="status-badge status-completed">Selesai</span></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td><i class="fas fa-briefcase text-blue-500 mr-2"></i>Tes Minat Karir Holland</td>
                                    <td>25 Juli 2024</td>
                                    <td>Tidak tahu minat kerja</td>
                                    <td>-</td>
                                    <td><span class="status-badge status-pending">Tertunda</span></td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td><i class="fas fa-heart text-red-500 mr-2"></i>Tes Stres (PSS-10)</td>
                                    <td>28 Juli 2024</td>
                                    <td>Mudah marah dan lelah</td>
                                    <td>2 bulan</td>
                                    <td><span class="status-badge status-completed">Selesai</span></td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td><i class="fas fa-briefcase text-blue-500 mr-2"></i>Tes Kepribadian Big Five
                                    </td>
                                    <td>02 Agustus 2024</td>
                                    <td>Ingin kenali diri</td>
                                    <td>-</td>
                                    <td><span class="status-badge status-completed">Selesai</span></td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td><i class="fas fa-heart text-red-500 mr-2"></i>Tes Burnout (MBI)</td>
                                    <td>05 Agustus 2024</td>
                                    <td>Lelah kerja terus-menerus</td>
                                    <td>8 bulan</td>
                                    <td><span class="status-badge status-pending">Tertunda</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </main>
    </div>
</body>
<x-footer></x-footer>
<script src="{{ asset('js/script-user-mh.js') }}"></script>

</html>
