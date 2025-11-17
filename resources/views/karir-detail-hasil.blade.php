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

                    @if ($hasil->top_1_alasan || $hasil->top_2_alasan || $hasil->top_3_alasan || $hasil->pekerjaan_lain_alasan)
                        <div style="margin-top: 15px;">
                            <h4 style="font-size: 14px; color: #666; margin-bottom: 10px;">Alasan Pemilihan:</h4>
                            @if ($hasil->top_1_alasan)
                                <div style="margin-bottom: 10px;">
                                    <strong style="color: #4361ee;">Pilihan 1:</strong>
                                    <p style="margin: 5px 0 0 0; color: #555;">{{ $hasil->top_1_alasan }}</p>
                                </div>
                            @endif
                            @if ($hasil->top_2_alasan)
                                <div style="margin-bottom: 10px;">
                                    <strong style="color: #4361ee;">Pilihan 2:</strong>
                                    <p style="margin: 5px 0 0 0; color: #555;">{{ $hasil->top_2_alasan }}</p>
                                </div>
                            @endif
                            @if ($hasil->top_3_alasan)
                                <div style="margin-bottom: 10px;">
                                    <strong style="color: #4361ee;">Pilihan 3:</strong>
                                    <p style="margin: 5px 0 0 0; color: #555;">{{ $hasil->top_3_alasan }}</p>
                                </div>
                            @endif
                            @if ($hasil->pekerjaan_lain_alasan)
                                <div style="margin-bottom: 10px;">
                                    <strong style="color: #4361ee;">Pekerjaan Lain:</strong>
                                    <p style="margin: 5px 0 0 0; color: #555;">{{ $hasil->pekerjaan_lain_alasan }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
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

                    <table class="rmib-table">
                        <thead>
                            <tr>
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
                                    // Tentukan apakah row ini adalah top 3
                                    $isTop3 = $item['rank'] <= 3;
                                    $isTop1 = $item['rank'] == 1;
                                    $rowClass = '';
                                    if ($isTop1) {
                                        $rowClass = 'highlight top-rank';
                                    } elseif ($isTop3) {
                                        $rowClass = 'top-rank';
                                    }
                                @endphp
                                <tr class="{{ $rowClass }}">
                                    <td class="kategori-column">{{ $index + 1 }}. {{ $item['singkatan'] }}
                                        ({{ $item['kategori'] }})</td>
                                    @foreach ($matrixData['kluster_urutan'] as $kluster)
                                        <td>{{ $item['matrix_row'][$kluster] }}</td>
                                    @endforeach
                                    <td>{{ $item['sum'] }}</td>
                                    <td>{{ $item['rank'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Validitas Hasil Tes -->
                    <div class="result-info" style="margin-top: 30px; margin-bottom: 20px;">
                        @php
                            $skorKonsistensi = $hasil->skor_konsistensi ?? 0;
                            $isValid = $skorKonsistensi >= 7;
                        @endphp

                        <h3 style="margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                            <i class="fas {{ $isValid ? 'fa-check-circle' : 'fa-exclamation-triangle' }}"
                               style="color: {{ $isValid ? '#06d6a0' : '#ef476f' }};"></i>
                            Validitas Hasil Tes
                        </h3>

                        <div style="background-color: {{ $isValid ? '#d1fae5' : '#fee2e2' }};
                                    border-left: 4px solid {{ $isValid ? '#06d6a0' : '#ef476f' }};
                                    padding: 15px;
                                    border-radius: 4px;
                                    margin-bottom: 15px;">
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                                <strong style="font-size: 1.1rem;">Skor Konsistensi: {{ $skorKonsistensi }}/10</strong>
                                <span style="padding: 4px 12px;
                                             background-color: {{ $isValid ? '#06d6a0' : '#ef476f' }};
                                             color: white;
                                             border-radius: 20px;
                                             font-size: 0.85rem;
                                             font-weight: bold;">
                                    {{ $isValid ? 'VALID' : 'TIDAK VALID' }}
                                </span>
                            </div>

                            <p style="margin: 10px 0 0 0; color: #555; line-height: 1.6;">
                                @if ($isValid)
                                    <strong style="color: #059669;">✓ Hasil tes ini VALID dan dapat dipercaya.</strong><br>
                                    Peserta menjawab dengan pola yang <strong>konsisten</strong> sepanjang tes.
                                    Skor konsistensi <strong>≥ 7</strong> menunjukkan bahwa peserta memberikan jawaban yang
                                    logis dan selaras antar kelompok pekerjaan. Hasil ini mencerminkan minat karir yang sesungguhnya
                                    dan dapat digunakan sebagai dasar konseling atau pengambilan keputusan karir.
                                @else
                                    <strong style="color: #dc2626;">⚠ Hasil tes ini TIDAK VALID dan perlu dievaluasi ulang.</strong><br>
                                    Peserta menjawab dengan pola yang <strong>tidak konsisten</strong> sepanjang tes.
                                    Skor konsistensi <strong>&lt; 7</strong> menunjukkan adanya ketidaksesuaian atau kontradiksi
                                    dalam jawaban antar kelompok pekerjaan. Hal ini bisa disebabkan oleh:
                                    <ul style="margin: 10px 0 0 20px; color: #555;">
                                        <li>Peserta tidak serius atau asal-asalan dalam mengisi tes</li>
                                        <li>Peserta kurang memahami instruksi pengerjaan</li>
                                        <li>Peserta terburu-buru atau tidak fokus saat mengerjakan</li>
                                        <li>Peserta mengalami kebingungan dalam menentukan preferensi</li>
                                    </ul>
                                    <strong style="color: #dc2626; display: block; margin-top: 10px;">
                                        Rekomendasi: Peserta disarankan untuk mengulang tes dengan lebih teliti dan fokus.
                                    </strong>
                                @endif
                            </p>
                        </div>

                        <div style="background-color: #fff7ed;
                                    border-left: 4px solid #f59e0b;
                                    padding: 15px;
                                    border-radius: 4px;">
                            <h4 style="margin: 0 0 10px 0; color: #92400e; font-size: 1rem;">
                                <i class="fas fa-info-circle"></i> Tentang Skor Konsistensi
                            </h4>
                            <p style="margin: 0; color: #78350f; font-size: 0.9rem; line-height: 1.6;">
                                Skor konsistensi mengukur seberapa <strong>konsisten</strong> peserta dalam memberikan peringkat
                                pada pekerjaan-pekerjaan yang sejenis di berbagai kelompok. Tes RMIB memiliki pekerjaan dari
                                12 kategori yang tersebar di 9 kelompok. Jika peserta konsisten, maka pekerjaan dari kategori
                                yang sama akan mendapat peringkat yang relatif sama di semua kelompok. Skor konsistensi dihitung
                                dalam skala 0-10, dengan threshold validitas <strong>≥ 7</strong>.
                            </p>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <a href="{{ route('admin.karir.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                        </a>
                        <button class="btn btn-primary" onclick="window.print()">
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
        </script>
</body>

</html>
