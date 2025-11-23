<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Rincian Hasil Tes</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.ico') }}" />
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
    <!-- AOS Library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <link href="{{ asset('css/karir-admin.css') }}" rel="stylesheet">
</head>

<body>
    <!-- Elemen khusus print - pojok kanan atas -->
    <div class="print-doc-title">Rincian Hasil Tes</div>

    <div class="main-content">
        <div class="header">
            <h1>Hasil Tes RMIB</h1>
        </div>

        <div class="content-area">
            <div class="tabs">
                <div class="tab active">Detail Hasil</div>
                <a href="{{ route('admin.karir.list-pekerjaan', $hasil->id) }}" class="tab" style="text-decoration: none; color: inherit;">
                    List Pekerjaan
                </a>
            </div>

            <div class="peserta-info">
                    <h3>Informasi Peserta</h3>
                    <div class="peserta-info-grid">
                        <div class="info-item">
                            <span class="info-label">Nama </span>
                            <span class="info-value">{{ $hasil->karirDataDiri->nama ?? 'Tidak Ada Data' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Email</span>
                            <span class="info-value">{{ $hasil->karirDataDiri->email ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Tanggal Tes</span>
                            <span
                                class="info-value">{{ $hasil->tanggal_pengerjaan ? \Carbon\Carbon::parse($hasil->tanggal_pengerjaan)->locale('id')->translatedFormat('d F Y') : '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Usia</span>
                            <span class="info-value">{{ $hasil->karirDataDiri->usia ?? '-' }} Tahun</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Program Studi</span>
                            <span
                                class="info-value">{{ $hasil->karirDataDiri->program_studi ?? 'Tidak Ada Data' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Jenis Kelamin</span>
                            <span class="info-value">
                                @if($hasil->karirDataDiri->jenis_kelamin == 'L')
                                    Laki-laki
                                @elseif($hasil->karirDataDiri->jenis_kelamin == 'P')
                                    Perempuan
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <div class="peserta-info" style="margin-top: 20px;">
                    <h3><i class="fas fa-star" style="margin-right: 10px; color: #ffc107;"></i> Top 3 Pekerjaan Pilihan
                        Peserta</h3>

                    <div class="peserta-info-grid">
                        <div class="info-item">
                            <span class="info-label">Pilihan 1</span>
                            <span class="info-value">
                                @if ($top1Pekerjaan)
                                    {{ $top1Pekerjaan }}
                                    @if ($top1Kategori)
                                        <span style="color: #000; font-weight: bold;"> | {{ $top1Kategori }}</span>
                                    @endif
                                @else
                                    <span style="color: #999;">Belum diisi</span>
                                @endif
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Pilihan 2</span>
                            <span class="info-value">
                                @if ($top2Pekerjaan)
                                    {{ $top2Pekerjaan }}
                                    @if ($top2Kategori)
                                        <span style="color: #000; font-weight: bold;"> | {{ $top2Kategori }}</span>
                                    @endif
                                @else
                                    <span style="color: #999;">Belum diisi</span>
                                @endif
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Pilihan 3</span>
                            <span class="info-value">
                                @if ($top3Pekerjaan)
                                    {{ $top3Pekerjaan }}
                                    @if ($top3Kategori)
                                        <span style="color: #000; font-weight: bold;"> | {{ $top3Kategori }}</span>
                                    @endif
                                @else
                                    <span style="color: #999;">Belum diisi</span>
                                @endif
                            </span>
                        </div>
                        @if ($hasil->pekerjaan_lain)
                            <div class="info-item">
                                <span class="info-label">Pekerjaan Lain yang Diminati</span>
                                <span class="info-value">{{ $hasil->pekerjaan_lain }}</span>
                            </div>
                        @endif
                    </div>

                </div>

                <div class="result-section">
                    <h2><i class="fas fa-calculator" style="margin-right: 10px"></i> Perhitungan Hasil Tes RMIB</h2>

                    <div class="result-info">
                        Berikut adalah hasil tes minat karir berdasarkan metode Rothwell-Miller Interest Blank (RMIB)
                        untuk
                        <strong>{{ $hasil->karirDataDiri->nama ?? 'Peserta' }}</strong>. Kategori dengan ranking
                        terendah menunjukkan bidang minat
                        yang paling dominan (semakin rendah ranking, semakin tinggi minat).
                    </div>

                    <div class="chart-container" id="chartContainer"></div>

                    <div class="rmib-table-wrapper">
                        <h2 class="print-table-header">Tabel Perhitungan RMIB - {{ $hasil->karirDataDiri->nama ?? 'Peserta' }}</h2>
                        <table class="rmib-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>KATEGORI</th>
                                <th>A</th>
                                <th>B</th>
                                <th>C</th>
                                <th>D</th>
                                <th>E</th>
                                <th>F</th>
                                <th>G</th>
                                <th>H</th>
                                <th>I</th>
                                <th>SUM</th>
                                <th>RANK</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($hasilLengkap as $index => $item)
                                @php
                                    // Tentukan class untuk cell RANK berdasarkan peringkat
                                    $rankClass = '';
                                    if ($item['rank'] == 1) {
                                        $rankClass = 'rank-1';
                                    } elseif ($item['rank'] == 2) {
                                        $rankClass = 'rank-2';
                                    } elseif ($item['rank'] == 3) {
                                        $rankClass = 'rank-3';
                                    }

                                    // Posisi diagonal (home position) hanya untuk kategori 1-9 (OUT sampai S.S)
                                    // Kategori 10-12 (CLER, PRAC, MED) tidak ada indikator diagonal
                                    $diagonalKlusterIndex = $index < 9 ? $index : -1;
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="kategori-column">{{ $item['singkatan'] }}
                                        ({{ $item['kategori'] }})</td>
                                    @foreach ($matrixData['kluster_urutan'] as $klusterIndex => $kluster)
                                        @php
                                            // Cek apakah cell ini adalah posisi diagonal (home position)
                                            $isDiagonal = ($klusterIndex == $diagonalKlusterIndex);
                                            $cellClass = $isDiagonal ? 'cell-diagonal' : '';
                                        @endphp
                                        <td class="{{ $cellClass }}">{{ $item['matrix_row'][$kluster] }}</td>
                                    @endforeach
                                    <td>{{ $item['sum'] }}</td>
                                    <td class="{{ $rankClass }}">{{ $item['rank'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        </table>
                    </div>

                    <div class="action-buttons">
                        <a href="{{ route('admin.karir.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                        </a>
                        <button class="btn btn-primary" onclick="cetakHasil()">
                            <i class="fas fa-print"></i> Cetak Hasil
                        </button>
                    </div>
        </div>
    </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Data RMIB dari backend
                const labels = [
                    @foreach ($hasilLengkap as $item)
                        '{{ $item['kategori'] }} ({{ $item['singkatan'] }})'
                        {{ !$loop->last ? ',' : '' }}
                    @endforeach
                ];

                const sumScores = [
                    @foreach ($hasilLengkap as $item)
                        {{ $item['sum'] }}{{ !$loop->last ? ',' : '' }}
                    @endforeach
                ];

                const ranks = [
                    @foreach ($hasilLengkap as $item)
                        {{ $item['rank'] }}{{ !$loop->last ? ',' : '' }}
                    @endforeach
                ];

                // Membuat chart
                const ctx = document.createElement('canvas');
                document.getElementById('chartContainer').appendChild(ctx);

                // Warna bar: top 3 lebih gelap, sisanya lebih terang
                const backgroundColors = [
                    @foreach ($hasilLengkap as $item)
                        @php
                            $opacity = $item['rank'] <= 3 ? 1 : 0.7;
                            if ($item['rank'] == 1) {
                                $opacity = 1;
                            } elseif ($item['rank'] == 2) {
                                $opacity = 0.9;
                            } elseif ($item['rank'] == 3) {
                                $opacity = 0.9;
                            }
                        @endphp
                            'rgba(67, 97, 238, {{ $opacity }})'
                        {{ !$loop->last ? ',' : '' }}
                    @endforeach
                ];

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Skor Total (SUM)',
                            data: sumScores,
                            backgroundColor: backgroundColors,
                            borderColor: 'rgba(67, 97, 238, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        indexAxis: 'y', // Horizontal bar chart
                        scales: {
                            x: {
                                beginAtZero: true,
                                max: 120, // Skala 0-120
                                title: {
                                    display: true,
                                    text: 'Skor Total (SUM) - Skala 0-120'
                                },
                                ticks: {
                                    stepSize: 10
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'Kategori Minat'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const index = context.dataIndex;
                                        return [
                                            'Skor Total: ' + context.raw,
                                            'Ranking: ' + ranks[index],
                                            '(Skor rendah = Minat tinggi)'
                                        ];
                                    }
                                }
                            }
                        }
                    }
                });

                // Toggle sidebar di mobile view (jika ada)
                const hamburger = document.querySelector('.fa-bars');
                if (hamburger) {
                    hamburger.addEventListener('click', function() {
                        const sidebar = document.querySelector('.sidebar');
                        if (sidebar) {
                            sidebar.classList.toggle('show');
                        }
                    });
                }
            });

            // Fungsi untuk cetak hasil dengan chart yang benar
            function cetakHasil() {
                // Ambil canvas chart
                const chartContainer = document.getElementById('chartContainer');
                const canvas = chartContainer.querySelector('canvas');

                if (canvas) {
                    // Konversi canvas ke image untuk print
                    const imageUrl = canvas.toDataURL('image/png', 1.0);

                    // Buat image element
                    const img = document.createElement('img');
                    img.src = imageUrl;
                    img.style.width = '100%';
                    img.style.maxWidth = '100%';
                    img.style.height = 'auto';
                    img.className = 'chart-image-print';

                    // Simpan canvas asli dan sembunyikan saat print
                    canvas.style.display = 'none';
                    canvas.classList.add('canvas-original');

                    // Tambahkan image ke container
                    chartContainer.appendChild(img);

                    // Cetak setelah image dimuat
                    img.onload = function() {
                        window.print();

                        // Kembalikan canvas dan hapus image setelah print
                        setTimeout(function() {
                            canvas.style.display = 'block';
                            img.remove();
                        }, 1000);
                    };
                } else {
                    // Jika tidak ada canvas, langsung print
                    window.print();
                }
            }
        </script>
</body>

</html>
