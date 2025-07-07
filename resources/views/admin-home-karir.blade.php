<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Admin Dashboard - Karir" />
    <meta name="author" content="" />
    <title>Admin Dashboard - Karir</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
    <!-- AOS Library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/@srexi/purecounterjs@1.5.0/dist/purecounter_vanilla.js"></script>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            --secondary-gradient: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
            --success-gradient: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            --danger-gradient: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
            --dark-gradient: linear-gradient(135deg, #343a40 0%, #6c757d 100%);
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --hover-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(108,117,125,0.08)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            z-index: -1;
            opacity: 0.5;
        }

        a {
            text-decoration: none !important;
        }

        /* Navbar Styles */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.08);
            border-bottom: 1px solid rgba(108, 117, 125, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-link {
            color: #333 !important;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            padding: 0.5rem 1rem !important;
        }

        .nav-link:hover {
            color: #495057 !important;
            transform: translateY(-2px);
        }

        .nav-link.active {
            color: #495057 !important;
            font-weight: 600;
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 1rem;
            right: 1rem;
            height: 2px;
            background: var(--primary-gradient);
            border-radius: 2px;
        }

        .dropdown-menu {
            border: none;
            box-shadow: var(--card-shadow);
            border-radius: 15px;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }

        .dropdown-item {
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background: rgba(108, 117, 125, 0.1);
            color: #495057;
            transform: translateX(5px);
        }

        /* Main Content Styles */
        .main-content {
            padding: 2rem 0;
            min-height: calc(100vh - 70px);
        }

        .content-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(108, 117, 125, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .content-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
        }

        .card-header {
            background: var(--primary-gradient);
            color: white;
            padding: 1.5rem;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="40" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="40" cy="80" r="1.5" fill="rgba(255,255,255,0.1)"/></svg>');
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .card-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1.3rem;
            position: relative;
            z-index: 1;
        }

        .card-header .subtitle {
            margin: 0;
            opacity: 0.9;
            font-size: 0.9rem;
            position: relative;
            z-index: 1;
        }

        .card-body {
            padding: 2rem;
        }

        /* Stats Cards */
        .stats-row {
            margin-bottom: 2rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            border: 1px solid rgba(108, 117, 125, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--primary-gradient);
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--card-shadow);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Table Styles */
        .table-container {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.06);
        }

        .table {
            margin: 0;
            background: transparent;
        }

        .table thead th {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .table tbody tr {
            background: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background: rgba(108, 117, 125, 0.08);
            transform: scale(1.01);
        }

        .table tbody td {
            padding: 1rem;
            border: none;
            vertical-align: middle;
            font-weight: 500;
        }

        .table tbody tr:not(:last-child) td {
            border-bottom: 1px solid rgba(108, 117, 125, 0.1);
        }

        /* Action Buttons */
        .action-btn {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 2px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .action-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: scale(0);
            transition: transform 0.3s ease;
        }

        .action-btn:hover::before {
            transform: scale(1);
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }

        .btn-view {
            background: var(--primary-gradient);
            color: white;
        }

        .btn-download {
            background: var(--success-gradient);
            color: white;
        }

        .btn-delete {
            background: var(--danger-gradient);
            color: white;
        }

        /* Badge Styles */
        .badge-custom {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-male {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
        }

        .badge-female {
            background: linear-gradient(135deg, #e83e8c 0%, #fd7e14 100%);
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .table-responsive {
                border-radius: 15px;
                box-shadow: var(--card-shadow);
            }

            .action-btn {
                width: 30px;
                height: 30px;
                margin: 0 1px;
            }

            .stat-number {
                font-size: 1.5rem;
            }
        }

        /* Loading Animation */
        .loading-dots {
            display: inline-block;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {

            0%,
            60%,
            100% {
                opacity: 0;
            }

            30% {
                opacity: 1;
            }
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-gradient);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--secondary-gradient);
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-graduation-cap me-2"></i>Pusat Data Tes RMIB
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/admin">
                            <i class="fas fa-home me-1"></i>Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/mental-health">
                            <i class="fas fa-brain me-1"></i>Mental Health
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">
                            <i class="fas fa-book me-1"></i>Peminatan Karir
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>Admin
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#"><i
                                        class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content" style="margin-top: 70px;">
        <div class="container">
            <!-- Stats Cards -->
            <div class="row stats-row">
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="stat-card">
                        <div class="stat-number">142</div>
                        <div class="stat-label">Total Mahasiswa</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="stat-card">
                        <div class="stat-number">23</div>
                        <div class="stat-label">Tes Hari Ini</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="stat-card">
                        <div class="stat-number">89</div>
                        <div class="stat-label">Tes Minggu Ini</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="stat-card">
                        <div class="stat-number">267</div>
                        <div class="stat-label">Total Tes</div>
                    </div>
                </div>
            </div>

            <!-- Main Content Card -->
            <div class="content-card">
                <div class="card-header">
                    <h5><i class="fas fa-clock me-2"></i>Aktivitas Terbaru</h5>
                    <p class="subtitle mb-0">Daftar submission terbaru dari mahasiswa</p>
                </div>
                <div class="card-body">
                    <div class="table-container">
                        <div class="table-responsive">
                            <table class="table align-middle text-center">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIM</th>
                                        <th>Nama</th>
                                        <th>Program Studi</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Usia</th>
                                        <th>Jenis Tes</th>
                                        <th>Tanggal Submit</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>1</strong></td>
                                        <td><code>1234567</code></td>
                                        <td><strong>Valdi</strong></td>
                                        <td>Rekayasa Kosmetik</td>
                                        <td><span class="badge badge-custom badge-female">P</span></td>
                                        <td>12</td>
                                        <td><span class="badge bg-info text-white">Mental Health</span></td>
                                        <td>
                                            <small class="text-muted">Wednesday, 02 Jul 2025</small><br>
                                            <small class="text-primary">13:01</small>
                                        </td>
                                        <td>
                                            <a href="admin-karir">
                                                <button class="action-btn btn-view" title="View Details">
                                                    <i class="fa-solid fa-book-open"></i>
                                                </button>
                                            </a>
                                            <button class="action-btn btn-download" title="Download Report">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            <button class="action-btn btn-delete" title="Delete Record">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>2</strong></td>
                                        <td><code>123456</code></td>
                                        <td><strong>Abdul</strong></td>
                                        <td>Pariwisata</td>
                                        <td><span class="badge badge-custom badge-male">L</span></td>
                                        <td>11</td>
                                        <td><span class="badge bg-info text-white">Mental Health</span></td>
                                        <td>
                                            <small class="text-muted">Tuesday, 01 Jul 2025</small><br>
                                            <small class="text-primary">12:18</small>
                                        </td>
                                        <td>
                                            <a href="admin-karir">
                                                <button class="action-btn btn-view" title="View Details">
                                                    <i class="fa-solid fa-book-open"></i>
                                                </button>
                                            </a>
                                            <button class="action-btn btn-download" title="Download Report">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            <button class="action-btn btn-delete" title="Delete Record">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>3</strong></td>
                                        <td><code>789012</code></td>
                                        <td><strong>Sari</strong></td>
                                        <td>Teknik Informatika</td>
                                        <td><span class="badge badge-custom badge-female">P</span></td>
                                        <td>20</td>
                                        <td><span class="badge bg-success text-white">Career Test</span></td>
                                        <td>
                                            <small class="text-muted">Monday, 30 Jun 2025</small><br>
                                            <small class="text-primary">09:45</small>
                                        </td>
                                        <td>
                                            <a href="admin-karir">
                                                <button class="action-btn btn-view" title="View Details">
                                                    <i class="fa-solid fa-book-open"></i>
                                                </button>
                                            </a>
                                            <button class="action-btn btn-download" title="Download Report">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            <button class="action-btn btn-delete" title="Delete Record">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>4</strong></td>
                                        <td><code>456789</code></td>
                                        <td><strong>Budi</strong></td>
                                        <td>Manajemen</td>
                                        <td><span class="badge badge-custom badge-male">L</span></td>
                                        <td>19</td>
                                        <td><span class="badge bg-warning text-dark">Personality</span></td>
                                        <td>
                                            <small class="text-muted">Sunday, 29 Jun 2025</small><br>
                                            <small class="text-primary">16:30</small>
                                        </td>
                                        <td>
                                            <a href="admin-karir">
                                                <button class="action-btn btn-view" title="View Details">
                                                    <i class="fa-solid fa-book-open"></i>
                                                </button>
                                            </a>
                                            <button class="action-btn btn-download" title="Download Report">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            <button class="action-btn btn-delete" title="Delete Record">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>5</strong></td>
                                        <td><code>234567</code></td>
                                        <td><strong>Maya</strong></td>
                                        <td>Psikologi</td>
                                        <td><span class="badge badge-custom badge-female">P</span></td>
                                        <td>21</td>
                                        <td><span class="badge bg-info text-white">Mental Health</span></td>
                                        <td>
                                            <small class="text-muted">Saturday, 28 Jun 2025</small><br>
                                            <small class="text-primary">14:22</small>
                                        </td>
                                        <td>
                                            <a href="admin-karir">
                                                <button class="action-btn btn-view" title="View Details">
                                                    <i class="fa-solid fa-book-open"></i>
                                                </button>
                                            </a>
                                            <button class="action-btn btn-download" title="Download Report">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            <button class="action-btn btn-delete" title="Delete Record">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AOS Animation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });

        // Add smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Add hover effects to table rows
        document.querySelectorAll('tbody tr').forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(5px)';
            });
            row.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(0)';
            });
        });

        // Add click handlers for action buttons
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire(
                            'Dihapus!',
                            'Data berhasil dihapus.',
                            'success'
                        )
                    }
                })
            });
        });

        document.querySelectorAll('.btn-download').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Mengunduh laporan...',
                    text: 'Mohon tunggu sebentar',
                    icon: 'info',
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    Swal.fire('Berhasil!', 'Laporan berhasil diunduh.', 'success');
                });
            });
        });
    </script>
</body>

</html>
