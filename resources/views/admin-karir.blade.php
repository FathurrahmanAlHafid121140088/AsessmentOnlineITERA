@php
    use App\Models\KarirDataDiri;
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Admin Dashboard - Peminatan Karir</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.ico') }}" />
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
    <!-- AOS Library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">

    <!-- Independent CSS for Admin Karir Page (NO dependencies on other CSS files) -->
    <link rel="stylesheet" href="{{ asset('css/admin-karir.css') }}">

    <!-- Footer CSS -->
    <link rel="stylesheet" href="{{ asset('css/style-footer.css') }}">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

</head>

<body>
    <header>
        <div class="hamburger" id="hamburger">
            <i class="fas fa-bars"></i>
        </div>
        <div class="header-title" style="color: white">
            <h2>Dashboard Peminatan Karir</h2>
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
                        <li>
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
                <div class="cards">
                    <div class="card bg-danger text-white">
                        <div class="card-icon bg-white text-primary">
                            <i class="fas fa-users" style="color: #ef4444"></i>
                        </div>
                        <div class="card-info">
                            <h3>Total Peserta</h3>
                            <h2 class="score-value">{{ $totalUsers ?? 0 }}</h2>
                        </div>
                    </div>

                    <div class="card bg-success text-white">
                        <div class="card-icon bg-white text-success">
                            <i class="fas fa-clipboard-list" style="color: #22c55e"></i>
                        </div>
                        <div class="card-info">
                            <h3>Total Tes</h3>
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
                    {{-- Donut: Status Tinggal --}}
                    @php
                        $totalStatusTinggal = array_sum($statusTinggalCounts);

                        $pctStatusTinggal = function ($n) use ($totalStatusTinggal) {
                            return $totalStatusTinggal > 0 ? round(($n / $totalStatusTinggal) * 100, 1) : 0;
                        };

                        // Untuk SVG donut
                        $r = 60; // radius
                        $circ = 2 * M_PI * $r; // keliling lingkaran
                        $segmentsStatusTinggal = [];
                        $offset = 0;
                        foreach ($statusTinggalCounts as $label => $val) {
                            $p = $totalStatusTinggal > 0 ? $val / $totalStatusTinggal : 0;
                            $dash = $circ * $p;
                            $segmentsStatusTinggal[] = [
                                'label' => $label,
                                'value' => $val,
                                'percent' => $pctStatusTinggal($val),
                                'dash' => $dash,
                                'offset' => $offset,
                            ];
                            $offset += $dash;
                        }
                    @endphp
                    <div class="chart" id="donutStatusTinggal">
                        <div class="donut-header">
                            <h4>Status Tinggal</h4>
                        </div>

                        @if ($totalStatusTinggal > 0)
                            <div class="donut-wrap">
                                <svg class="donut" viewBox="0 0 160 160" width="100%" height="100%">
                                    <!-- Ring background -->
                                    <circle class="donut-bg" cx="80" cy="80" r="{{ $r }}">
                                    </circle>

                                    {{-- Segmen: urutan warna mengikuti legenda --}}
                                    @php
                                        // mapping warna per label
                                        $colorMapStatusTinggal = [
                                            'Kost' => '--c-kost',
                                            'Bersama Orang Tua' => '--c-orangtua',
                                        ];
                                    @endphp

                                    @foreach ($segmentsStatusTinggal as $seg)
                                        <circle class="donut-seg" cx="80" cy="80"
                                            r="{{ $r }}"
                                            style="
                            --seg-color: var({{ $colorMapStatusTinggal[$seg['label']] ?? '--c-kost' }});
                        "
                                            data-dash="{{ $seg['dash'] }}" data-gap="{{ $circ - $seg['dash'] }}"
                                            data-offset="{{ $seg['offset'] }}"></circle>
                                    @endforeach
                                </svg>

                                <div class="donut-center">
                                    <div class="donut-total">{{ $totalStatusTinggal }}</div>
                                    <div class="donut-sub">mahasiswa</div>
                                </div>
                            </div>

                            <ul class="donut-legend">
                                <li>
                                    <span class="dot" style="background: var(--c-kost)"></span>
                                    <span class="label">Kost</span>
                                    <span class="count">{{ $statusTinggalCounts['Kost'] }} Mahasiswa
                                        ({{ $pctStatusTinggal($statusTinggalCounts['Kost']) }}%)</span>
                                </li>
                                <li>
                                    <span class="dot" style="background: var(--c-orangtua)"></span>
                                    <span class="label">Bersama Orang Tua</span>
                                    <span class="count">{{ $statusTinggalCounts['Bersama Orang Tua'] }} Mahasiswa
                                        ({{ $pctStatusTinggal($statusTinggalCounts['Bersama Orang Tua']) }}%)</span>
                                </li>
                            </ul>
                        @else
                            <div style="text-align: center; padding: 40px 20px; color: #94a3b8;">
                                <i class="fas fa-inbox"
                                    style="font-size: 48px; margin-bottom: 10px; opacity: 0.5;"></i>
                                <p style="margin: 0; font-style: italic;">Belum ada data status tinggal</p>
                            </div>
                        @endif
                    </div>

                    {{-- Donut: Prodi Sesuai Keinginan --}}
                    @php
                        $totalProdiSesuai = array_sum($prodiSesuaiCounts);

                        $pctProdiSesuai = function ($n) use ($totalProdiSesuai) {
                            return $totalProdiSesuai > 0 ? round(($n / $totalProdiSesuai) * 100, 1) : 0;
                        };

                        // Untuk SVG donut
                        $segmentsProdiSesuai = [];
                        $offset = 0;
                        foreach ($prodiSesuaiCounts as $label => $val) {
                            $p = $totalProdiSesuai > 0 ? $val / $totalProdiSesuai : 0;
                            $dash = $circ * $p;
                            $segmentsProdiSesuai[] = [
                                'label' => $label,
                                'value' => $val,
                                'percent' => $pctProdiSesuai($val),
                                'dash' => $dash,
                                'offset' => $offset,
                            ];
                            $offset += $dash;
                        }
                    @endphp
                    <div class="chart" id="donutProdiSesuai">
                        <div class="donut-header">
                            <h4>Prodi Sesuai Keinginan</h4>
                        </div>

                        @if ($totalProdiSesuai > 0)
                            <div class="donut-wrap">
                                <svg class="donut" viewBox="0 0 160 160" width="100%" height="100%">
                                    <!-- Ring background -->
                                    <circle class="donut-bg" cx="80" cy="80" r="{{ $r }}">
                                    </circle>

                                    {{-- Segmen: urutan warna mengikuti legenda --}}
                                    @php
                                        // mapping warna per label
                                        $colorMapProdiSesuai = [
                                            'Ya' => '--c-ya',
                                            'Tidak' => '--c-tidak',
                                        ];
                                    @endphp

                                    @foreach ($segmentsProdiSesuai as $seg)
                                        <circle class="donut-seg" cx="80" cy="80"
                                            r="{{ $r }}"
                                            style="
                            --seg-color: var({{ $colorMapProdiSesuai[$seg['label']] ?? '--c-ya' }});
                        "
                                            data-dash="{{ $seg['dash'] }}" data-gap="{{ $circ - $seg['dash'] }}"
                                            data-offset="{{ $seg['offset'] }}"></circle>
                                    @endforeach
                                </svg>

                                <div class="donut-center">
                                    <div class="donut-total">{{ $totalProdiSesuai }}</div>
                                    <div class="donut-sub">mahasiswa</div>
                                </div>
                            </div>

                            <ul class="donut-legend">
                                <li>
                                    <span class="dot" style="background: var(--c-ya)"></span>
                                    <span class="label">Ya</span>
                                    <span class="count">{{ $prodiSesuaiCounts['Ya'] }} Mahasiswa
                                        ({{ $pctProdiSesuai($prodiSesuaiCounts['Ya']) }}%)</span>
                                </li>
                                <li>
                                    <span class="dot" style="background: var(--c-tidak)"></span>
                                    <span class="label">Tidak</span>
                                    <span class="count">{{ $prodiSesuaiCounts['Tidak'] }} Mahasiswa
                                        ({{ $pctProdiSesuai($prodiSesuaiCounts['Tidak']) }}%)</span>
                                </li>
                            </ul>
                        @else
                            <div style="text-align: center; padding: 40px 20px; color: #94a3b8;">
                                <i class="fas fa-inbox"
                                    style="font-size: 48px; margin-bottom: 10px; opacity: 0.5;"></i>
                                <p style="margin: 0; font-style: italic;">Belum ada data prodi sesuai keinginan</p>
                            </div>
                        @endif
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
                                        <span class="label">{{ $fakultas }}</span>
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
                        $asalCounts = $asalSekolahCounts;
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

                        @if ($totalAsal > 0)
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
                                        <circle class="donut-seg" cx="80" cy="80"
                                            r="{{ $r }}"
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
                        @else
                            <div style="text-align: center; padding: 40px 20px; color: #94a3b8;">
                                <i class="fas fa-inbox"
                                    style="font-size: 48px; margin-bottom: 10px; opacity: 0.5;"></i>
                                <p style="margin: 0; font-style: italic;">Belum ada data asal sekolah</p>
                            </div>
                        @endif
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
                                        $jumlah = KarirDataDiri::whereHas('hasilTes')
                                            ->where('program_studi', $prodi)
                                            ->count();
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
                            // Daftar provinsi
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
                                    $jumlah = KarirDataDiri::whereHas('hasilTes')
                                        ->where('provinsi', $provinsi)
                                        ->count();
                                @endphp
                                <div class="bar-line">
                                    <span class="bar-text">
                                        <i class="color-dot fa-solid fa-location-dot"></i>
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
                            <form method="GET" action="{{ route('admin.karir.index') }}" class="limit-form">
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
                            </form>
                        </div>
                        {{-- Search Box --}}
                        <div class="right-controls">
                            <form action="{{ route('admin.karir.index') }}" method="GET" class="search-form">
                                <input type="text" name="search" placeholder="Cari data..."
                                    value="{{ request('search') }}">

                                {{-- Simpan filter lain --}}
                                <input type="hidden" name="limit" value="{{ request('limit', 10) }}">

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
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Fakultas</th>
                                <th>Program Studi</th>
                                <th>Prodi Sesuai Keinginan</th>
                                <th>Jenis Kelamin</th>
                                <th>Usia</th>
                                <th>Provinsi</th>
                                <th>Alamat</th>
                                <th>Email</th>
                                <th>Asal Sekolah</th>
                                <th>Status Tinggal</th>
                                <th>Tanggal Tes</th>
                                <th style="text-align: center;">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($hasilTes as $hasil)
                                <tr>
                                    <td>{{ $loop->iteration + ($hasilTes->firstItem() - 1) }}</td>
                                    <td>{{ $hasil->karirDataDiri->nim ?? '-' }}</td>
                                    <td>{{ $hasil->karirDataDiri->nama ?? 'Tidak Ada Data' }}</td>
                                    <td>{{ $hasil->karirDataDiri->fakultas ?? 'Tidak Ada Data' }}</td>
                                    <td>{{ $hasil->karirDataDiri->program_studi ?? 'Tidak Ada Data' }}</td>
                                    <td>{{ $hasil->karirDataDiri->prodi_sesuai_keinginan ?? '-' }}</td>
                                    <td>{{ $hasil->karirDataDiri->jenis_kelamin ?? '-' }}</td>
                                    <td>{{ $hasil->karirDataDiri->usia ?? '-' }}</td>
                                    <td>{{ $hasil->karirDataDiri->provinsi ?? '-' }}</td>
                                    <td>{{ $hasil->karirDataDiri->alamat ?? '-' }}</td>
                                    <td>{{ $hasil->karirDataDiri->email ?? '-' }}</td>
                                    <td>{{ $hasil->karirDataDiri->asal_sekolah ?? '-' }}</td>
                                    <td>{{ $hasil->karirDataDiri->status_tinggal ?? '-' }}</td>
                                    <td>{{ $hasil->tanggal_pengerjaan ? \Carbon\Carbon::parse($hasil->tanggal_pengerjaan)->locale('id')->setTimezone('Asia/Jakarta')->translatedFormat('l, d M Y - H:i') : '-' }}
                                    </td>
                                    <td>
                                        <div class="action-buttons"
                                            style="display: flex; gap: 10px; align-items: center; justify-content: center;">
                                            @php
                                                $jumlahTes = $hasil->karirDataDiri->hasilTes->count();
                                            @endphp
                                            <button type="button"
                                                onclick="openModal('modal-riwayat-{{ $hasil->id }}')"
                                                class="history-button tooltip-action"
                                                title="Riwayat Tes ({{ $jumlahTes }}x)"
                                                style="background-color:#16a34a; color:white; padding: 0.5rem 0.75rem; border: none; border-radius: 0.375rem; cursor: pointer;">
                                                <i class="fas fa-history"></i>
                                            </button>

                                            <a href="{{ route('admin.karir.detail', $hasil->id) }}">
                                                <button type="button" class="view-button tooltip-action"
                                                    style="background-color:#1d4ed8; color:white"
                                                    title="Lihat Detail Matrix RMIB">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </a>

                                            <button type="button" style="background-color: red"
                                                class="delete-button" onclick="confirmDelete({{ $hasil->id }})"
                                                title="Hapus">
                                                <svg viewBox="0 0 15 17.5" height="17.5" width="15"
                                                    xmlns="http://www.w3.org/2000/svg" class="icon">
                                                    <path transform="translate(-2.5 -1.25)"
                                                        d="M15,18.75H5A1.251,1.251,0,0,1,3.75,17.5V5H2.5V3.75h15V5H16.25V17.5A1.251,1.251,0,0,1,15,18.75ZM5,5V17.5H15V5Zm7.5,10H11.25V7.5H12.5V15ZM8.75,15H7.5V7.5H8.75V15ZM12.5,2.5h-5V1.25h5V2.5Z" />
                                                </svg>
                                            </button>
                                            <form id="delete-form-{{ $hasil->id }}"
                                                action="{{ route('admin.karir.delete', $hasil->id) }}"
                                                method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Riwayat -->
                                <div id="modal-riwayat-{{ $hasil->id }}" class="custom-modal"
                                    onclick="backdropClose(event, 'modal-riwayat-{{ $hasil->id }}')">
                                    <div class="custom-modal-content">
                                        <span class="custom-modal-close"
                                            onclick="closeModal('modal-riwayat-{{ $hasil->id }}')">&times;</span>
                                        <h4 style="text-align: center; font-size: large; padding: 1rem;">
                                            Riwayat Tes RMIB -
                                            {{ $hasil->karirDataDiri->nama ?? $hasil->karirDataDiri->nim }}
                                        </h4>

                                        @php
                                            $riwayatTes = $hasil->karirDataDiri->hasilTes->sortByDesc(
                                                'tanggal_pengerjaan',
                                            );
                                        @endphp

                                        @forelse ($riwayatTes as $riwayat)
                                            <div
                                                style="margin-bottom: 18px; padding: 1rem; background-color: #f9fafb; border-radius: 0.5rem;">
                                                <div style="margin-bottom: 12px;">
                                                    <strong>
                                                        <i class="fas fa-calendar-alt"></i> Tanggal:
                                                    </strong>
                                                    {{ \Carbon\Carbon::parse($riwayat->tanggal_pengerjaan)->locale('id')->setTimezone('Asia/Jakarta')->translatedFormat('l, d M Y - H:i') }}
                                                </div>

                                                <div style="margin-bottom: 8px;">
                                                    <strong>
                                                        <i class="fas fa-trophy"></i> Top 1:
                                                    </strong>
                                                    <span
                                                        class="badge bg-success">{{ $kategoriRMIB[$riwayat->top_1_pekerjaan] ?? ($riwayat->top_1_pekerjaan ?? '-') }}</span>
                                                </div>

                                                <div style="margin-bottom: 8px;">
                                                    <strong>
                                                        <i class="fas fa-medal"></i> Top 2:
                                                    </strong>
                                                    <span
                                                        class="badge bg-info">{{ $kategoriRMIB[$riwayat->top_2_pekerjaan] ?? ($riwayat->top_2_pekerjaan ?? '-') }}</span>
                                                </div>

                                                <div style="margin-bottom: 8px;">
                                                    <strong>
                                                        <i class="fas fa-award"></i> Top 3:
                                                    </strong>
                                                    <span
                                                        class="badge bg-warning">{{ $kategoriRMIB[$riwayat->top_3_pekerjaan] ?? ($riwayat->top_3_pekerjaan ?? '-') }}</span>
                                                </div>

                                                @if ($riwayat->interpretasi)
                                                    <div style="margin-top: 12px;">
                                                        <strong>
                                                            <i class="fas fa-info-circle"></i> Interpretasi:
                                                        </strong>
                                                        {{ $riwayat->interpretasi }}
                                                    </div>
                                                @endif
                                            </div>
                                        @empty
                                            <p style="text-align: center; color: #6b7280;">Tidak ada riwayat tes.</p>
                                        @endforelse
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="15" class="text-center">
                                        @if (request()->has('search') && request('search'))
                                            <span class="text-danger">Tidak ditemukan hasil untuk:
                                                <strong>{{ request('search') }}</strong></span>
                                        @else
                                            Belum ada data hasil tes.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="pagination">
                    {{ $hasilTes->links('vendor.pagination.default') }}
                </div>
                <div class="button-control">
                    {{-- TOMBOL EXPORT EXCEL --}}
                    <form action="{{ route('admin.karir.export.excel') }}" method="GET" style="display: inline;">
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
<script>
    // Reset filters
    function resetFilters() {
        window.location.href = "{{ route('admin.karir.index') }}";
    }

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

    // ============================================
    // ANIMASI DONUT CHART
    // ============================================
    document.addEventListener("DOMContentLoaded", function() {
        // Ambil semua segmen donut
        const donutSegments = document.querySelectorAll(".donut-seg");

        // Fungsi untuk menganimasikan donut chart
        function animateDonutChart() {
            donutSegments.forEach((seg) => {
                const dash = parseFloat(seg.getAttribute("data-dash")) || 0;
                const gap = parseFloat(seg.getAttribute("data-gap")) || 0;
                const offset = parseFloat(seg.getAttribute("data-offset")) || 0;

                // Set stroke-dasharray dan stroke-dashoffset untuk animasi
                setTimeout(() => {
                    seg.style.strokeDasharray = `${dash} ${gap}`;
                    seg.style.strokeDashoffset = -offset;
                }, 100); // Delay sedikit untuk trigger transition
            });
        }

        // Jalankan animasi saat halaman dimuat
        animateDonutChart();
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</html>
