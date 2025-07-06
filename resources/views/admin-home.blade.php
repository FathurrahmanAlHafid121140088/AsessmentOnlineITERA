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
    <script src="https://cdn.jsdelivr.net/npm/@srexi/purecounterjs@1.5.0/dist/purecounter_vanilla.js"></script>
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
            @if (Auth::guard('admin')->check())
                <div class="user-info">
                    <i class="fas fa-user-circle"></i>
                    <span>{{ Auth::guard('admin')->user()->username }}</span>
                </div>
                <div class="logout-button">
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="logout-btn">
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
                <li>
                    <a href="#"><i class="fas fa-home"></i> Home</a>
                </li>
                <li class="active">
                    <a href="/admin"><i class="fas fa-brain"></i> Mental Health</a>
                </li>
                <li>
                    <a href="/admin-karir"><i class="fas fa-briefcase"></i> Peminatan Karir</a>
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
                        <h3>Kategori Mental Health</h3>
                        <div class="bar-container">
                            @php
                                $max = max($kategoriCounts ?: [1]);
                                $warnaKategori = [
                                    'Sangat Baik (Sejahtera Secara Mental)' => '#4caf50',
                                    'Baik (Sehat Secara Mental)' => '#2196f3',
                                    'Sedang (Rentan)' => '#ffc107',
                                    'Buruk (Distres Sedang)' => '#ff9800',
                                    'Sangat Buruk (Distres Berat)' => '#f44336',
                                ];
                                $urutanKategori = [
                                    'Sangat Buruk (Distres Berat)',
                                    'Buruk (Distres Sedang)',
                                    'Sedang (Rentan)',
                                    'Baik (Sehat Secara Mental)',
                                    'Sangat Baik (Sejahtera Secara Mental)',
                                ];
                            @endphp

                            @foreach ($urutanKategori as $kategori)
                                @php
                                    $jumlah = $kategoriCounts[$kategori] ?? 0;
                                    $heightPx = $jumlah > 0 ? round(($jumlah / $max) * 160) : 5; // sedikit lebih pendek
                                    $color = $warnaKategori[$kategori] ?? '#999';
                                @endphp
                                <div class="bar-segment">
                                    <div class="bar-value">{{ $jumlah }} org</div>
                                    <div class="bar-fill"
                                        style="height: {{ $heightPx }}px; background-color: {{ $color }};">
                                    </div>
                                    <div class="bar-label">{{ $kategori }}</div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                    <div class="chart">
                        <div class="chart-header">
                            <h3>Fakultas</h3>
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
                                        {{ $singkatan }} ({{ $count }} orang, {{ $persen }}%)
                                    </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tables">
                <div class="table">
                    <div class="table-header">
                        <h3>Aktivitas Terbaru</h3>
                        <div class="table-controls">
                            <form method="GET" action="{{ route('admin.home') }}" class="limit-form">
                                <label for="limit">Tampilkan:</label>
                                <select name="limit" id="limit" onchange="this.form.submit()">
                                    @foreach ([10, 25, 50, 100, 200] as $l)
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
                    <table id="assessmentTable">
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
                                    <td>{{ \Carbon\Carbon::parse($hasil->created_at)->setTimezone('Asia/Jakarta')->translatedFormat('l, d M Y - H:i') }}
                                    </td>
                                    <td>
                                        <div class="action-buttons"
                                            style="display: flex; gap: 10px; align-items: center; justify-content: center;">
                                            <button type="button"
                                                onclick="openModal('modal-riwayat-{{ $hasil->id }}')"
                                                class="print-button">
                                                <i class="fas fa-history"></i>
                                            </button>
                                            <button type="button" class="print-button"
                                                style="background-color: green" onclick="printPDF(this)"
                                                dusk="print-button-{{ $hasil->id }}">
                                                <svg class="svgIcon" viewBox="0 0 384 512" height="1em"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M169.4 470.6c12.5 12.5 32.8 12.5 45.3 0l160-160c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L224 370.8 224 64c0-17.7-14.3-32-32-32s-32 14.3-32 32v306.7L54.6 265.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l160 160z" />
                                                </svg>
                                                <span class="tooltip">Print PDF</span>
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
                                <div id="modal-riwayat-{{ $hasil->id }}" class="custom-modal">
                                    <div class="custom-modal-content">
                                        <span class="custom-modal-close"
                                            onclick="closeModal('modal-riwayat-{{ $hasil->id }}')">&times;</span>
                                        <h4 style="text-align: center; font-size: large; padding: 1rem;">Riwayat
                                            Keluhan & Hasil - {{ $hasil->dataDiri->nama ?? $hasil->nim }}</h4>
                                        @php
                                            $merged = collect();
                                            foreach ($hasil->dataDiri->hasilKuesioners as $kuesioner) {
                                                $key = \Carbon\Carbon::parse($kuesioner->created_at)->format(
                                                    'Y-m-d H:i',
                                                );
                                                $merged->push([
                                                    'group_key' => $key,
                                                    'type' => 'kuesioner',
                                                    'created_at' => $kuesioner->created_at,
                                                    'data' => $kuesioner,
                                                ]);
                                            }
                                            foreach ($hasil->dataDiri->riwayatKeluhans as $keluhan) {
                                                $key = \Carbon\Carbon::parse($keluhan->created_at)->format('Y-m-d H:i');
                                                $merged->push([
                                                    'group_key' => $key,
                                                    'type' => 'keluhan',
                                                    'created_at' => $keluhan->created_at,
                                                    'data' => $keluhan,
                                                ]);
                                            }

                                            $grouped = $merged->sortByDesc('created_at')->groupBy('group_key');
                                        @endphp

                                        @forelse ($grouped as $timestamp => $entries)
                                            <div style="margin-bottom: 10px;">
                                                <strong>Tanggal:</strong>
                                                {{ \Carbon\Carbon::parse($entries->first()['created_at'])->setTimezone('Asia/Jakarta')->translatedFormat('l, d M Y - H:i') }}
                                                <br>
                                                @foreach ($entries as $entry)
                                                    @if ($entry['type'] === 'kuesioner')
                                                        <strong>Skor Total:</strong>
                                                        {{ $entry['data']->total_skor }}<br>
                                                        <strong>Kategori:</strong>
                                                        {{ $entry['data']->kategori }}<br>
                                                    @elseif ($entry['type'] === 'keluhan')
                                                        <strong>Keluhan:</strong> {{ $entry['data']->keluhan }}<br>
                                                        <strong>Lama Keluhan:</strong>
                                                        {{ $entry['data']->lama_keluhan }}<br>
                                                        <strong>Pernah Tes:</strong>
                                                        {{ $entry['data']->pernah_tes }}<br>
                                                        <strong>Pernah Konsul:</strong>
                                                        {{ $entry['data']->pernah_konsul }}<br>
                                                    @endif
                                                @endforeach
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
                    <div class="pagination">
                        {{ $hasilKuesioners->links('vendor.pagination.default') }} </div>
                    <button class="btn-pdf" onclick="generatePDF()">
                        <i class="fas fa-file-pdf"></i> Cetak PDF
                    </button>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script src="{{ asset('js/script-admin.js') }}"></script>
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
</body>

</html>
