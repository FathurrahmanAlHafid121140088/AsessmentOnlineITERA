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
                <a href="{{ route('user.mental-health') }}"
                    class="menu-item {{ request()->routeIs('user.mental-health') ? 'active' : '' }}">
                    <i class="fas fa-brain"></i>
                    <span>Mental Health</span>
                </a>
                <a href="{{ route('karir.dashboard') }}"
                    class="menu-item {{ request()->routeIs('karir.dashboard') ? 'active' : '' }}">
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
                    <h3 class="chart-title"><i class="fas fa-chart-radar"></i> Profil Minat Peminatan Karir (RMIB)
                        Terbaru</h3>

                    @if (!empty($radarLabels) && !empty($radarData))
                        <!-- Keterangan Cara Membaca Chart -->
                        <div
                            style="background: #fff7ed; border-left: 4px solid #ea580c; padding: 12px 16px; margin-bottom: 20px; border-radius: 6px;">
                            <p style="margin: 0; font-size: 14px; color: #666;">
                                <i class="fas fa-info-circle" style="color: #ea580c;"></i>
                                <strong>Cara Membaca:</strong>
                                Area yang <strong>lebih besar/menonjol</strong> menunjukkan <strong>minat yang lebih
                                    tinggi</strong>.
                                Hover pada titik untuk melihat skor detail. <strong>Klik label kategori</strong> untuk
                                melihat penjelasan lengkap.
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

                <!-- Modal Penjelasan Kategori RMIB -->
                <div id="categoryModal" class="category-modal">
                    <div class="category-modal-content">
                        <span class="category-close">&times;</span>
                        <div id="categoryModalBody">
                            <!-- Konten akan diisi via JavaScript -->
                        </div>
                    </div>
                </div>

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
                                        <td>
                                            <a href="{{ route('karir.hasil', $tes['id']) }}" class="diagnose-btn">
                                                <i class="fa-solid fa-eye"></i> Lihat Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" style="text-align: center;">Anda belum pernah mengikuti
                                            tes RMIB.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <!-- Button Mulai Tes -->
                        <div class="button-container">
                            <a href="{{ route('karir.datadiri.form') }}" class="start-test-btn">
                                <i class="fas fa-play-circle"></i> Mulai Tes
                            </a>
                        </div>
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
                                    return 'Skor RMIB: ' + originalScore +
                                        ' (Semakin kecil = Semakin diminati)';
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

    // ===================================
    // MODAL KATEGORI RMIB
    // ===================================
    const categoryData = {
        'OUT': {
            name: 'Outdoor',
            icon: 'fas fa-tree',
            description: 'Minat terhadap pekerjaan yang berhubungan dengan alam terbuka, pertanian, perkebunan, atau kehidupan di luar ruangan.',
            characteristics: [
                'Menyukai aktivitas di luar ruangan',
                'Tertarik pada pertanian, peternakan, dan kehutanan',
                'Senang bekerja dengan tanaman dan hewan',
                'Memiliki kepedulian terhadap lingkungan alam'
            ],
            careers: 'Petani, Peternak, Ahli Kehutanan, Arsitek Landscape, Ahli Lingkungan, Park Ranger'
        },
        'MECH': {
            name: 'Mechanical',
            icon: 'fas fa-cog',
            description: 'Minat terhadap pekerjaan yang melibatkan mesin, alat-alat mekanik, dan peralatan teknis.',
            characteristics: [
                'Tertarik pada cara kerja mesin',
                'Senang memperbaiki dan merakit barang',
                'Memiliki kemampuan teknis yang baik',
                'Menyukai pekerjaan hands-on dengan alat'
            ],
            careers: 'Teknisi Mesin, Montir, Insinyur Mekanik, Operator Alat Berat, Ahli Robotika'
        },
        'COMP': {
            name: 'Computational',
            icon: 'fas fa-calculator',
            description: 'Minat terhadap pekerjaan yang melibatkan angka, perhitungan, dan analisis data numerik.',
            characteristics: [
                'Tertarik pada matematika dan statistik',
                'Senang bekerja dengan angka dan data',
                'Teliti dalam perhitungan',
                'Mampu menganalisis data kuantitatif'
            ],
            careers: 'Akuntan, Aktuaris, Analis Keuangan, Data Analyst, Statistician, Auditor'
        },
        'SCI': {
            name: 'Scientific',
            icon: 'fas fa-flask',
            description: 'Minat terhadap pekerjaan yang berhubungan dengan sains, penelitian, dan eksperimen ilmiah.',
            characteristics: [
                'Ingin tahu dan selalu bertanya',
                'Senang melakukan penelitian',
                'Tertarik pada fenomena ilmiah',
                'Memiliki pemikiran analitis dan sistematis'
            ],
            careers: 'Ilmuwan, Peneliti, Ahli Biologi, Kimia, Fisikawan, Dokter Penelitian'
        },
        'PERS': {
            name: 'Personal Contact',
            icon: 'fas fa-handshake',
            description: 'Minat terhadap pekerjaan yang melibatkan interaksi langsung dengan orang lain, penjualan, atau pelayanan.',
            characteristics: [
                'Senang bertemu dan berbicara dengan orang',
                'Memiliki kemampuan komunikasi yang baik',
                'Persuasif dan ramah',
                'Tertarik membangun relasi'
            ],
            careers: 'Sales, Marketing, Customer Service, Public Relations, Event Organizer'
        },
        'AETH': {
            name: 'Aesthetic',
            icon: 'fas fa-palette',
            description: 'Minat terhadap pekerjaan yang berhubungan dengan seni, desain, dan keindahan visual.',
            characteristics: [
                'Memiliki apresiasi terhadap keindahan',
                'Kreatif dalam visual dan desain',
                'Senang mengekspresikan diri melalui seni',
                'Peka terhadap warna, bentuk, dan komposisi'
            ],
            careers: 'Desainer Grafis, Ilustrator, Fotografer, Fashion Designer, Interior Designer'
        },
        'LIT': {
            name: 'Literary',
            icon: 'fas fa-book',
            description: 'Minat terhadap pekerjaan yang melibatkan menulis, membaca, dan penggunaan bahasa.',
            characteristics: [
                'Senang membaca dan menulis',
                'Memiliki kemampuan verbal yang kuat',
                'Kreatif dalam penggunaan kata',
                'Tertarik pada sastra dan bahasa'
            ],
            careers: 'Penulis, Jurnalis, Editor, Copywriter, Content Creator, Pustakawan'
        },
        'MUS': {
            name: 'Musical',
            icon: 'fas fa-music',
            description: 'Minat terhadap pekerjaan yang berhubungan dengan musik, suara, dan harmoni.',
            characteristics: [
                'Memiliki kepekaan terhadap musik',
                'Senang mendengarkan dan memainkan musik',
                'Tertarik pada ritme dan melodi',
                'Kreatif dalam komposisi musik'
            ],
            careers: 'Musisi, Penyanyi, Komposer, Produser Musik, Guru Musik, DJ'
        },
        'S.S': {
            name: 'Social Service',
            icon: 'fas fa-hands-helping',
            description: 'Minat terhadap pekerjaan yang melibatkan membantu orang lain dan pelayanan sosial.',
            characteristics: [
                'Empati terhadap orang lain',
                'Ingin membantu menyelesaikan masalah sosial',
                'Peduli terhadap kesejahteraan masyarakat',
                'Senang bekerja untuk kepentingan umum'
            ],
            careers: 'Pekerja Sosial, Konselor, Psikolog, Guru, Aktivis Sosial, NGO Worker'
        },
        'CLER': {
            name: 'Clerical',
            icon: 'fas fa-file-alt',
            description: 'Minat terhadap pekerjaan administratif, organisasi dokumen, dan tugas-tugas kantor.',
            characteristics: [
                'Terorganisir dan rapi',
                'Teliti dalam detail',
                'Senang bekerja dengan sistem dan prosedur',
                'Efisien dalam administrasi'
            ],
            careers: 'Sekretaris, Staff Administrasi, Office Manager, Data Entry, Resepsionis'
        },
        'PRAC': {
            name: 'Practical',
            icon: 'fas fa-wrench',
            description: 'Minat terhadap pekerjaan praktis yang menghasilkan sesuatu yang berguna dan konkret.',
            characteristics: [
                'Senang membuat dan membangun sesuatu',
                'Praktis dalam memecahkan masalah',
                'Terampil dalam pekerjaan tangan',
                'Fokus pada hasil yang nyata'
            ],
            careers: 'Tukang Kayu, Tukang Listrik, Tukang Batu, Craftsman, Builder'
        },
        'MED': {
            name: 'Medical',
            icon: 'fas fa-heartbeat',
            description: 'Tertarik pada ilmu kedokteran dan kesehatan. Minat terhadap pekerjaan yang berhubungan dengan kesehatan, perawatan, dan pengobatan.',
            characteristics: [
                'Peduli terhadap kesehatan orang lain',
                'Tertarik pada ilmu kedokteran',
                'Senang merawat dan membantu yang sakit',
                'Memiliki empati dan kesabaran'
            ],
            careers: 'Dokter, Perawat, Apoteker, Fisioterapis, Ahli Gizi, Paramedis'
        }
    };

    // Fungsi untuk mendapatkan HTML konten modal
    function getCategoryHTML(categoryCode) {
        const data = categoryData[categoryCode];
        if (!data) return '<p>Data kategori tidak tersedia.</p>';

        return `
            <h3><i class="${data.icon}"></i> ${data.name} <span class="category-badge">${categoryCode}</span></h3>
            <p>${data.description}</p>

            <h4><i class="fas fa-star"></i> Karakteristik</h4>
            <ul>
                ${data.characteristics.map(char => `<li><i class="fas fa-check-circle"></i> ${char}</li>`).join('')}
            </ul>
        `;
    }

    // Modal elements
    const modal = document.getElementById('categoryModal');
    const modalBody = document.getElementById('categoryModalBody');
    const closeBtn = document.querySelector('.category-close');

    // Fungsi untuk menampilkan modal
    function showCategoryModal(categoryCode) {
        modalBody.innerHTML = getCategoryHTML(categoryCode);
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden'; // Prevent scrolling
    }

    // Fungsi untuk menutup modal
    function closeCategoryModal() {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto'; // Re-enable scrolling
    }

    // Event listener untuk tombol close
    if (closeBtn) {
        closeBtn.addEventListener('click', closeCategoryModal);
    }

    // Event listener untuk klik di luar modal
    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeCategoryModal();
        }
    });

    // Event listener untuk ESC key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.style.display === 'block') {
            closeCategoryModal();
        }
    });

    // Tambahkan click event pada canvas untuk detect klik pada label
    const radarCanvas = document.getElementById('rmibRadarChart');
    if (radarCanvas) {
        radarCanvas.style.cursor = 'pointer';
        radarCanvas.addEventListener('click', function(evt) {
            const chart = Chart.getChart(radarCanvas);
            if (!chart) return;

            const points = chart.getElementsAtEventForMode(evt, 'nearest', {
                intersect: true
            }, true);

            // Jika tidak ada point yang diklik, cek apakah klik pada area label
            const labels = @json($radarLabels ?? []);
            const rect = radarCanvas.getBoundingClientRect();
            const x = evt.clientX - rect.left;
            const y = evt.clientY - rect.top;

            // Deteksi klik pada label menggunakan posisi relatif
            // Karena Chart.js tidak memiliki API langsung untuk detect label click,
            // kita gunakan pendekatan sederhana: jika user klik di dekat tepi canvas
            const chartArea = chart.chartArea;
            const centerX = (chartArea.left + chartArea.right) / 2;
            const centerY = (chartArea.top + chartArea.bottom) / 2;
            const radius = Math.min(chartArea.right - chartArea.left, chartArea.bottom - chartArea.top) / 2;

            // Hitung jarak dari center
            const distance = Math.sqrt(Math.pow(x - centerX, 2) + Math.pow(y - centerY, 2));

            // Jika klik di luar radar area (di mana label berada)
            if (distance > radius * 0.85) {
                // Hitung angle untuk menentukan label mana yang diklik
                const angle = Math.atan2(y - centerY, x - centerX);
                const labelCount = labels.length;
                const anglePerLabel = (Math.PI * 2) / labelCount;

                // Normalize angle (start from top, clockwise)
                let normalizedAngle = angle + Math.PI / 2;
                if (normalizedAngle < 0) normalizedAngle += Math.PI * 2;

                const labelIndex = Math.round(normalizedAngle / anglePerLabel) % labelCount;
                const categoryCode = labels[labelIndex];

                showCategoryModal(categoryCode);
            }
        });
    }
</script>

</html>
