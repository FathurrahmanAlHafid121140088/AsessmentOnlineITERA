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
    <!-- Core theme CSS (includes Bootstrap)-->
    @vite(['resources/css/app-user-dashboard.css'])

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
                        {{-- Mengambil huruf pertama dari email user dan menjadikannya kapital --}}
                        {{ strtoupper(substr(Auth::user()->email, 0, 1)) }}
                    </div>

                    {{-- Tampilkan nama pengguna yang sedang login --}}
                    <span class="user-name">{{ Auth::user()->name }}</span>

                    <i class="fas fa-caret-down caret" style="color: white"></i> <!-- Ikon dropdown -->
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
                // Ambil waktu sekarang pakai WIB
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
                <p class="greeting-subtitle">Semoga harimu menyenangkan 💫</p>
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

                    <!-- Card 3: Kategori Terakhir -->
                    @php
                        $kategoriClass = '';
                        $iconClass = 'fas fa-heartbeat'; // default icon

                        switch ($kategoriTerakhir) {
                            case 'Perlu Dukungan Intensif':
                                $kategoriClass = 'range-intensif';
                                $iconClass = 'fas fa-frown';
                                break;
                            case 'Perlu Dukungan':
                                $kategoriClass = 'range-support';
                                $iconClass = 'fas fa-meh';
                                break;
                            case 'Cukup Sehat':
                                $kategoriClass = 'range-moderate';
                                $iconClass = 'fas fa-smile';
                                break;
                            case 'Sehat':
                                $kategoriClass = 'range-good';
                                $iconClass = 'fas fa-thumbs-up';
                                break;
                            case 'Sangat Sehat':
                                $kategoriClass = 'range-excellent';
                                $iconClass = 'fas fa-star';
                                break;
                        }
                    @endphp

                    <div class="card avg-score {{ $kategoriClass }}">
                        <div class="card-header">
                            <div class="card-content">
                                <div class="card-title">Kategori</div>
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


                <!-- Grafik Perkembangan (Desain Diperbarui) -->
                <section class="chart-section">
                    <h3 class="chart-title"><i class="fas fa-chart-line"></i> Grafik Skor Mental Health</h3>
                    <div class="chart-container">
                        @if (isset($chartLabels) && $chartLabels->isNotEmpty())
                            <canvas id="mentalHealthChart"></canvas>
                        @else
                            <p style="text-align: center; color: #888; padding-top: 50px;">Grafik akan muncul setelah
                                Anda menyelesaikan tes pertama.</p>
                        @endif
                    </div>
                </section>

                <div class="alert-box-blue">
                    <i class="fas fa-info-circle alert-icon-blue"></i>
                    <span class="alert-text">
                        “Tes berkala disarankan setiap 2 bulan.”
                    </span>
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
                                    <th>Tanggal Tes</th>
                                    <th>NIM</th>
                                    <th>Nama</th>
                                    <th>Prodi</th>
                                    <th>Riwayat Keluhan</th>
                                    <th>Lama Keluhan</th>
                                    <th>Kategori Mental Health</th>
                                    <th>Lihat Hasil Tes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($riwayatTes as $tes)
                                    <tr>
                                        <td>{{ ($riwayatTes->currentPage() - 1) * $riwayatTes->perPage() + $loop->iteration }}
                                        </td>
                                        {{-- Kolom Tanggal Tes ditambahkan di sini --}}
                                        <td>
                                            {{-- Pisahkan format tanggal dan waktu untuk menebalkan jam --}}
                                            {{ \Carbon\Carbon::parse($tes->created_at)->timezone('Asia/Jakarta')->translatedFormat('d F Y') }}
                                            <strong>{{ \Carbon\Carbon::parse($tes->created_at)->timezone('Asia/Jakarta')->translatedFormat('H:i') }}
                                                WIB</strong>
                                        </td>
                                        <td>{{ $tes->nim }}</td>
                                        <td>{{ $tes->nama }}</td>
                                        <td>{{ $tes->program_studi ?? '-' }}</td>
                                        <td>{{ $tes->keluhan ?? '-' }}</td>
                                        <td>{{ $tes->lama_keluhan ? $tes->lama_keluhan . ' Bulan' : '-' }}</td>
                                        <td style="text-align: center">
                                            @php
                                                $kategori = $tes->kategori_mental_health ?? 'N/A';
                                                $badgeClass = match ($kategori) {
                                                    'Perlu Dukungan Intensif' => 'range-intensif',
                                                    'Perlu Dukungan' => 'range-support',
                                                    'Cukup Sehat' => 'range-moderate',
                                                    'Sehat' => 'range-good',
                                                    'Sangat Sehat' => 'range-excellent',
                                                    default => 'range-moderate', // fallback
                                                };
                                            @endphp

                                            <span class="range-badge {{ $badgeClass }}">
                                                {{ $kategori }}
                                            </span>
                                        </td>
                                        <td>
                                            <button class="open-modal diagnose-btn"
                                                data-kategori="{{ $kategori }}">
                                                <i class="fa-solid fa-eye"></i> Interpretasi
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        {{-- Colspan disesuaikan menjadi 9 agar sesuai dengan jumlah kolom header --}}
                                        <td colspan="9" style="text-align: center;">Anda belum pernah mengikuti
                                            tes.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <!-- ✅ Paginasi Ditambahkan Di Sini -->
                        <div class="pagination">
                            {{ $riwayatTes->links('vendor.pagination.default') }}
                        </div>

                        <!-- 📊 Modal Diagnosa -->
                        <div id="diagnosaModal" class="modal">
                            <div class="modal-content">
                                <span class="close">&times;</span>
                                <div id="diagnosaContent"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Button Mulai Tes -->
                    <div class="button-container" style="text-align: left; margin-top: 20px;">
                        <a href="{{ route('mental-health.isi-data-diri') }}" class="start-test-btn">
                            <i class="fas fa-play-circle"></i> Mulai Tes
                        </a>
                    </div>
                </div>

            </div>
        </main>
    </div>
    <a id="wa-contact" href="https://wa.me/6285150876464" target="_blank" rel="noopener noreferrer"
        aria-label="Hubungi kami via WhatsApp">
        <span class="wa-icon" aria-hidden="true">
            <i class="fab fa-whatsapp"></i>
        </span>
        <span class="wa-label">Hubungi Kami</span>
    </a>
</body>
<x-footer></x-footer>
<script src="{{ asset('js/script-user-mh.js') }}"></script>
{{-- 3. Pindahkan Script ke bagian bawah body (tanpa @push) --}}
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const chartSection = document.getElementById("mentalHealthChart");

        // 🔥 Lazy render grafik (baru aktif saat terlihat di layar)
        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting) {
                renderChart();
                observer.disconnect();
            }
        });

        observer.observe(chartSection);

        function renderChart() {
            let labels = @json($chartLabels ?? []);
            const scores = @json($chartScores ?? []);

            if (!labels.length || !scores.length) {
                console.warn("⚠️ Data tidak cukup untuk menampilkan grafik.");
                return;
            }

            // 📱 Jika layar kecil (mobile), ubah label menjadi angka 1, 2, 3, ...
            if (window.innerWidth <= 768) {
                labels = labels.map((_, index) => index + 1);
            }

            new Chart(chartSection, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Skor',
                        data: scores,
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37,99,235,0.15)',
                        pointBackgroundColor: '#2563eb',
                        tension: 0.3,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: false,
                    plugins: {
                        legend: {
                            labels: {
                                font: {
                                    weight: 'bold',
                                    size: 14
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            min: 38,
                            max: 226,
                            title: {
                                display: true,
                                text: 'Total Skor',
                                font: {
                                    weight: 'bold',
                                    size: 14
                                }
                            },
                            ticks: {
                                stepSize: 38,
                                font: {
                                    weight: 'bold',
                                    size: 13
                                }
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Riwayat Tes',
                                font: {
                                    weight: 'bold',
                                    size: 14
                                }
                            },
                            ticks: {
                                font: {
                                    weight: 'bold',
                                    size: 13
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>


</html>
