<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Data Program Studi - Peminatan Karir</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.ico') }}" />
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
    <!-- AOS Library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">

    <!-- Independent CSS for Admin Karir Program Studi Page -->
    <link rel="stylesheet" href="{{ asset('css/admin-karir-program-studi.css') }}">

    <!-- Footer CSS -->
    <link rel="stylesheet" href="{{ asset('css/style-footer.css') }}">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
    <header>
        <div class="hamburger" id="hamburger">
            <i class="fas fa-bars"></i>
        </div>
        <div class="header-title" style="color: white">
            <h2>Data Program Studi - Peminatan Karir</h2>
        </div>
        <div class="user-wrapper">
            <div class="account-group">
                <button class="account-toggle">
                    <i class="fas fa-user-circle"></i>
                    <span class="account-username">{{ Auth::guard('admin')->user()->username }}</span>
                    <i class="fas fa-caret-down caret"></i>
                </button>

                <div class="account-dropdown">
                    <!-- Username masuk dropdown saat mobile -->
                    <div class="dropdown-item account-name">
                        <i class="fas fa-user"></i>
                        {{ Auth::guard('admin')->user()->username }}
                    </div>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item logout">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </header>

    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('assets/img/Logo_ITERA.png') }}" class="img-fluid animated" alt="">
                <h2>ANALOGY</h2>
                <h4>PPSDM ITERA</h4>
            </div>
            <ul class="menu">
                <li>
                    <a href="{{ url('/admin') }}">
                        <i class="fas fa-home" style="margin-right: 1rem;"></i> Home
                    </a>
                </li>

                <!-- Dropdown Mental Health - hide di halaman peminatan karir -->
                @php
                    $isKarirPage =
                        request()->is('admin/peminatan-karir*') ||
                        request()->is('admin/admin-karir*') ||
                        request()->is('admin/karir*');
                @endphp

                @if (!$isKarirPage)
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle">
                            <i class="fas fa-brain" style="margin-right: 1rem;"></i> Mental Health
                            <i class="fas fa-chevron-down arrow"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="{{ route('admin.mental-health') }}">
                                    <i class="fas fa-tachometer-alt" style="margin-right: 0.8rem;"></i> Dashboard
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/admin/mental-health/provinsi') }}">
                                    <i class="fas fa-map-marker-alt" style="margin-right: 0.8rem;"></i> Data Provinsi
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/admin/mental-health/program-studi') }}">
                                    <i class="fas fa-university" style="margin-right: 0.8rem;"></i> Data Program Studi
                                </a>
                            </li>
                        </ul>
                    </li>
                @else
                    <li>
                        <a href="{{ route('admin.mental-health') }}">
                            <i class="fas fa-brain" style="margin-right: 1rem;"></i> Mental Health
                        </a>
                    </li>
                @endif

                <!-- Dropdown Peminatan Karir -->
                <li class="dropdown active">
                    <a href="#" class="dropdown-toggle">
                        <i class="fas fa-briefcase" style="margin-right: 1rem;"></i> Peminatan Karir
                        <i class="fas fa-chevron-down arrow"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="{{ route('admin.karir.index') }}">
                                <i class="fas fa-tachometer-alt" style="margin-right: 0.8rem;"></i> Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/admin/peminatan-karir/provinsi') }}">
                                <i class="fas fa-map-marker-alt" style="margin-right: 0.8rem;"></i> Data Provinsi
                            </a>
                        </li>
                        <li class="active">
                            <a href="{{ url('/admin/peminatan-karir/program-studi') }}">
                                <i class="fas fa-university" style="margin-right: 0.8rem;"></i> Data Program Studi
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <div class="cards" style="margin-bottom: 2rem;">
                    <div class="card bg-primary text-white">
                        <div class="card-icon bg-white text-primary">
                            <i class="fas fa-graduation-cap" style="color: #4361ee"></i>
                        </div>
                        <div class="card-info">
                            <h3>Total Program Studi</h3>
                            <h2 class="score-value">{{ count($prodiData) }}</h2>
                        </div>
                    </div>

                    <div class="card bg-success text-white">
                        <div class="card-icon bg-white text-success">
                            <i class="fas fa-users" style="color: #22c55e"></i>
                        </div>
                        <div class="card-info">
                            <h3>Total Peserta</h3>
                            <h2 class="score-value">{{ $totalUsers }}</h2>
                        </div>
                    </div>

                    <div class="card text-white" style="background-color: rgb(238, 58, 58);">
                        <div class="card-icon bg-white">
                            <i class="fas fa-building" style="color: rgb(238, 58, 58)"></i>
                        </div>
                        <div class="card-info">
                            <h3>FS</h3>
                            <h2 class="score-value">{{ $fakultasStats['FS']['jumlah'] }}
                                ({{ $fakultasStats['FS']['persentase'] }}%)</h2>
                        </div>
                    </div>

                    <div class="card bg-pink text-white">
                        <div class="card-icon bg-white text-danger">
                            <i class="fas fa-building" style="color: #e83e8c"></i>
                        </div>
                        <div class="card-info">
                            <h3>FTIK</h3>
                            <h2 class="score-value">{{ $fakultasStats['FTIK']['jumlah'] }}
                                ({{ $fakultasStats['FTIK']['persentase'] }}%)</h2>
                        </div>
                    </div>

                    <div class="card bg-warning text-white">
                        <div class="card-icon bg-white text-warning">
                            <i class="fas fa-building" style="color: #f59e0b"></i>
                        </div>
                        <div class="card-info">
                            <h3>FTI</h3>
                            <h2 class="score-value">{{ $fakultasStats['FTI']['jumlah'] }}
                                ({{ $fakultasStats['FTI']['persentase'] }}%)</h2>
                        </div>
                    </div>
                </div>

                <div class="tables">
                    <div class="table-header">
                        <h2>
                            Data Program Studi Peserta Tes RMIB
                        </h2>
                        <div class="table-controls">
                            <div class="left-controls">
                                <form method="GET" class="limit-form">
                                    <label for="fakultasFilter" class="limit-label">Filter Fakultas:</label>
                                    <select name="fakultas" id="fakultasFilter" onchange="filterByFakultas()">
                                        <option value="">Semua Fakultas</option>
                                        <option value="FS">FS</option>
                                        <option value="FTIK">FTIK</option>
                                        <option value="FTI">FTI</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="table">
                        <table id="prodiTable">
                            <thead>
                                <tr>
                                    <th style="width: 8%;">No</th>
                                    <th style="width: 12%;">Fakultas</th>
                                    <th style="width: 35%;">Program Studi</th>
                                    <th style="width: 20%;">Jumlah Peserta</th>
                                    <th style="width: 25%;">Persentase</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($prodiData as $index => $data)
                                    <tr data-fakultas="{{ $data['fakultas'] }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <span class="fakultas-badge fakultas-{{ strtolower($data['fakultas']) }}">
                                                {{ $data['fakultas'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <i class="fas fa-graduation-cap"
                                                style="color: #6366f1; margin-right: 0.5rem;"></i>
                                            {{ $data['nama'] }}
                                        </td>
                                        <td style="text-align: center;">
                                            <span class="badge bg-info">{{ $data['jumlah'] }} Peserta</span>
                                        </td>
                                        <td style="text-align: center;">
                                            <div
                                                style="display: flex; align-items: center; gap: 0.5rem; justify-content: center;">
                                                <div
                                                    style="flex: 0 0 60%; background: #e5e7eb; border-radius: 0.5rem; height: 1.5rem; overflow: hidden;">
                                                    <div
                                                        style="background: linear-gradient(90deg, #6366f1, #8b5cf6); height: 100%; width: {{ $data['persentase'] }}%; transition: width 0.3s;">
                                                    </div>
                                                </div>
                                                <span
                                                    style="font-weight: bold; color: #6366f1;">{{ $data['persentase'] }}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            Belum ada data program studi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<x-footer></x-footer>
<script src="{{ asset('js/script-admin-mh.js') }}"></script>
<script>
    function filterByFakultas() {
        const filter = document.getElementById('fakultasFilter').value;
        const rows = document.querySelectorAll('#prodiTable tbody tr');

        rows.forEach((row, index) => {
            const fakultas = row.getAttribute('data-fakultas');

            if (filter === '' || fakultas === filter) {
                row.style.display = '';
                // Update nomor urut yang terlihat
                const noCell = row.querySelector('td:first-child');
                if (noCell) {
                    let visibleIndex = 0;
                    for (let i = 0; i <= index; i++) {
                        const prevRow = rows[i];
                        const prevFakultas = prevRow.getAttribute('data-fakultas');
                        if (filter === '' || prevFakultas === filter) {
                            visibleIndex++;
                        }
                    }
                    noCell.textContent = visibleIndex;
                }
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</html>
