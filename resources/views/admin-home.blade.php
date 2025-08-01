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
    <link href="{{ asset('css/style-admin-home.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style-footer.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            <div class="account-group"> {{-- pembungkus untuk :focus-within --}}
                <button class="account-toggle">
                    <i class="fas fa-user-circle"></i>
                    <span>{{ Auth::guard('admin')->user()->username }}</span>
                    <i class="fas fa-caret-down caret"></i>
                </button>

                <div class="account-dropdown">
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
            <div class="logo">
                <h2>Dashboard</h2>
            </div>
            <ul class="menu">
                <li>
                    <a href="/admin"><i class="fas fa-home" style="margin-right: 1rem;"></i> Home</a>
                </li>
                <li class="active">
                    <a href="/admin/mental-health"><i class="fas fa-brain " style="margin-right: 1rem;"></i> Mental
                        Health</a>
                </li>
                <li>
                    <a href="/admin-home-karir">
                        <i class="fas fa-briefcase" style="margin-right: 1rem;"></i> Peminatan Karir
                    </a>
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
                            <h3>Mental Health Tests</h3>
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
                                    'Sangat Buruk (Distres Berat)' => '#f44336',
                                    'Buruk (Distres Sedang)' => '#ff9800',
                                    'Sedang (Rentan)' => '#ffc107',
                                    'Baik (Sehat Secara Mental)' => '#2196f3',
                                    'Sangat Baik (Sejahtera Secara Mental)' => '#4caf50',
                                ];

                                /* urutan tampilan */
                                $urutanKategori = [
                                    'Sangat Buruk (Distres Berat)',
                                    'Buruk (Distres Sedang)',
                                    'Sedang (Rentan)',
                                    'Baik (Sehat Secara Mental)',
                                    'Sangat Baik (Sejahtera Secara Mental)',
                                ];

                                /* label ringkas */
                                $mapLabelPendek = [
                                    'Sangat Buruk (Distres Berat)' => 'Berat',
                                    'Buruk (Distres Sedang)' => ' Sedang',
                                    'Sedang (Rentan)' => 'Rentan',
                                    'Baik (Sehat Secara Mental)' => 'Sehat',
                                    'Sangat Baik (Sejahtera Secara Mental)' => 'Sejahtera',
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
                    <div class="chart">
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
                            <div class="pie-legend">
                                @foreach ($fakultasPersen as $fakultas => $persen)
                                    @php
                                        $color = $warnaFakultas[$fakultas] ?? '#999';
                                        $count = $fakultasCount[$fakultas] ?? 0;

                                        $singkatan = match ($fakultas) {
                                            'Fakultas Sains' => 'FS',
                                            'Fakultas Teknologi Industri' => 'FTI',
                                            'Fakultas Teknologi Infrastruktur dan Kewilayahan' => 'FTIK',
                                            default => 'Lainnya',
                                        };
                                    @endphp
                                    <div>
                                        <span class="legend-color"
                                            style="background-color: {{ $color }};"></span>
                                        {{ $singkatan }} ({{ $count }} Mahasiswa,
                                        <strong>{{ $persen }}%</strong>)
                                    </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
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
                                        $jumlah = \App\Models\DataDiris::where('program_studi', $prodi)->count();
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
            </div>
            <div class="tables">
                <div class="table-header">
                    <h3>Aktivitas Terbaru</h3>
                    <div class="table-controls">
                        <form method="GET" action="{{ route('admin.home') }}" class="limit-form">
                            <label for="limit">Tampilkan:</label>
                            <select name="limit" id="limit" onchange="this.form.submit()">
                                @foreach ([10, 25, 50, 100] as $l)
                                    <option value="{{ $l }}"
                                        {{ request('limit') == $l ? 'selected' : '' }}>
                                        {{ $l }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        </form>
                        {{-- Search Box --}}
                        <form action="{{ route('admin.home') }}" method="GET" class="search-form">
                            <input type="text" name="search" placeholder="Cari Data..."
                                value="{{ request('search') }}">
                            <input type="hidden" name="limit" value="{{ request('limit', 10) }}">
                            <button type="submit" class="search-button"><i class="fas fa-search"></i></button>
                        </form>
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
                                <th>Program Studi</th>
                                <th>Jenis Kelamin</th>
                                <th>Usia</th>
                                <th>Jenis Tes</th>
                                <th>Email</th>
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
                                    <td>{{ $hasil->dataDiri->program_studi ?? 'Tidak Ada Data' }}</td>
                                    <td>{{ $hasil->dataDiri->jenis_kelamin ?? '-' }}</td>
                                    <td>{{ $hasil->dataDiri->usia ?? '-' }}</td>
                                    <td>Mental Health</td>
                                    <td>{{ $hasil->dataDiri->email ?? '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($hasil->created_at)->setTimezone('Asia/Jakarta')->translatedFormat('l, d M Y - H:i') }}
                                    </td>
                                    <td>
                                        <div class="action-buttons"
                                            style="display: flex; gap: 10px; align-items: center; justify-content: center;">
                                            <button type="button"
                                                onclick="openModal('modal-riwayat-{{ $hasil->id }}')"
                                                class="history-button tooltip-action" {{-- kelas baru + tooltip-action --}}
                                                style="background-color:#1d4ed8; color:white">
                                                <i class="fas fa-history"></i>
                                            </button>

                                            <button type="button" class="print-button tooltip-action"
                                                style="background-color: green; color:white" onclick="printPDF(this)"
                                                dusk="print-button-{{ $hasil->id }}">
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
                                    class="custom-modal"onclick="backdropClose(event, 'modal-riwayat-{{ $hasil->id }}')">
                                    <div class="custom-modal-content">
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
                                                    </strong> {{ $kuesioner->kategori }}<br>
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
                                    <td colspan="10" class="text-center">
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
                <button class="btn-pdf" onclick="generatePDF()">
                    <i class="fas fa-file-pdf"></i> Cetak PDF
                </button>
            </div>
        </div>
    </div>
</body>
<x-footer></x-footer>
<script src="{{ asset('js/script-admin-mh.js') }}"></script>
@if (session('searchMessage') || isset($searchMessage))
    <script>
        Swal.fire({
            title: 'Hasil Pencarian',
            text: '{{ $searchMessage ?? session('searchMessage') }}',
            icon: '{{ isset($searchMessage) && $searchMessage === 'Data berhasil ditemukan!' ? 'success' : 'warning' }}',
            timer: 3000,
            showConfirmButton: false
        });
    </script>
@endif
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</html>
