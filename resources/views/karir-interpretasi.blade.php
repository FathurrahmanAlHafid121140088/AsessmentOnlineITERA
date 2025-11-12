<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Interpretasi | Tes Peminatan Karir</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.ico') }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
    <!-- Core theme CSS (includes Bootstrap)-->
    @vite(['resources/css/app-public.css'])
    <!-- AOS Library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <link href="{{ asset('css/karir-interpretasi.css') }}" rel="stylesheet">

</head>

<body id="page-top" style="background-color: #ffffff; background-image: url('{{ asset('assets/bg.svg') }}');">
    <x-navbar></x-navbar>

    <!-- Header Section -->
    <div class="header-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-12 text-center">
                    <h1 class="fw-bold mb-2">Interpretasi Tes RMIB</h1>
                    <p class="lead mb-0">Rothwell Miller Interest Blank</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-4">
        <!-- Profile Section -->
        <div class="row mb-4 animated-item fade-in">
            <div class="col-md-12">
                <div class="card profile-card border border-2">
                    <div class="profile-header">
                        <h5 class="mb-0"><i class="fas fa-user-circle me-2"></i> Profil Peserta</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4 fw-bold">Nama :</div>
                                    <div class="col-md-8"> {{ $hasil->dataDiri->nama }}</div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-4 fw-bold">NIM :</div>
                                    <div class="col-md-8"> {{ $hasil->dataDiri->nim }}</div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-4 fw-bold">Program Studi :</div>
                                    <div class="col-md-8"> {{ $hasil->dataDiri->program_studi }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 fw-bold">Jenis Kelamin :</div>
                                    <div class="col-md-7">
                                        {{ $hasil->dataDiri->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-5 fw-bold">Tanggal Tes :</div>
                                    <div class="col-md-7">
                                        {{ \Carbon\Carbon::parse($hasil->tanggal_pengerjaan)->format('d F Y') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Score Results Section -->
        <div class="card animated-item fade-in">
            <div class="card-header">
                <h4 class="mb-0"><i class="fas fa-chart-bar me-2"></i> Hasil Total Skor Kategori</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-header">
                            <tr>
                                <th width="15%">RANK</th>
                                <th width="60%">KATEGORI</th>
                                <th width="25%">SKOR</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $maxSkor = max(array_column($hasilLengkap, 'skor'));
                            @endphp
                            @foreach ($hasilLengkap as $item)
                                <tr class="animated-item fade-in">
                                    <td><span
                                            style="font-weight: 700; font-size: 1.1rem;">{{ $item['peringkat'] == floor($item['peringkat']) ? number_format($item['peringkat'], 0) : number_format($item['peringkat'], 1) }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $item['kategori'] }}</strong>
                                        <strong class="text-muted">({{ $item['singkatan'] }})</strong>
                                    </td>
                                    <td>
                                        @php
                                            $persentase = $maxSkor > 0 ? ($item['skor'] / $maxSkor) * 100 : 0;
                                            // Warna berdasarkan peringkat
                                            if ($item['peringkat'] <= 3) {
                                                $color = '#419D78'; // hijau untuk top 3
                                            } elseif ($item['peringkat'] <= 9) {
                                                $color = '#3CAEA3'; // biru untuk middle
                                            } else {
                                                $color = '#dc3545'; // merah untuk bottom
                                            }
                                        @endphp
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: {{ $persentase }}%; background-color: {{ $color }};"
                                                aria-valuenow="{{ $item['skor'] }}" aria-valuemin="0"
                                                aria-valuemax="{{ $maxSkor }}">{{ $item['skor'] }}</div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Top Category Interpretation Section -->
        <div class="card mt-4 animated-item fade-in">
            <div class="card-header">
                <h4 class="mb-0"><i class="fas fa-star me-2"></i> Interpretasi 3 Skor Kategori Tertinggi</h4>
            </div>
            <div class="card-body">
                <!-- Info Alert -->
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Cara Membaca Skor:</strong>
                    Semakin <strong>kecil nilai skor</strong> yang didapat, semakin <strong>akurat dan tinggi
                        minat</strong> Anda terhadap kategori pekerjaan tersebut.
                    Skor rendah menunjukkan bahwa Anda memberikan peringkat tinggi (1-3) pada pekerjaan-pekerjaan dalam
                    kategori tersebut.
                    @php
                        // Cek apakah ada tied ranks di top 3
                        $hasTiedRanks = false;
                        if (count($top3) >= 2) {
                            for ($i = 0; $i < count($top3) - 1; $i++) {
                                if ($top3[$i]['peringkat'] == $top3[$i + 1]['peringkat']) {
                                    $hasTiedRanks = true;
                                    break;
                                }
                            }
                        }
                    @endphp
                    @if ($hasTiedRanks)
                        <hr class="my-2">
                        <small><i class="fas fa-info-circle me-1"></i><strong>Catatan:</strong> Terdapat kategori dengan
                            peringkat yang sama. Urutan ditampilkan berdasarkan nama kategori (alfabetis).</small>
                    @endif
                </div>

                <!-- Bar Chart Visualization -->
                <div class="row justify-content-center mb-3">
                    <div class="col-md-10">
                        <div class="bar-chart d-flex justify-content-center align-items-end">
                            @foreach ($top3 as $index => $item)
                                @php
                                    $heights = [140, 160, 170];
                                    $colors = ['#419D78', '#3CAEA3', '#67C8FF'];
                                    $maxTop3 = max(array_column($top3, 'skor'));
                                    $persen = $maxTop3 > 0 ? round(($item['skor'] / $maxTop3) * 100, 1) : 0;
                                @endphp
                                <div class="mx-3 text-center animated-item fade-in">
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="score-value mb-2 fw-bold">{{ $item['skor'] }}</div>
                                        <div class="bar"
                                            style="height: {{ $heights[$index] }}px; background-color: {{ $colors[$index] }};">
                                        </div>
                                        <div class="bar-label" style="width: 80px;">{{ $item['singkatan'] }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Interpretations List -->
                <div class="mt-3">
                    @foreach ($top3 as $item)
                        <div class="interpretation-item animated-item fade-in">
                            <div class="d-flex align-items-center mb-3">
                                <span class="interpretation-label">{{ $item['singkatan'] }}</span>
                                <h5 class="mb-0">{{ $item['nama'] }}</h5>
                            </div>
                            <p class="mb-0">{{ $item['deskripsi'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Career Match Section -->
        <div class="card mt-4 animated-item fade-in">
            <div class="card-header">
                <h4 class="mb-0"><i class="fas fa-briefcase me-2"></i> Rekomendasi Pekerjaan Berdasarkan Minat
                    Tertinggi</h4>
            </div>
            <div class="card-body">
                <p class="mb-4 lead">
                    Berdasarkan hasil tes RMIB Anda, berikut adalah daftar pekerjaan yang sesuai dengan 3 kategori minat
                    tertinggi Anda:
                </p>

                <div class="row">
                    @php
                        $colors = ['success', 'info', 'primary'];
                        $icons = ['fa-star', 'fa-medal', 'fa-award'];
                    @endphp
                    @foreach ($pekerjaanTop3 as $index => $kategoriPekerjaan)
                        <div class="col-md-4 mb-3 animated-item fade-in">
                            <div class="card h-100 border-{{ $colors[$index] }}"
                                style="border-width: 2px !important;">
                                <div class="card-header text-white bg-{{ $colors[$index] }}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">{{ $kategoriPekerjaan['singkatan'] }}</h5>
                                        <span class="badge bg-white text-{{ $colors[$index] }}">
                                            <i class="fas {{ $icons[$index] }}"></i> Rank {{ $index + 1 }}
                                        </span>
                                    </div>
                                    <p class="mb-0 small">{{ $kategoriPekerjaan['nama'] }}</p>
                                </div>
                                <div class="card-body">
                                    <h6 class="fw-bold mb-3">Pekerjaan yang Cocok:</h6>
                                    <ul class="list-unstyled">
                                        @foreach ($kategoriPekerjaan['pekerjaan'] as $pekerjaan)
                                            <li class="mb-2">
                                                <i
                                                    class="fas fa-check-circle text-{{ $colors[$index] }} me-2"></i>{{ $pekerjaan }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pilihan Pekerjaan User -->
                <div class="mt-4">
                    <h5 class="mb-3"><i class="fas fa-user-check me-2"></i>Pekerjaan yang Anda Pilih</h5>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <div class="alert alert-success mb-0">
                                <strong>Pilihan 1:</strong> {{ $hasil->top_1_pekerjaan }}
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="alert alert-success mb-0">
                                <strong>Pilihan 2:</strong> {{ $hasil->top_2_pekerjaan }}
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="alert alert-success mb-0">
                                <strong>Pilihan 3:</strong> {{ $hasil->top_3_pekerjaan }}
                            </div>
                        </div>
                    </div>
                </div>

                @if ($hasil->pekerjaan_lain)
                    <div class="mt-3">
                        <div class="alert alert-info">
                            <strong><i class="fas fa-plus-circle me-2"></i>Pekerjaan Lain yang Anda Minati:</strong>
                            {{ $hasil->pekerjaan_lain }}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Additional Career Insights Section -->
        <div class="card mt-4 animated-item fade-in">
            <div class="card-header">
                <h4 class="mb-0"><i class="fas fa-eye me-2"></i> Wawasan Karir Tambahan</h4>
            </div>
            <div class="card-body">
                @php
                    // Mapping kekuatan dan area pengembangan berdasarkan kategori
                    $insightsMap = [
                        'Outdoor' => [
                            'kekuatan' => [
                                'Kecintaan terhadap alam dan lingkungan',
                                'Kemampuan beradaptasi dengan kondisi lapangan',
                                'Ketahanan fisik dan mental yang baik',
                                'Kemandirian dan kemampuan bekerja di lingkungan terbuka',
                            ],
                            'pengembangan' => [
                                'Keterampilan administratif dan clerical',
                                'Kemampuan bekerja di lingkungan indoor yang terstruktur',
                                'Keterampilan teknologi komputer',
                                'Kemampuan analisis data dan perhitungan',
                            ],
                        ],
                        'Mechanical' => [
                            'kekuatan' => [
                                'Kemampuan memahami cara kerja mesin dan alat',
                                'Keterampilan hands-on dan troubleshooting',
                                'Pemikiran logis dan sistematis',
                                'Kemampuan perbaikan dan maintenance peralatan',
                            ],
                            'pengembangan' => [
                                'Keterampilan interpersonal dan komunikasi',
                                'Kemampuan artistik dan estetika',
                                'Keterampilan menulis dan literasi',
                                'Kepekaan sosial dan empati',
                            ],
                        ],
                        'Computational' => [
                            'kekuatan' => [
                                'Kemampuan analisis numerik dan matematis',
                                'Ketelitian dalam bekerja dengan data dan angka',
                                'Pemikiran logis dan terstruktur',
                                'Kemampuan problem-solving berbasis data',
                            ],
                            'pengembangan' => [
                                'Keterampilan komunikasi interpersonal',
                                'Kreativitas dan inovasi',
                                'Kemampuan bekerja di lingkungan outdoor',
                                'Keterampilan praktis manual',
                            ],
                        ],
                        'Scientific' => [
                            'kekuatan' => [
                                'Keingintahuan tinggi dan sikap analitis',
                                'Kemampuan penelitian dan eksperimen',
                                'Pemikiran kritis dan sistematis',
                                'Kemampuan observasi dan pemecahan masalah ilmiah',
                            ],
                            'pengembangan' => [
                                'Keterampilan interpersonal dan networking',
                                'Kemampuan persuasi dan penjualan',
                                'Keterampilan artistik dan estetika',
                                'Kemampuan bekerja di bidang administrasi',
                            ],
                        ],
                        'Personal Contact' => [
                            'kekuatan' => [
                                'Kemampuan komunikasi dan interpersonal yang baik',
                                'Empati dan pemahaman terhadap orang lain',
                                'Kemampuan membangun relasi dan networking',
                                'Kepercayaan diri dalam berinteraksi dengan banyak orang',
                            ],
                            'pengembangan' => [
                                'Keterampilan teknis dan analitis',
                                'Kemampuan bekerja secara mandiri',
                                'Keterampilan penelitian dan riset mendalam',
                                'Kemampuan bekerja dengan mesin dan teknologi',
                            ],
                        ],
                        'Aesthetic' => [
                            'kekuatan' => [
                                'Kepekaan terhadap keindahan dan estetika',
                                'Kreativitas dan inovasi dalam desain',
                                'Kemampuan ekspresi visual dan artistik',
                                'Apresiasi tinggi terhadap seni dan budaya',
                            ],
                            'pengembangan' => [
                                'Keterampilan matematis dan komputasional',
                                'Kemampuan teknis dan mekanis',
                                'Keterampilan analisis data',
                                'Kemampuan bekerja dengan sistem terstruktur',
                            ],
                        ],
                        'Literary' => [
                            'kekuatan' => [
                                'Kemampuan menulis dan mengekspresikan ide dengan kata-kata',
                                'Apresiasi terhadap bahasa dan literatur',
                                'Kemampuan komunikasi tertulis yang baik',
                                'Kreativitas dalam penyampaian narasi dan cerita',
                            ],
                            'pengembangan' => [
                                'Keterampilan numerik dan matematis',
                                'Kemampuan teknis dan mekanis',
                                'Keterampilan praktis hands-on',
                                'Kemampuan bekerja dengan alat dan mesin',
                            ],
                        ],
                        'Musical' => [
                            'kekuatan' => [
                                'Kepekaan terhadap ritme, harmoni, dan melodi',
                                'Kemampuan ekspresi melalui musik',
                                'Apresiasi tinggi terhadap seni musikal',
                                'Kreativitas dalam menciptakan atau menginterpretasi musik',
                            ],
                            'pengembangan' => [
                                'Keterampilan administratif dan clerical',
                                'Kemampuan analisis data dan statistik',
                                'Keterampilan teknis non-musikal',
                                'Kemampuan bekerja di bidang outdoor',
                            ],
                        ],
                        'Social Service' => [
                            'kekuatan' => [
                                'Kepedulian tinggi terhadap kesejahteraan orang lain',
                                'Empati dan kemampuan memahami masalah sosial',
                                'Kemampuan membantu dan memberdayakan orang lain',
                                'Komitmen terhadap keadilan sosial dan kemanusiaan',
                            ],
                            'pengembangan' => [
                                'Keterampilan teknis dan mekanis',
                                'Kemampuan bekerja dengan angka dan data',
                                'Keterampilan praktis manual',
                                'Kemampuan bekerja di lingkungan kompetitif',
                            ],
                        ],
                        'Clerical' => [
                            'kekuatan' => [
                                'Kemampuan organisasi dan manajemen informasi',
                                'Ketelitian dan perhatian pada detail',
                                'Keterampilan administratif yang baik',
                                'Kemampuan bekerja dengan sistem terstruktur',
                            ],
                            'pengembangan' => [
                                'Kreativitas dan inovasi',
                                'Keterampilan interpersonal dan networking',
                                'Kemampuan bekerja di lingkungan dinamis',
                                'Keterampilan outdoor dan petualangan',
                            ],
                        ],
                        'Practical' => [
                            'kekuatan' => [
                                'Keterampilan hands-on dan praktis',
                                'Kemampuan menghasilkan karya nyata dan konkret',
                                'Keahlian dalam pekerjaan manual',
                                'Kemampuan melihat hasil langsung dari pekerjaan',
                            ],
                            'pengembangan' => [
                                'Keterampilan teoritis dan konseptual',
                                'Kemampuan analisis dan riset',
                                'Keterampilan komunikasi dan presentasi',
                                'Kemampuan bekerja dengan abstraksi',
                            ],
                        ],
                        'Medical' => [
                            'kekuatan' => [
                                'Kepedulian terhadap kesehatan dan kesejahteraan orang lain',
                                'Kemampuan empati dan compassion',
                                'Minat pada ilmu kesehatan dan kedokteran',
                                'Kesiapan membantu dalam situasi kritis',
                            ],
                            'pengembangan' => [
                                'Keterampilan administratif non-medis',
                                'Kemampuan bekerja di bidang kreatif',
                                'Keterampilan mekanis dan teknologi',
                                'Kemampuan bekerja di lingkungan outdoor',
                            ],
                        ],
                    ];

                    // Ambil kategori top 1
                    $topKategori = $top3[0]['kategori'];
                    $insights = $insightsMap[$topKategori] ?? [
                        'kekuatan' => ['Kemampuan sesuai minat Anda'],
                        'pengembangan' => ['Area yang dapat dikembangkan'],
                    ];
                @endphp

                <div class="alert alert-info mb-4">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Berdasarkan kategori minat tertinggi Anda: {{ $top3[0]['kategori'] }}
                        ({{ $top3[0]['singkatan'] }})</strong>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h5 class="text-success"><i class="fas fa-plus-circle me-2"></i>Kekuatan Anda</h5>
                        <ul class="list-unstyled">
                            @foreach ($insights['kekuatan'] as $kekuatan)
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ $kekuatan }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h5 class="text-warning"><i class="fas fa-exclamation-triangle me-2"></i>Area Pengembangan
                        </h5>
                        <ul class="list-unstyled">
                            @foreach ($insights['pengembangan'] as $area)
                                <li class="mb-2"><i
                                        class="fas fa-arrow-up text-warning me-2"></i>{{ $area }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="text-center mt-4 mb-4">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="https://wa.me/6281234567890?text=Halo,%20saya%20ingin%20konsultasi%20mengenai%20hasil%20tes%20peminatan%20karir%20saya"
                            target="_blank" class="btn btn-primary btn-action">
                            <i class="fab fa-whatsapp me-2"></i>Hubungi Konselor
                        </a>
                        <a href="{{ url('/') }}" class="btn btn-primary btn-action">
                            <i class="fas fa-home me-2"></i>Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <x-footer></x-footer>

    <!-- Custom Styles -->
    <style>
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .bar-chart {
                flex-direction: column;
                height: auto;
                align-items: center !important;
            }

            .bar-chart .mx-4 {
                margin: 10px 0 !important;
            }

            .bar {
                width: 200px;
                height: 40px !important;
                border-radius: 20px;
            }

            .bar-label {
                width: auto !important;
                padding: 5px 15px;
                margin-top: 5px;
            }
        }
    </style>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <!-- AOS Animation Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>

    <!-- Custom JavaScript -->
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });

        // Add smooth scrolling animation for elements
        document.addEventListener('DOMContentLoaded', function() {
            // Trigger fade-in animations
            const animatedItems = document.querySelectorAll('.animated-item');
            animatedItems.forEach((item, index) => {
                setTimeout(() => {
                    item.classList.add('fade-in');
                }, index * 100);
            });

            // Add ripple effect for buttons
            const buttons = document.querySelectorAll('.btn-action');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    // Add ripple effect
                    const ripple = document.createElement('span');
                    ripple.classList.add('ripple');
                    this.appendChild(ripple);

                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });

            // Add hover effects for cards
            const cards = document.querySelectorAll('.card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transition = 'transform 0.3s ease, box-shadow 0.3s ease';
                });
            });

            // Progress bar animation
            const progressBars = document.querySelectorAll('.progress-bar');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const progressBar = entry.target;
                        const width = progressBar.style.width;
                        progressBar.style.width = '0%';
                        setTimeout(() => {
                            progressBar.style.transition = 'width 1s ease-in-out';
                            progressBar.style.width = width;
                        }, 200);
                    }
                });
            });

            progressBars.forEach(bar => observer.observe(bar));
        });

        // Add ripple effect CSS
        const style = document.createElement('style');
        style.textContent = `
            .ripple {
                position: absolute;
                border-radius: 50%;
                background-color: rgba(255, 255, 255, 0.6);
                transform: scale(0);
                animation: ripple-animation 0.6s linear;
                pointer-events: none;
            }

            @keyframes ripple-animation {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>

    <!-- Core theme JS-->
    <script src="{{ asset('js/script.js') }}"></script>
</body>

</html>
