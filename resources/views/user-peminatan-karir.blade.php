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
    <!-- CSS untuk Peminatan Karir -->
    <link href="{{ asset('css/style-user-karir.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style-footer.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
</head>

<body>
    <div class="dashboard-container">
        <!-- 📦 Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <img src="../assets/img/Logo_ITERA.png" class="img-fluid animated" alt="">
                <h2> ANALOGY</h2>
                <h4>PPSDM ITERA</h4>
            </div>
            <div class="sidebar-menu">
                <a href="{{ route('user.mental-health') }}" class="menu-item {{ request()->routeIs('user.mental-health') ? 'active' : '' }}">
                    <i class="fas fa-brain"></i>
                    <span>Mental Health</span>
                </a>
                <a href="{{ route('karir.dashboard') }}" class="menu-item {{ request()->routeIs('karir.dashboard') ? 'active' : '' }}">
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
                    <h1 class="navbar-title">Dashboard Peminatan Karir</h1>
                </div>

                <div class="user-info" tabindex="0">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->email, 0, 1)) }}
                    </div>

                    <span class="user-name">{{ Auth::user()->name }}</span>

                    <i class="fas fa-caret-down caret" style="color: white"></i>
                    <div class="user-dropdown">
                        <!-- Info user (untuk mobile) -->
                        <div class="dropdown-user-details">
                            <strong><i class="fas fa-user-circle" style="color: white"></i>
                                {{ Auth::user()->name }}</strong><br>
                            <small style="color: #ddd;">{{ Auth::user()->email }}</small>
                        </div>
                        <hr>

                        <!-- Form Logout -->
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>

                        <a href="{{ route('logout') }}" class="dropdown-item logout"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </div>
            </nav>

            @php
                $hour = now('Asia/Jakarta')->format('H');

                if ($hour >= 5 && $hour < 12) {
                    $greeting = 'Selamat Pagi';
                } elseif ($hour >= 12 && $hour < 15) {
                    $greeting = 'Selamat Siang';
                } elseif ($hour >= 15 && $hour < 18) {
                    $greeting = 'Selamat Sore';
                } else {
                    $greeting = 'Selamat Malam';
                }
            @endphp

            <div class="greeting-wrapper">
                <h2 class="greeting-text">
                    {{ $greeting }}, <span class="user-name">{{ Auth::user()->name }}</span> 👋
                </h2>
                <p class="greeting-subtitle">Mari temukan potensi karirmu 💼</p>
            </div>

            <!-- Content -->
            <div class="content">
                <!-- Cards -->
                <div class="cards-grid">
                    <!-- Card 1: Total Tes Diikuti -->
                    <div class="card total-tests">
                        <div class="card-header">
                            <div class="card-content">
                                <div class="card-title">Tes Diikuti</div>
                                <div class="card-value">{{ $jumlahTesDiikuti }}</div>
                                <div class="card-subtitle">Total semua tes</div>
                            </div>
                            <div class="card-icon-wrapper">
                                <div class="card-icon">
                                    <i class="fas fa-list-ul"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Tes Selesai -->
                    <div class="card completed-tests">
                        <div class="card-header">
                            <div class="card-content">
                                <div class="card-title">Tes Selesai</div>
                                <div class="card-value">{{ $jumlahTesSelesai }}</div>
                                <div class="card-subtitle">Sudah dikerjakan</div>
                            </div>
                            <div class="card-icon-wrapper">
                                <div class="card-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3: Kategori Minat Tertinggi -->
                    @php
                        $kategoriClass = 'avg-score';
                        $iconClass = 'fas fa-briefcase';

                        // Map kategori ke class CSS yang sesuai
                        $kategoriClassMap = [
                            'Outdoor' => 'minat-outdoor',
                            'Mechanical' => 'minat-mechanical',
                            'Computational' => 'minat-computational',
                            'Scientific' => 'minat-scientific',
                            'Personal Contact' => 'minat-personal-contact',
                            'Aesthetic' => 'minat-aesthetic',
                            'Literary' => 'minat-literary',
                            'Musical' => 'minat-musical',
                            'Social Service' => 'minat-social-service',
                            'Clerical' => 'minat-clerical',
                            'Practical' => 'minat-practical',
                            'Medical' => 'minat-medical',
                        ];

                        if (isset($kategoriClassMap[$kategoriTerakhir])) {
                            $kategoriClass .= ' ' . $kategoriClassMap[$kategoriTerakhir];
                        }

                        // Map kategori ke icon yang sesuai
                        $iconMap = [
                            'Outdoor' => 'fas fa-tree',
                            'Mechanical' => 'fas fa-cog',
                            'Computational' => 'fas fa-calculator',
                            'Scientific' => 'fas fa-flask',
                            'Personal Contact' => 'fas fa-handshake',
                            'Aesthetic' => 'fas fa-palette',
                            'Literary' => 'fas fa-book',
                            'Musical' => 'fas fa-music',
                            'Social Service' => 'fas fa-hands-helping',
                            'Clerical' => 'fas fa-file-alt',
                            'Practical' => 'fas fa-wrench',
                            'Medical' => 'fas fa-heartbeat',
                        ];

                        if (isset($iconMap[$kategoriTerakhir])) {
                            $iconClass = $iconMap[$kategoriTerakhir];
                        }
                    @endphp

                    <div class="card {{ $kategoriClass }}">
                        <div class="card-header">
                            <div class="card-content">
                                <div class="card-title">Minat Tertinggi</div>
                                <div class="card-value">{{ $kategoriTerakhir }}</div>
                                <div class="card-subtitle">Dari tes terakhir</div>
                            </div>
                            <div class="card-icon-wrapper">
                                <div class="card-icon">
                                    <i class="{{ $iconClass }}"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grafik Radar - Profil Minat RMIB -->
                <section class="chart-section">
                    <h3 class="chart-title"><i class="fas fa-chart-radar"></i> Profil Minat Peminatan Karir (RMIB)</h3>

                    @if (!empty($radarLabels) && !empty($radarData))
                        <!-- Keterangan Cara Membaca Chart -->
                        <div style="background: #fff7ed; border-left: 4px solid #ea580c; padding: 12px 16px; margin-bottom: 20px; border-radius: 6px;">
                            <p style="margin: 0; font-size: 14px; color: #666;">
                                <i class="fas fa-info-circle" style="color: #ea580c;"></i>
                                <strong>Cara Membaca:</strong>
                                Area yang <strong>lebih besar/menonjol</strong> menunjukkan <strong>minat yang lebih tinggi</strong>.
                                Hover pada titik untuk melihat skor detail.
                            </p>
                        </div>
                    @endif

                    <div class="chart-container">
                        @if (!empty($radarLabels) && !empty($radarData))
                            <canvas id="rmibRadarChart"></canvas>
                        @else
                            <p style="text-align: center; color: #888; padding-top: 50px;">Grafik akan muncul setelah
                                Anda menyelesaikan tes RMIB pertama.</p>
                        @endif
                    </div>
                </section>

                <!-- Table -->
                <div class="table-container">
                    <div class="table-header">
                        <i class="fas fa-history"></i>
                        <h3>Riwayat Tes RMIB</h3>
                    </div>
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Tes</th>
                                    <th>NIM</th>
                                    <th>Nama</th>
                                    <th>Prodi</th>
                                    <th>Top 1 Minat</th>
                                    <th>Top 2 Minat</th>
                                    <th>Top 3 Minat</th>
                                    <th>Skor Konsistensi</th>
                                    <th>Validitas</th>
                                    <th>Detail Hasil</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($riwayatTes as $tes)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ \Carbon\Carbon::parse($tes['created_at'])->timezone('Asia/Jakarta')->translatedFormat('d F Y H:i') }}
                                            WIB</td>
                                        <td>{{ $tes['nim'] }}</td>
                                        <td>{{ $tes['nama'] }}</td>
                                        <td>{{ $tes['program_studi'] ?? '-' }}</td>
                                        <td>{{ $tes['top_1'] ?? '-' }}</td>
                                        <td>{{ $tes['top_2'] ?? '-' }}</td>
                                        <td>{{ $tes['top_3'] ?? '-' }}</td>
                                        <td style="text-align: center">{{ $tes['skor_konsistensi'] ?? '-' }}</td>
                                        <td style="text-align: center">
                                            @php
                                                $validitas = $tes['validitas'] ?? 'Tidak Valid';
                                                $badgeClass = $validitas == 'Valid' ? 'range-valid' : 'range-invalid';
                                            @endphp
                                            <span class="range-badge {{ $badgeClass }}">
                                                {{ $validitas }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('karir.hasil', $tes['id']) }}" class="diagnose-btn">
                                                <i class="fa-solid fa-eye"></i> Lihat Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" style="text-align: center;">Anda belum pernah mengikuti
                                            tes RMIB.</td>
                                    </tr>
                                @endforelse
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
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const radarCanvas = document.getElementById("rmibRadarChart");

        if (!radarCanvas) {
            console.warn("⚠️ Canvas radar chart tidak ditemukan.");
            return;
        }

        // Lazy render grafik radar
        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting) {
                renderRadarChart();
                observer.disconnect();
            }
        });

        observer.observe(radarCanvas);

        function renderRadarChart() {
            const labels = @json($radarLabels ?? []);
            const originalData = @json($radarData ?? []); // Data asli (skor RMIB)

            if (!labels.length || !originalData.length) {
                console.warn("⚠️ Data tidak cukup untuk menampilkan grafik radar.");
                return;
            }

            // REVERSE: Inverse data agar skor kecil (minat tinggi) tampil lebih tinggi di chart
            // Formula: inverted = (max + min) - original
            // Skor RMIB: Range 9-108 (9 cluster, peringkat 1-12)
            const maxScore = Math.max(...originalData);
            const minScore = Math.min(...originalData);
            const sumRange = maxScore + minScore;

            // Inverse data untuk visualisasi
            const invertedData = originalData.map(score => sumRange - score);

            new Chart(radarCanvas, {
                type: 'radar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Level Minat',
                        data: invertedData, // Menggunakan data yang sudah di-inverse
                        backgroundColor: 'rgba(234, 88, 12, 0.15)',
                        borderColor: '#ea580c',
                        borderWidth: 2,
                        pointBackgroundColor: '#ea580c',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#ea580c',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                font: {
                                    weight: 'bold',
                                    size: 14
                                },
                                color: '#333'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            padding: 10,
                            cornerRadius: 6,
                            callbacks: {
                                // Tampilkan skor asli di tooltip, bukan inverted
                                label: function(context) {
                                    const index = context.dataIndex;
                                    const originalScore = originalData[index];
                                    return 'Skor RMIB: ' + originalScore + ' (Semakin kecil = Semakin diminati)';
                                }
                            }
                        }
                    },
                    scales: {
                        r: {
                            beginAtZero: true,
                            min: 0,
                            max: Math.max(...invertedData) + 5,
                            ticks: {
                                display: false, // Sembunyikan angka di axis karena sudah di-inverse
                                stepSize: 10,
                                font: {
                                    size: 12,
                                    weight: 'bold'
                                },
                                color: '#666',
                                backdropColor: 'rgba(255, 255, 255, 0.9)'
                            },
                            pointLabels: {
                                font: {
                                    size: 13,
                                    weight: 'bold'
                                },
                                color: '#ea580c'
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            },
                            angleLines: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        }
                    },
                    animation: {
                        duration: 800,
                        easing: 'easeInOutQuart'
                    }
                }
            });
        }
    });
</script>

</html>
