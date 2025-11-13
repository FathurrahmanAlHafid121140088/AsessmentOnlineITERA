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
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
    <!-- AOS Library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">

    <!-- Core theme CSS (includes Bootstrap)-->
    @vite(['resources/css/app-admin-dashboard.css'])

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

    <style>
        /* Custom styling for detail button in modal */
        .btn-detail-jawaban {
            background-color: #8b5cf6 !important;
            color: white !important;
            padding: 10px 18px !important;
            border-radius: 6px !important;
            text-decoration: none !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px !important;
            font-size: 14px !important;
            font-weight: 500 !important;
            border: none !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 2px 4px rgba(139, 92, 246, 0.3) !important;
        }

        .btn-detail-jawaban:hover {
            background-color: #7c3aed !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 8px rgba(139, 92, 246, 0.4) !important;
            color: white !important;
        }

        .btn-detail-jawaban:active {
            transform: translateY(0) !important;
            box-shadow: 0 2px 4px rgba(139, 92, 246, 0.3) !important;
        }
    </style>

</head>

<body>
    <header>
        <div class="hamburger" id="hamburger">
            <i class="fas fa-bars"></i>
        </div>
        <div class="header-title" style="color: white">
            <h2>Dashboard</h2>
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
                <img src="../assets/img/Logo_ITERA.png" class="img-fluid animated" alt="">
                <h2>ANALOGY</h2>
                <h4>PPSDM ITERA</h4>
            </div>
            <ul class="menu">
                <li>
                    <a href="/admin">
                        <i class="fas fa-home" style="margin-right: 1rem;"></i> Home
                    </a>
                </li>

                <!-- Dropdown Mental Health -->
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">
                        <i class="fas fa-brain" style="margin-right: 1rem;"></i> Mental Health
                        <i class="fas fa-chevron-down arrow"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="/admin/mental-health">
                                <i class="fas fa-tachometer-alt" style="margin-right: 0.8rem;"></i> Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="/admin/mental-health/provinsi">
                                <i class="fas fa-map-marker-alt" style="margin-right: 0.8rem;"></i> Data Provinsi
                            </a>
                        </li>
                        <li>
                            <a href="/admin/mental-health/program-studi">
                                <i class="fas fa-university" style="margin-right: 0.8rem;"></i> Data Program Studi
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="/admin-home-karir">
                        <i class="fas fa-briefcase" style="margin-right: 1rem;"></i> Peminatan Karir
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <div class="cards">
                    <div class="card bg-danger text-white">
                        <div class="card-icon bg-white text-primary">
                            <i class="fas fa-users" style="color: #ef4444"></i>
                        </div>
                        <div class="card-info">
                            <h3>Total Pengguna</h3>
                            <h2 class="score-value">{{ $totalUsers ?? 0 }}</h2>
                        </div>
                    </div>

                    <div class="card bg-success text-white">
                        <div class="card-icon bg-white text-success">
                            <i class="fas fa-brain" style="color: #22c55e"></i>
                        </div>
                        <div class="card-info">
                            <h3>Jumlah Tes Masuk</h3>
                            <h2 class="score-value">{{ $totalTes ?? 0 }}</h2>
                        </div>
                    </div>

                    <div class="card bg-primary text-white">
                        <div class="card-icon bg-white text-primary">
                            <i class="fas fa-mars" style="color: #4361ee"></i>
                        </div>
                        <div class="card-info">
                            <h3>Laki-laki</h3>
                            <h2 class="score-value">{{ $totalLaki ?? 0 }}</h2>
                        </div>
                    </div>

                    <div class="card bg-pink text-white">
                        <div class="card-icon bg-white text-danger">
                            <i class="fas fa-venus" style="color: #e83e8c"></i>
                        </div>
                        <div class="card-info">
                            <h3>Perempuan</h3>
                            <h2 class="score-value">{{ $totalPerempuan ?? 0 }}</h2>
                        </div>
                    </div>
                </div>
                <div class="charts">
                    <div class="chart">
                        <div class="chart-header">
                            <h4>Kategori Mental Health</h4>
                        </div>
                        <div class="bar-container">
                            @php
                                $max = max($kategoriCounts ?: [1]);

                                /* warna setiap kategori */
                                $warnaKategori = [
                                    'Perlu Dukungan Intensif' => '#f44336', // merah
                                    'Perlu Dukungan' => '#ff9800', // oranye
                                    'Cukup Sehat' => '#ffc107', // kuning
                                    'Sehat' => '#2196f3', // biru
                                    'Sangat Sehat' => '#4caf50', // hijau
                                ];

                                /* urutan tampilan */
                                $urutanKategori = [
                                    'Perlu Dukungan Intensif',
                                    'Perlu Dukungan',
                                    'Cukup Sehat',
                                    'Sehat',
                                    'Sangat Sehat',
                                ];

                                /* label ringkas (sama dengan kategori sekarang, bisa dihapus jika redundant) */
                                $mapLabelPendek = [
                                    'Perlu Dukungan Intensif' => 'Perlu Dukungan Intensif',
                                    'Perlu Dukungan' => 'Perlu Dukungan',
                                    'Cukup Sehat' => 'Cukup Sehat',
                                    'Sehat' => 'Sehat',
                                    'Sangat Sehat' => 'Sangat Sehat',
                                ];
                            @endphp

                            @foreach ($urutanKategori as $kategori)
                                @php
                                    $jumlah = $kategoriCounts[$kategori] ?? 0;
                                    $heightPx = $jumlah > 0 ? round(($jumlah / $max) * 210) : 5;
                                    $color = $warnaKategori[$kategori] ?? '#999';
                                    $labelPendek = $mapLabelPendek[$kategori] ?? $kategori;
                                @endphp

                                <div class="bar-segment">
                                    <div class="bar-value">{{ $jumlah }} org</div>
                                    <div class="bar-fill"
                                        style="height: {{ $heightPx }}px; background-color: {{ $color }};">
                                    </div>
                                    <div class="bar-label">{{ $labelPendek }}</div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                    <div class="charts-prodi">
                        <div class="chart-header">
                            <h4>Fakultas</h4>
                        </div>
                        <div class="chart-canvas">
                            <div class="placeholder-pie">
                                @php $start = 0; @endphp
                                @if (isset($fakultasPersen))
                                    @foreach ($fakultasPersen as $fakultas => $persen)
                                        @php
                                            $color = $warnaFakultas[$fakultas] ?? '#999';
                                            $rotate = round(($start / 100) * 360, 2) . 'deg';
                                            $deg = round(($persen / 100) * 360, 2) . 'deg';
                                            $start += $persen;
                                        @endphp
                                        <div class="pie-segment"
                                            style="
                    --start: {{ $rotate }};
                    --size: {{ $deg }};
                    --color: {{ $color }};
                ">
                                        </div>
                                    @endforeach
                            </div>
                            <ul class="pie-legend">
                                @foreach ($fakultasPersen as $fakultas => $persen)
                                    @php
                                        $color = $warnaFakultas[$fakultas] ?? '#999';
                                        $count = $fakultasCount[$fakultas] ?? 0;
                                    @endphp
                                    <li>
                                        <span class="dot" style="background: {{ $color }}"></span>
                                        <span class="label">{{ $fakultas }}</span> {{-- Tampilkan langsung kunci singkatan --}}
                                        <span class="count">
                                            {{ $count }} Mahasiswa
                                            ({{ number_format($persen, 1) }}%)
                                        </span>
                                    </li>
                                @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                    {{-- Donut: Asal Sekolah --}}
                    @php
                        use App\Models\DataDiris;

                        $asalCounts = [
                            'SMA' => DataDiris::where('asal_sekolah', 'SMA')->count(),
                            'SMK' => DataDiris::where('asal_sekolah', 'SMK')->count(),
                            'Boarding School' => DataDiris::where('asal_sekolah', 'Boarding School')->count(),
                        ];
                        $totalAsal = array_sum($asalCounts);

                        $pct = function ($n) use ($totalAsal) {
                            return $totalAsal > 0 ? round(($n / $totalAsal) * 100, 1) : 0;
                        };

                        // Untuk SVG donut
                        $r = 60; // radius
                        $circ = 2 * M_PI * $r; // keliling lingkaran
                        $segments = [];
                        $offset = 0;
                        foreach ($asalCounts as $label => $val) {
                            $p = $totalAsal > 0 ? $val / $totalAsal : 0;
                            $dash = $circ * $p;
                            $segments[] = [
                                'label' => $label,
                                'value' => $val,
                                'percent' => $pct($val),
                                'dash' => $dash,
                                'offset' => $offset,
                            ];
                            $offset += $dash;
                        }
                    @endphp
                    <div class="chart" id="donutAsalSekolah">
                        <div class="donut-header">
                            <h4>Asal Sekolah</h4>
                        </div>

                        <div class="donut-wrap">
                            <svg class="donut" viewBox="0 0 160 160" width="100%" height="100%">
                                <!-- Ring background -->
                                <circle class="donut-bg" cx="80" cy="80" r="{{ $r }}">
                                </circle>

                                {{-- Segmen: urutan warna mengikuti legenda --}}
                                @php
                                    // mapping warna per label
                                    $colorMap = [
                                        'SMA' => '--c-sma',
                                        'SMK' => '--c-smk',
                                        'Boarding School' => '--c-boarding',
                                    ];
                                @endphp

                                @foreach ($segments as $seg)
                                    <circle class="donut-seg" cx="80" cy="80" r="{{ $r }}"
                                        style="
                        --seg-color: var({{ $colorMap[$seg['label']] ?? '--c-sma' }});
                    "
                                        data-dash="{{ $seg['dash'] }}" data-gap="{{ $circ - $seg['dash'] }}"
                                        data-offset="{{ $seg['offset'] }}"></circle>
                                @endforeach
                            </svg>

                            <div class="donut-center">
                                <div class="donut-total">{{ $totalUsers }}</div>
                                <div class="donut-sub">mahasiswa</div>
                            </div>
                        </div>

                        <ul class="donut-legend">
                            <li>
                                <span class="dot" style="background: var(--c-sma)"></span>
                                <span class="label">SMA</span>
                                <span class="count">{{ $asalCounts['SMA'] }} Mahasiswa
                                    ({{ $pct($asalCounts['SMA']) }}%)</span>
                            </li>
                            <li>
                                <span class="dot" style="background: var(--c-smk)"></span>
                                <span class="label">SMK</span>
                                <span class="count">{{ $asalCounts['SMK'] }} Mahasiswa
                                    ({{ $pct($asalCounts['SMK']) }}%)</span>
                            </li>
                            <li>
                                <span class="dot" style="background: var(--c-boarding)"></span>
                                <span class="label">Boarding School</span>
                                <span class="count">{{ $asalCounts['Boarding School'] }} Mahasiswa
                                    ({{ $pct($asalCounts['Boarding School']) }}%)</span>
                            </li>
                        </ul>
                    </div>
                    @php
                        // Hitung jumlah mahasiswa per status tinggal
                        $statusCounts = [
                            'Kost' => DataDiris::where('status_tinggal', 'Kost')->count(),
                            'Bersama Orang Tua' => DataDiris::where('status_tinggal', 'Bersama Orang Tua')->count(),
                        ];
                        $totalStatus = array_sum($statusCounts);

                        $pct = function ($n) use ($totalStatus) {
                            return $totalStatus > 0 ? round(($n / $totalStatus) * 100, 1) : 0;
                        };

                        // Untuk SVG donut
                        $r = 60;
                        $circ = 2 * M_PI * $r;
                        $segments = [];
                        $offset = 0;
                        foreach ($statusCounts as $label => $val) {
                            $p = $totalStatus > 0 ? $val / $totalStatus : 0;
                            $dash = $circ * $p;
                            $segments[] = [
                                'label' => $label,
                                'value' => $val,
                                'percent' => $pct($val),
                                'dash' => $dash,
                                'offset' => $offset,
                            ];
                            $offset += $dash;
                        }

                        // Mapping warna
                        $colorMap = [
                            'Kost' => '--c-kost',
                            'Bersama Orang Tua' => '--c-ortu',
                        ];
                    @endphp

                    <div class="chart">
                        <div class="donut-header">
                            <h4>Status Tinggal</h4>
                        </div>

                        <div class="donut-wrap">
                            <svg class="donut" viewBox="0 0 160 160" width="100%" height="100%">
                                <!-- Ring background -->
                                <circle class="donut-bg" cx="80" cy="80" r="{{ $r }}">
                                </circle>

                                @foreach ($segments as $seg)
                                    <circle class="donut-seg" cx="80" cy="80" r="{{ $r }}"
                                        style="
                stroke: var({{ $colorMap[$seg['label']] ?? '--c-kost' }});
                stroke-dasharray: {{ $seg['dash'] }} {{ $circ - $seg['dash'] }};
                stroke-dashoffset: -{{ $seg['offset'] }};
            ">
                                    </circle>
                                @endforeach
                            </svg>

                            <div class="donut-center">
                                <div class="donut-total">{{ $totalUsers }}</div>
                                <div class="donut-sub">mahasiswa</div>
                            </div>
                        </div>

                        <ul class="donut-legend">
                            @foreach ($statusCounts as $label => $count)
                                <li>
                                    <span class="dot" style="background: var({{ $colorMap[$label] }})"></span>
                                    <span class="label">{{ $label }}</span>
                                    <span class="count">{{ $count }} Mahasiswa ({{ $pct($count) }}%)</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="chart chart-prodi" id="chartProdiContainer">
                    <div class="chart-header-prodi">
                        <h4>Program Studi</h4>
                        <select id="fakultasDropdown">
                            <option value="FS">FS</option>
                            <option value="FTIK">FTIK</option>
                            <option value="FTI">FTI</option>
                        </select>
                    </div>
                    <div class="chart-canvas-prodi" id="chartCanvas">
                        @php
                            $prodiPerFakultas = [
                                'FS' => [
                                    'Fisika',
                                    'Matematika',
                                    'Biologi',
                                    'Kimia',
                                    'Farmasi',
                                    'Sains Data',
                                    'Sains Aktuaria',
                                    'Sains Lingkungan Kelautan',
                                    'Sains Atmosfer dan Keplanetan',
                                    'Magister Fisika',
                                ],
                                'FTIK' => [
                                    'Perencanaan Wilayah dan Kota',
                                    'Teknik Geomatika',
                                    'Teknik Sipil',
                                    'Arsitektur',
                                    'Teknik Lingkungan',
                                    'Teknik Kelautan',
                                    'Desain Komunikasi Visual',
                                    'Arsitektur Lanskap',
                                    'Teknik Perkeretaapian',
                                    'Rekayasa Tata Kelola Air Terpadu',
                                    'Pariwisata',
                                ],
                                'FTI' => [
                                    'Teknik Elektro',
                                    'Teknik Fisika',
                                    'Teknik Informatika',
                                    'Teknik Geologi',
                                    'Teknik Geofisika',
                                    'Teknik Mesin',
                                    'Teknik Kimia',
                                    'Teknik Material',
                                    'Teknik Sistem Energi',
                                    'Teknik Industri',
                                    'Teknik Telekomunikasi',
                                    'Teknik Biomedis',
                                    'Teknik Biosistem',
                                    'Teknik Pertambangan',
                                    'Teknologi Industri Pertanian',
                                    'Teknologi Pangan',
                                    'Rekayasa Kehutanan',
                                    'Rekayasa Kosmetik',
                                    'Rekayasa Minyak dan Gas',
                                    'Rekayasa Instrumentasi dan Automasi',
                                    'Rekayasa Keolahragaan',
                                ],
                            ];
                            $warnaProdi = [];
                            foreach (array_merge(...array_values($prodiPerFakultas)) as $index => $prodi) {
                                $warnaProdi[$prodi] = 'hsl(' . ($index * 33) % 360 . ', 70%, 60%)';
                            }
                        @endphp

                        {{-- Blade chart prodi per fakultas --}}
                        @foreach ($prodiPerFakultas as $kodeFakultas => $prodis)
                            @php
                                $chartHeight = count($prodis) * 38 + 60;
                            @endphp
                            <div class="horizontal-bar-chart fakultas-{{ $kodeFakultas }}"
                                data-jumlah="{{ count($prodis) }}"
                                style="display: {{ $loop->first ? 'block' : 'none' }}; min-height: {{ $chartHeight }}px">
                                @foreach ($prodis as $prodi)
                                    @php
                                        $jumlah = DataDiris::where('program_studi', $prodi)->count();
                                        $iconMap = [
                                            'Fisika' => 'fa-solid fa-atom',
                                            'Matematika' => 'fa-solid fa-square-root-variable',
                                            'Biologi' => 'fa-solid fa-dna',
                                            'Kimia' => 'fa-solid fa-vial',
                                            'Farmasi' => 'fa-solid fa-pills',
                                            'Sains Data' => 'fa-solid fa-database',
                                            'Sains Aktuaria' => 'fa-solid fa-percent',
                                            'Sains Lingkungan Kelautan' => 'fa-solid fa-water',
                                            'Sains Atmosfer dan Keplanetan' => 'fa-solid fa-earth-asia',
                                            'Magister Fisika' => 'fa-solid fa-atom',
                                            'Perencanaan Wilayah dan Kota' => 'fa-solid fa-city',
                                            'Teknik Geomatika' => 'fa-solid fa-map-location-dot',
                                            'Teknik Sipil' => 'fa-solid fa-building',
                                            'Arsitektur' => 'fa-solid fa-drafting-compass',
                                            'Teknik Lingkungan' => 'fa-solid fa-recycle',
                                            'Teknik Kelautan' => 'fa-solid fa-ship',
                                            'Desain Komunikasi Visual' => 'fa-solid fa-palette',
                                            'Arsitektur Lanskap' => 'fa-solid fa-tree',
                                            'Teknik Perkeretaapian' => 'fa-solid fa-train-tram',
                                            'Rekayasa Tata Kelola Air Terpadu' => 'fa-solid fa-faucet',
                                            'Pariwisata' => 'fa-solid fa-umbrella-beach',
                                            'Teknik Elektro' => 'fa-solid fa-bolt',
                                            'Teknik Fisika' => 'fa-solid fa-wave-square',
                                            'Teknik Informatika' => 'fa-solid fa-laptop-code',
                                            'Teknik Geologi' => 'fa-solid fa-mountain',
                                            'Teknik Geofisika' => 'fa-solid fa-compass-drafting',
                                            'Teknik Mesin' => 'fa-solid fa-cogs',
                                            'Teknik Kimia' => 'fa-solid fa-flask',
                                            'Teknik Material' => 'fa-solid fa-cube',
                                            'Teknik Sistem Energi' => 'fa-solid fa-solar-panel',
                                            'Teknik Industri' => 'fa-solid fa-industry',
                                            'Teknik Telekomunikasi' => 'fa-solid fa-tower-cell',
                                            'Teknik Biomedis' => 'fa-solid fa-heart-pulse',
                                            'Teknik Biosistem' => 'fa-solid fa-seedling',
                                            'Teknik Pertambangan' => 'fa-solid fa-hard-hat',
                                            'Teknologi Industri Pertanian' => 'fa-solid fa-tractor',
                                            'Teknologi Pangan' => 'fa-solid fa-carrot',
                                            'Rekayasa Kehutanan' => 'fa-solid fa-tree',
                                            'Rekayasa Kosmetik' => 'fa-solid fa-spa',
                                            'Rekayasa Minyak dan Gas' => 'fa-solid fa-oil-well',
                                            'Rekayasa Instrumentasi dan Automasi' => 'fa-solid fa-robot',
                                            'Rekayasa Keolahragaan' => 'fa-solid fa-dumbbell',
                                        ];
                                        $icon = $iconMap[$prodi] ?? 'fa-solid fa-graduation-cap';
                                    @endphp
                                    <div class="bar-line">
                                        <span class="bar-text">
                                            <i class="color-dot {{ $icon }}"></i>
                                            {{ $prodi }}
                                        </span>
                                        <div class="bar-track">
                                            <div class="bar-fill-prodi" data-raw="{{ $jumlah }}"></div>
                                            <span class="bar-count">
                                                {{ $jumlah }} org
                                                @if ($totalUsers > 0)
                                                    (<strong>{{ round(($jumlah / $totalUsers) * 100, 1) }}%</strong>)
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="chart chart-provinsi" id="chartProvinsiContainer">
                    <div class="chart-header-provinsi">
                        <h4>Provinsi</h4>
                    </div>
                    <div class="chart-canvas-provinsi" id="chartCanvasProvinsi">
                        @php
                            // Daftar provinsi — bisa ambil dari database langsung kalau mau
                            $daftarProvinsi = [
                                'Aceh',
                                'Sumatera Utara',
                                'Sumatera Barat',
                                'Riau',
                                'Kepulauan Riau',
                                'Jambi',
                                'Bengkulu',
                                'Kepulauan Bangka Belitung',
                                'Sumatera Selatan',
                                'Lampung',
                                'Banten',
                                'DKI Jakarta',
                                'Jawa Barat',
                                'Jawa Tengah',
                                'DI Yogyakarta',
                                'Jawa Timur',
                                'Bali',
                                'Nusa Tenggara Barat',
                                'Nusa Tenggara Timur',
                                'Kalimantan Barat',
                                'Kalimantan Tengah',
                                'Kalimantan Selatan',
                                'Kalimantan Timur',
                                'Kalimantan Utara',
                                'Sulawesi Utara',
                                'Sulawesi Tengah',
                                'Sulawesi Selatan',
                                'Sulawesi Tenggara',
                                'Gorontalo',
                                'Sulawesi Barat',
                                'Maluku',
                                'Maluku Utara',
                                'Papua',
                                'Papua Barat',
                                'Papua Selatan',
                                'Papua Tengah',
                                'Papua Pegunungan',
                                'Papua Barat Daya',
                            ];
                        @endphp

                        {{-- Loop provinsi --}}
                        <div class="horizontal-bar-chart-provinsi"
                            style="min-height: {{ count($daftarProvinsi) * 38 + 60 }}px">
                            @foreach ($daftarProvinsi as $provinsi)
                                @php
                                    $jumlah = DataDiris::where('provinsi', $provinsi)->count();
                                @endphp
                                <div class="bar-line">
                                    <span class="bar-text">
                                        <i class="color-dot fa-solid fa-location-dot"></i> {{-- icon provinsi --}}
                                        {{ $provinsi }}
                                    </span>
                                    <div class="bar-track">
                                        <div class="bar-fill-provinsi" data-raw="{{ $jumlah }}">
                                        </div>
                                        <span class="bar-count">
                                            {{ $jumlah }} org
                                            @if ($totalUsers > 0)
                                                (<strong>{{ round(($jumlah / $totalUsers) * 100, 1) }}%</strong>)
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
            <div class="tables">
                <div class="table-header">
                    <h2>
                        Aktivitas Terbaru
                    </h2>
                    <div class="table-controls">
                        <div class="left-controls">
                            {{-- Form Limit --}}
                            {{-- Form Limit --}}
                            <form method="GET" action="{{ route('admin.home') }}" class="limit-form">
                                <label for="limit" class="limit-label">Tampilkan:</label>

                                <div class="limit-actions">
                                    <select name="limit" id="limit" onchange="this.form.submit()">
                                        @foreach ([10, 25, 50] as $l)
                                            <option value="{{ $l }}"
                                                {{ request('limit') == $l ? 'selected' : '' }}>
                                                {{ $l }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <button type="button" class="reset-button" onclick="resetFilters()"
                                        title="Reset Filter">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>

                                {{-- Bawa parameter lain supaya tidak ter-reset --}}
                                <input type="hidden" name="search" value="{{ request('search') }}">
                                <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                                <input type="hidden" name="sort" value="{{ request('sort', 'created_at') }}">
                                <input type="hidden" name="order" value="{{ request('order', 'desc') }}">
                            </form>


                            {{-- Form Kategori --}}
                            <form method="GET" action="{{ route('admin.home') }}" class="category-form">
                                <div class="category-wrapper">
                                    <label for="kategori">Filter Kategori:</label>
                                    <div class="category-controls">
                                        <select name="kategori" id="kategori" onchange="this.form.submit()">
                                            <option value="">Pilih:</option>
                                            @foreach (['Perlu Dukungan Intensif', 'Perlu Dukungan', 'Cukup Sehat', 'Sehat', 'Sangat Sehat'] as $k)
                                                <option value="{{ $k }}"
                                                    {{ request('kategori') == $k ? 'selected' : '' }}>
                                                    {{ $k }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="reset-button" onclick="resetFilters()"
                                            title="Reset Filter">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </div>
                                </div>

                                <input type="hidden" name="search" value="{{ request('search') }}">
                                <input type="hidden" name="limit" value="{{ request('limit', 10) }}">
                                <input type="hidden" name="sort" value="{{ request('sort', 'created_at') }}">
                                <input type="hidden" name="order" value="{{ request('order', 'desc') }}">
                            </form>
                        </div>
                        {{-- Search Box --}}
                        <div class="right-controls">
                            <form action="{{ route('admin.home') }}" method="GET" class="search-form">
                                <input type="text" name="search" placeholder="Cari data..."
                                    value="{{ request('search') }}">

                                {{-- Simpan filter lain --}}
                                <input type="hidden" name="limit" value="{{ request('limit', 10) }}">
                                <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                                <input type="hidden" name="sort" value="{{ request('sort', 'created_at') }}">
                                <input type="hidden" name="order" value="{{ request('order', 'desc') }}">

                                <button type="submit" class="search-button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>


                </div>
                <div class="table">
                    <table id="assessmentTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>
                                    <a href="{{ route('admin.home', ['sort' => 'nim', 'order' => request('order') === 'asc' && request('sort') === 'nim' ? 'desc' : 'asc'] + request()->except(['page'])) }}"
                                        class="sortable-header">
                                        NIM
                                        @if (request('sort') === 'nim')
                                            <i
                                                class="fas fa-sort-{{ request('order') === 'asc' ? 'up' : 'down' }}"></i>
                                        @else
                                            <i class="fas fa-sort"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ route('admin.home', ['sort' => 'nama', 'order' => request('order') === 'asc' && request('sort') === 'nama' ? 'desc' : 'asc'] + request()->except(['page'])) }}"
                                        class="sortable-header">
                                        Nama
                                        @if (request('sort') === 'nama')
                                            <i
                                                class="fas fa-sort-{{ request('order') === 'asc' ? 'up' : 'down' }}"></i>
                                        @else
                                            <i class="fas fa-sort"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>Fakultas</th>
                                <th>Program Studi</th>
                                <th>Jenis Kelamin</th>
                                <th>Usia</th>
                                <th>Provinsi</th>
                                <th>Alamat</th>
                                <th>Email</th>
                                <th>Asal Sekolah</th>
                                <th>Status Tinggal</th>
                                <th>Jumlah Tes</th> {{-- ✅ HEADER BARU --}}
                                <th>Kategori Terakhir</th>
                                <th>
                                    <a href="{{ route('admin.home', ['sort' => 'created_at', 'order' => request('order') === 'asc' && request('sort') === 'created_at' ? 'desc' : 'asc'] + request()->except(['page'])) }}"
                                        class="sortable-header">
                                        Tanggal Submit
                                        @if (request('sort') === 'created_at')
                                            <i
                                                class="fas fa-sort-{{ request('order') === 'asc' ? 'up' : 'down' }}"></i>
                                        @else
                                            <i class="fas fa-sort"></i>
                                        @endif
                                    </a>
                                </th>
                                <th style="text-align: center;">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($hasilKuesioners as $hasil)
                                <tr>
                                    <td>{{ $loop->iteration + ($hasilKuesioners->firstItem() - 1) }}</td>
                                    <td>{{ $hasil->nim }}</td>
                                    <td>{{ $hasil->dataDiri->nama ?? 'Tidak Ada Data' }}</td>
                                    <td>{{ $hasil->dataDiri->fakultas ?? 'Tidak Ada Data' }}</td>
                                    <td>{{ $hasil->dataDiri->program_studi ?? 'Tidak Ada Data' }}</td>
                                    <td>{{ $hasil->dataDiri->jenis_kelamin ?? '-' }}</td>
                                    <td>{{ $hasil->dataDiri->usia ?? '-' }}</td>
                                    <td>{{ $hasil->dataDiri->provinsi ?? 'Tidak Ada Data' }}</td>
                                    <td>{{ $hasil->dataDiri->alamat ?? 'Tidak Ada Data' }}</td>
                                    <td class="email">{{ $hasil->dataDiri->email ?? '-' }}</td>
                                    <td>{{ $hasil->dataDiri->asal_sekolah ?? '-' }}</td>
                                    <td>{{ $hasil->dataDiri->status_tinggal ?? '-' }}</td>
                                    <td>{{ $hasil->jumlah_tes }} Kali</td> {{-- ✅ KOLOM BARU --}}
                                    <td>
                                        <span
                                            class="
        range-badge
        @if ($hasil->kategori === 'Perlu Dukungan Intensif') range-very-poor
        @elseif ($hasil->kategori === 'Perlu Dukungan') range-poor
        @elseif ($hasil->kategori === 'Cukup Sehat') range-moderate
        @elseif ($hasil->kategori === 'Sehat') range-good
        @elseif ($hasil->kategori === 'Sangat Sehat') range-excellent @endif
    ">
                                            {{ $hasil->kategori }}
                                        </span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($hasil->created_at)->locale('id')->setTimezone('Asia/Jakarta')->translatedFormat('l, d M Y - H:i') }}
                                    </td>
                                    <td>
                                        <div class="action-buttons"
                                            style="display: flex; gap: 10px; align-items: center; justify-content: center;">
                                            <button type="button"
                                                onclick="openModal('modal-riwayat-{{ $hasil->id }}')"
                                                class="history-button tooltip-action"
                                                style="background-color:#1d4ed8; color:white"
                                                title="Riwayat Tes">
                                                <i class="fas fa-history"></i>
                                            </button>

                                            <button type="button" class="print-button tooltip-action"
                                                style="background-color: green; color:white" onclick="printPDF(this)"
                                                dusk="print-button-{{ $hasil->id }}"
                                                title="Cetak PDF">
                                                <svg class="svgIcon" viewBox="0 0 384 512" height="1em"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M169.4 470.6c12.5 12.5 32.8 12.5 45.3 0l160-160c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L224 370.8V64c0-17.7-14.3-32-32-32s-32 14.3-32 32v306.7L54.6 265.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l160 160z" />
                                                </svg>
                                            </button>
                                            <button type="button" style="background-color: red"
                                                class="delete-button" onclick="confirmDelete({{ $hasil->id }})"
                                                dusk="delete-button-{{ $hasil->id }}" title="Hapus">
                                                <svg viewBox="0 0 15 17.5" height="17.5" width="15"
                                                    xmlns="http://www.w3.org/2000/svg" class="icon">
                                                    <path transform="translate(-2.5 -1.25)"
                                                        d="M15,18.75H5A1.251,1.251,0,0,1,3.75,17.5V5H2.5V3.75h15V5H16.25V17.5A1.251,1.251,0,0,1,15,18.75ZM5,5V17.5H15V5Zm7.5,10H11.25V7.5H12.5V15ZM8.75,15H7.5V7.5H8.75V15ZM12.5,2.5h-5V1.25h5V2.5Z" />
                                                </svg>
                                            </button>
                                            <form id="delete-form-{{ $hasil->id }}"
                                                action="{{ route('admin.delete', $hasil->id) }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Modal Riwayat -->
                                <div id="modal-riwayat-{{ $hasil->id }}"
                                    class="custom-modal" onclick="backdropClose(event, 'modal-riwayat-{{ $hasil->id }}')">
                                    <div class="custom-modal-content" onclick="event.stopPropagation();">
                                        <span class="custom-modal-close"
                                            onclick="closeModal('modal-riwayat-{{ $hasil->id }}')">&times;</span>
                                        <h4 style="text-align: center; font-size: large; padding: 1rem;">Riwayat
                                            Keluhan & Hasil - {{ $hasil->dataDiri->nama ?? $hasil->nim }}</h4>
                                        @php
                                            $merged = collect();

                                            /* Gabungkan kedua koleksi menjadi satu array */
                                            $semuaData = [
                                                ...$hasil->dataDiri->hasilKuesioners->map(
                                                    fn($k) => ['type' => 'kuesioner', 'record' => $k],
                                                ),
                                                ...$hasil->dataDiri->riwayatKeluhans->map(
                                                    fn($r) => ['type' => 'keluhan', 'record' => $r],
                                                ),
                                            ];

                                            /* Iterasi satu kali saja */
                                            foreach ($semuaData as $item) {
                                                $created = $item['record']->created_at;
                                                $key = \Carbon\Carbon::parse($created)->format('Y-m-d H:i');

                                                $merged->push([
                                                    'group_key' => $key,
                                                    'type' => $item['type'],
                                                    'created_at' => $created,
                                                    'data' => $item['record'],
                                                ]);
                                            }

                                            $grouped = $merged->sortByDesc('created_at')->groupBy('group_key');
                                        @endphp
                                        @forelse ($grouped as $timestamp => $entries)
                                            @php
                                                $tgl = \Carbon\Carbon::parse($entries->first()['created_at'])
                                                    ->locale('id')
                                                    ->setTimezone('Asia/Jakarta')
                                                    ->translatedFormat('l, d M Y - H:i');

                                                $kuesioner = $entries->firstWhere('type', 'kuesioner')['data'] ?? null;
                                                $keluhan = $entries->firstWhere('type', 'keluhan')['data'] ?? null;
                                            @endphp

                                            <div style="margin-bottom:18px;">
                                                <strong>
                                                    <i class="fas fa-calendar-alt"></i> Tanggal:
                                                </strong> {{ $tgl }}<br>

                                                @if ($kuesioner)
                                                    <strong>
                                                        <i class="fas fa-chart-line"></i> Skor Total:
                                                    </strong> {{ $kuesioner->total_skor }}<br>

                                                    <strong>
                                                        <i class="fas fa-tag"></i> Kategori:
                                                    </strong>
                                                    @php
                                                        $kategoriSingkat = [
                                                            'Perlu Dukungan Intensif' => 'Perlu Dukungan Intensif',
                                                            'Perlu Dukungan' => 'Perlu Dukungan',
                                                            'Cukup Sehat' => 'Cukup Sehat',
                                                            'Sehat' => 'Sehat',
                                                            'Sangat Sehat' => 'Sangat Sehat',
                                                            'Tidak Terdefinisi' => 'Tidak Terdefinisi',
                                                        ];
                                                        $kelasKategori = [
                                                            'Perlu Dukungan Intensif' => 'range-very-poor',
                                                            'Perlu Dukungan' => 'range-poor',
                                                            'Cukup Sehat' => 'range-moderate',
                                                            'Sehat' => 'range-good',
                                                            'Sangat Sehat' => 'range-excellent',
                                                        ];

                                                        $kategoriLabel =
                                                            $kategoriSingkat[$kuesioner->kategori] ?? 'Tidak Diketahui';
                                                        $badgeClass = $kelasKategori[$kategoriLabel] ?? 'bg-gray-300';
                                                    @endphp
                                                    <span
                                                        class="kategori-badge {{ $badgeClass }}">{{ $kategoriLabel }}
                                                    </span>
                                                    <br>
                                                    <div style="margin-top: 12px;">
                                                        <a href="{{ route('admin.mental-health.detail', $kuesioner->id) }}"
                                                           class="btn-detail-jawaban"
                                                           onclick="event.stopPropagation();">
                                                            <i class="fas fa-list-alt"></i>
                                                            <span>Lihat Detail Jawaban</span>
                                                        </a>
                                                    </div>
                                                @endif

                                                @if ($keluhan)
                                                    <strong>
                                                        <i class="fas fa-comment-medical"></i> Keluhan:
                                                    </strong> {{ $keluhan->keluhan }}<br>

                                                    <strong>
                                                        <i class="fas fa-hourglass-half"></i> Lama Keluhan:
                                                    </strong> {{ $keluhan->lama_keluhan }} Bulan<br>

                                                    <strong>
                                                        <i class="fas fa-vials"></i> Pernah Tes:
                                                    </strong> {{ $keluhan->pernah_tes }}<br>

                                                    <strong>
                                                        <i class="fas fa-user-md"></i> Pernah Konsul:
                                                    </strong> {{ $keluhan->pernah_konsul }}<br>
                                                @endif
                                            </div>

                                            <hr>
                                        @empty
                                            <p class="text-center">Tidak ada data keluhan atau kuesioner.</p>
                                        @endforelse
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="13" class="text-center">
                                        @if (request()->has('search') && request('search'))
                                            <span class="text-danger">Tidak ditemukan hasil untuk:
                                                <strong>{{ request('search') }}</strong></span>
                                        @else
                                            Belum ada data hasil kuesioner.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="pagination">
                    {{ $hasilKuesioners->links('vendor.pagination.default') }} </div>
                <div class="button-control">
                    {{-- TOMBOL EXPORT EXCEL --}}
                    <form action="{{ route('admin.export.excel') }}" method="GET" style="display: inline;">
                        {{-- Loop melalui parameter query yang ada dan menambahkannya sebagai input tersembunyi --}}
                        @foreach (request()->query() as $key => $value)
                            @if (is_array($value))
                                @foreach ($value as $val)
                                    <input type="hidden" name="{{ $key }}[]"
                                        value="{{ $val }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <button type="submit" class="export-button">
                            <i class="fas fa-file-excel"></i> Export ke Excel
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
<x-footer></x-footer>
<script src="{{ asset('js/script-admin-mh.js') }}"></script>
@if ($searchMessage)
    <script>
        Swal.fire({
            title: 'Hasil Pencarian',
            text: '{{ $searchMessage }}',
            icon: '{{ $searchMessage === 'Data berhasil ditemukan!' ? 'success' : 'warning' }}',
            timer: 2500,
            showConfirmButton: false
        });
    </script>
@endif
<script>
    // Konfirmasi sebelum hapus
    function confirmDelete(id) {
        Swal.fire({
            title: "Yakin ingin menghapus data ini?",
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById("delete-form-" + id).submit();
            }
        });
    }

    // Popup setelah berhasil dihapus
    @if (session('success'))
        Swal.fire({
            title: "Berhasil!",
            text: "{{ session('success') }}",
            icon: "success",
            confirmButtonColor: "#3085d6",
            confirmButtonText: "OK"
        });
    @endif
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</html>
