<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>{{ $title }}</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.ico') }}" />
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
        <!-- AOS Library -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
        <link href="{{ asset('css/style-admin-home.css') }}" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    </head>
<body>
<header>
    <div class="hamburger" id="hamburger">
        <i class="fas fa-bars"></i>
    </div>
    <div class="header-title">
        <h2>Dashboard</h2>
    </div>
    <div class="user-wrapper">
        <div class="search-box" id="searchBox">
            <input type="text" placeholder="Cari Data..." id="searchInput">
            <button id="searchIcon" class="fas fa-search" onclick="toggleSearchInput()"></button>
        </div>

        @if(Auth::guard('admin')->check())
            <div class="user-info">
                <span>{{ Auth::guard('admin')->user()->username }}</span>
            </div>
            <div class="logout-button">
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" style="background:none;border:none;cursor:pointer;">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        @else
            <div class="login-button">
                <a href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> Login</a>
            </div>
        @endif
    </div>
</header>

        <div class="container">
            <!-- Sidebar -->
            <div class="sidebar" id="sidebar">
                <div class="logo">
                    <h2>Dashboard</h2>
                </div>
                <ul class="menu">
                    <li class="active">
                        <a href="#"><i class="fas fa-home"></i> Home</a>
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-brain"></i> Mental Health</a>
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-briefcase"></i> Peminatan Karir</a>
                    </li>
                </ul>
            </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <div class="cards">
                    <div class="card">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-info">
                            <h3>Total Users</h3>
                            <h2>1,250</h2>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-icon bg-success">
                            <i class="fas fa-brain"></i>
                        </div>
                        <div class="card-info">
                            <h3>Mental Health Tests</h3>
                            <h2>753</h2>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <div class="card-info">
                            <h3>Career Tests</h3>
                            <h2>498</h2>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-icon bg-danger">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="card-info">
                            <h3>Appointments</h3>
                            <h2>87</h2>
                        </div>
                    </div>
                </div>

                <div class="charts">
                    <div class="chart">
                        <div class="chart-header">
                            <h3>User Statistics</h3>
                            <div class="dropdown">
                                <button>Last 7 Days <i class="fas fa-chevron-down"></i></button>
                            </div>
                        </div>
                        <div class="chart-canvas">
                            <!-- Chart will be displayed here -->
                            <div class="placeholder-chart">
                                <div class="bar" style="height: 30%;"></div>
                                <div class="bar" style="height: 50%;"></div>
                                <div class="bar" style="height: 80%;"></div>
                                <div class="bar" style="height: 60%;"></div>
                                <div class="bar" style="height: 40%;"></div>
                                <div class="bar" style="height: 70%;"></div>
                                <div class="bar" style="height: 90%;"></div>
                            </div>
                            <div class="chart-labels">
                                <span>Sen</span>
                                <span>Sel</span>
                                <span>Rab</span>
                                <span>Kam</span>
                                <span>Jum</span>
                                <span>Sab</span>
                                <span>Min</span>
                            </div>
                        </div>
                    </div>
                    <div class="chart">
                        <div class="chart-header">
                            <h3>Test Distribution</h3>
                            <div class="dropdown">
                                <button>This Month <i class="fas fa-chevron-down"></i></button>
                            </div>
                        </div>
                        <div class="chart-canvas">
                            <div class="placeholder-pie">
                                <div class="pie-segment mental-health"></div>
                                <div class="pie-segment career"></div>
                            </div>
                            <div class="pie-legend">
                                <div><span class="mental-health-color"></span> Mental Health (60%)</div>
                                <div><span class="career-color"></span> Peminatan Karir (40%)</div>
                            </div>
                        </div>
                    </div>
                </div>

<div class="tables">
    <div class="table">
        <div class="table-header">
            <h3>Recent Activities</h3>
            <a href="{{ route('admin.home') }}">
                <button type="button">View All</button>
            </a>
        </div>
<table id="assessmentTable">
    <thead>
        <tr>
            <th>NIM</th>
            <th>Nama</th>
            <th>Program Studi</th>
            <th>Jenis Tes</th>
            <th>Kategori</th>
            <th>Tanggal Pengerjaan</th>
            <th style="text-align: center;">Aksi</th>         </tr>
    </thead>
    <tbody>
        @forelse($hasilKuesioners as $hasil)
            <tr>
                <td>{{ $hasil->nim }}</td>
                <td>{{ $hasil->dataDiri->nama ?? 'Tidak Ada Data' }}</td>
                <td>{{ $hasil->dataDiri->program_studi ?? 'Tidak Ada Data' }}</td>
                <td>Mental Health</td>
                <td>
                    @php
                        $kategori = $hasil->kategori ?? 'Belum Dikategorikan';
                        $statusClass = match($kategori) {
                            'Baik' => 'completed',
                            'Buruk' => 'warning',
                            'Sangat Baik' => 'in-progress',
                            'Sedang' => 'pending',
                            'Sangat Buruk' => 'cancelled',
                            default => 'unknown'
                        };
                    @endphp
                    <span class="status {{ $statusClass }}">{{ $kategori }}</span>
                </td>
                <td>{{ \Carbon\Carbon::parse($hasil->created_at)->format('d M Y') }}</td>

                <!-- Kolom Aksi -->
                <td>
                    <div class="action-buttons" style="display: flex; gap: 10px; align-items: center;">
                        <!-- Tombol Print PDF -->
                        <button type="button" class="print-button" onclick="printPDF(this)" dusk="print-button-1">                            <svg class="svgIcon" viewBox="0 0 384 512" height="1em" xmlns="http://www.w3.org/2000/svg">
                                <path d="M169.4 470.6c12.5 12.5 32.8 12.5 45.3 0l160-160c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L224 370.8 224 64c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 306.7L54.6 265.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l160 160z"/>
                            </svg>
                            <span class="icon2"></span>
                            <span class="tooltip">Print PDF</span>
                        </button>

                        <!-- Tombol Delete -->
                        <button type="button" class="delete-button" onclick="confirmDelete({{ $hasil->id }})" title="Hapus">
                            <svg viewBox="0 0 15 17.5" height="17.5" width="15" xmlns="http://www.w3.org/2000/svg" class="icon">
                                <path transform="translate(-2.5 -1.25)" d="M15,18.75H5A1.251,1.251,0,0,1,3.75,17.5V5H2.5V3.75h15V5H16.25V17.5A1.251,1.251,0,0,1,15,18.75ZM5,5V17.5H15V5Zm7.5,10H11.25V7.5H12.5V15ZM8.75,15H7.5V7.5H8.75V15ZM12.5,2.5h-5V1.25h5V2.5Z" id="Fill"></path>
                            </svg>
                        </button>

                        <!-- Form delete tersembunyi -->
                        <form action="{{ route('admin.delete', $hasil->id) }}" method="POST" dusk="delete-button-1" onsubmit="return confirm('Yakin ingin menghapus data ini?')" style="display:inline;">                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">Belum ada data hasil kuesioner.</td>
            </tr>
        @endforelse
    </tbody>
</table>
        <button class="btn-pdf" onclick="generatePDF()">
            <i class="fas fa-file-pdf"></i> Cetak PDF
        </button>
    </div>
</div>

            </div>
        </div>
    </div>
<script src="{{ asset('js/script-admin.js') }}"></script>
</body>
</html>