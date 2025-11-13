<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Detail Jawaban Kuesioner Mental Health" />
    <meta name="author" content="" />
    <title>{{ $title }}</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.ico') }}" />
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />

    <!-- jsPDF Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Core theme CSS (includes Bootstrap + Navbar + Sidebar)-->
    @vite(['resources/css/app-admin-dashboard.css', 'resources/css/app-admin-detail.css', 'resources/js/admin-detail.js'])

    <style>
        /* Container */
        .detail-container {
            max-width: 1100px;
            margin: 2rem auto;
            padding: 1.5rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        /* Header Actions */
        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.7rem 1.3rem;
            border: none;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-back {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
            color: white;
        }

        .btn-back:hover {
            background: linear-gradient(135deg, #4b5563 0%, #374151 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-print {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
        }

        .btn-print:hover {
            background: linear-gradient(135deg, #047857 0%, #065f46 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
        }

        /* Title Section */
        .title-section {
            text-align: center;
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 50%, #1e3a8a 100%);
            border-radius: 12px;
            color: white;
        }

        .title-section h2 {
            color: white;
            margin-bottom: 0.5rem;
            font-size: 1.75rem;
        }

        .title-section .date-info {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.95rem;
        }

        /* Info Cards */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .info-card {
            padding: 1.2rem;
            background: white;
            border-radius: 12px;
            border-left: 4px solid #3b82f6;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .info-card .icon {
            color: #3b82f6;
            margin-right: 0.5rem;
            font-size: 1.1rem;
        }

        .info-card .label {
            font-size: 0.85rem;
            color: #6b7280;
            margin-bottom: 0.3rem;
        }

        .info-card .value {
            font-size: 1.05rem;
            color: #1f2937;
            font-weight: 600;
        }

        /* Summary Cards */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .summary-card {
            padding: 1.5rem;
            border-radius: 15px;
            text-align: center;
            color: white;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .summary-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.15);
        }

        .summary-card.total {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        }

        .summary-card.positive {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
        }

        .summary-card.negative {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        }

        /* Kategori dinamis sesuai dengan dashboard user */
        .summary-card.category-intensif {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
        }

        .summary-card.category-support {
            background: linear-gradient(135deg, #ea580c, #c2410c);
        }

        .summary-card.category-moderate {
            background: linear-gradient(135deg, #ca8a04, #a16207);
        }

        .summary-card.category-good {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
        }

        .summary-card.category-excellent {
            background: linear-gradient(135deg, #059669, #047857);
        }

        .summary-card .icon {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            opacity: 0.9;
        }

        .summary-card h3 {
            font-size: 2rem;
            margin: 0.5rem 0;
            font-weight: 700;
        }

        .summary-card p {
            margin: 0;
            font-size: 0.9rem;
            opacity: 0.9;
        }

        /* Questions Section */
        .questions-header {
            margin: 2rem 0 1rem 0;
            padding: 1rem 1.2rem;
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            border-radius: 10px;
            border-left: 5px solid #3b82f6;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .questions-header h3 {
            margin: 0;
            color: #1f2937;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
        }

        .question-item {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .question-item:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            border-color: #3b82f6;
            transform: translateY(-2px);
        }

        .question-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .question-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-weight: 700;
            font-size: 0.9rem;
            box-shadow: 0 2px 6px rgba(59, 130, 246, 0.3);
        }

        .question-type-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.4rem 0.85rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        }

        .badge-positive {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
        }

        .badge-negative {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
        }

        .question-text {
            color: #374151;
            font-size: 0.95rem;
            line-height: 1.6;
            margin: 0.5rem 0 1rem 0;
        }

        .answer-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.2rem;
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
            border-radius: 10px;
            border: 2px solid #e5e7eb;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .answer-label {
            font-weight: 600;
            color: #4b5563;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
        }

        .answer-label i {
            color: #3b82f6;
        }

        .answer-score {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .score-badge {
            font-size: 1.6rem;
            font-weight: 700;
            padding: 0.6rem 1.2rem;
            border-radius: 10px;
            background: white;
            border: 3px solid;
            min-width: 70px;
            text-align: center;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .question-item.positive .score-badge {
            color: #059669;
            border-color: #059669;
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        }

        .question-item.negative .score-badge {
            color: #dc2626;
            border-color: #dc2626;
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        }

        /* Print Styles */
        @media print {
            body * {
                visibility: hidden;
            }

            .detail-container,
            .detail-container * {
                visibility: visible;
            }

            .detail-container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .btn-action,
            .header-actions,
            header,
            .sidebar {
                display: none !important;
            }

            .question-item {
                page-break-inside: avoid;
            }
        }

        /* No Print Class */
        .no-print {
            display: block;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .detail-container {
                padding: 1rem;
            }

            .summary-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="hamburger" id="hamburger">
            <i class="fas fa-bars"></i>
        </div>
        <div class="header-title" style="color: white">
            <h2>Detail Jawaban Kuesioner</h2>
        </div>
        <div class="user-wrapper">
            <div class="account-group">
                <button class="account-toggle">
                    <i class="fas fa-user-circle"></i>
                    <span class="account-username">{{ Auth::guard('admin')->user()->username }}</span>
                    <i class="fas fa-caret-down caret"></i>
                </button>

                <div class="account-dropdown">
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
                <img src="{{ asset('assets/img/Logo_ITERA.png') }}" class="img-fluid animated" alt="">
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
            <div class="detail-container">
                <!-- Header Actions -->
                <div class="header-actions no-print">
                    <a href="{{ route('admin.home') }}" class="btn-action btn-back">
                        <i class="fas fa-arrow-left"></i>
                        Kembali
                    </a>
                    <button onclick="printDetail()" class="btn-action btn-print">
                        <i class="fas fa-print"></i>
                        Cetak PDF
                    </button>
                </div>

                <!-- Title Section -->
                <div class="title-section">
                    <h2><i class="fas fa-file-alt"></i> Detail Jawaban Kuesioner</h2>
                    <p class="date-info">
                        <i class="fas fa-calendar-alt"></i>
                        {{ \Carbon\Carbon::parse($hasil->created_at)->locale('id')->setTimezone('Asia/Jakarta')->translatedFormat('l, d F Y - H:i') }}
                        WIB
                    </p>
                </div>

                <!-- Info Cards -->
                <div class="info-grid">
                    <div class="info-card">
                        <div class="label">
                            <i class="fas fa-id-card icon"></i> NIM
                        </div>
                        <div class="value">{{ $hasil->nim }}</div>
                    </div>

                    <div class="info-card">
                        <div class="label">
                            <i class="fas fa-user icon"></i> Nama Mahasiswa
                        </div>
                        <div class="value">{{ $hasil->dataDiri->nama ?? 'Tidak Ada Data' }}</div>
                    </div>

                    <div class="info-card">
                        <div class="label">
                            <i class="fas fa-graduation-cap icon"></i> Program Studi
                        </div>
                        <div class="value">{{ $hasil->dataDiri->program_studi ?? 'Tidak Ada Data' }}</div>
                    </div>

                    <div class="info-card">
                        <div class="label">
                            <i class="fas fa-calendar-check icon"></i> Tanggal Tes
                        </div>
                        <div class="value">
                            {{ \Carbon\Carbon::parse($hasil->created_at)->locale('id')->setTimezone('Asia/Jakarta')->translatedFormat('d F Y') }}
                        </div>
                    </div>
                </div>

                <!-- Keluhan Section -->
                @if($hasil->riwayatKeluhans->isNotEmpty())
                <div style="margin-bottom: 1.5rem;">
                    <!-- Header Keluhan -->
                    <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
                                padding: 0.75rem 1.2rem;
                                border-radius: 10px 10px 0 0;
                                margin-bottom: 0;">
                        <h4 style="margin: 0;
                                   color: white;
                                   font-size: 1rem;
                                   font-weight: 600;
                                   display: flex;
                                   align-items: center;
                                   gap: 0.5rem;">
                            <i class="fas fa-notes-medical"></i>
                            Informasi Keluhan
                        </h4>
                    </div>

                    <!-- Content Keluhan -->
                    <div style="background: white;
                                border: 2px solid #f59e0b;
                                border-top: none;
                                border-radius: 0 0 12px 12px;
                                padding: 1.5rem;
                                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
                        <div style="display: grid;
                                    grid-template-columns: 1fr;
                                    gap: 1.2rem;">
                            <!-- Keluhan Text -->
                            <div>
                                <div style="display: flex;
                                            align-items: center;
                                            gap: 0.5rem;
                                            margin-bottom: 0.5rem;">
                                    <i class="fas fa-exclamation-circle" style="color: #f59e0b; font-size: 1rem;"></i>
                                    <span style="font-size: 0.85rem;
                                                 color: #6b7280;
                                                 font-weight: 600;
                                                 text-transform: uppercase;
                                                 letter-spacing: 0.5px;">
                                        Keluhan
                                    </span>
                                </div>
                                <div style="font-size: 1rem;
                                            color: #1f2937;
                                            line-height: 1.6;
                                            padding: 0.75rem 1rem;
                                            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
                                            border-left: 4px solid #f59e0b;
                                            border-radius: 6px;">
                                    {{ $hasil->riwayatKeluhans->first()->keluhan ?? 'Tidak ada keluhan' }}
                                </div>
                            </div>

                            <!-- Lama Keluhan -->
                            <div>
                                <div style="display: flex;
                                            align-items: center;
                                            gap: 0.5rem;
                                            margin-bottom: 0.5rem;">
                                    <i class="fas fa-clock" style="color: #f59e0b; font-size: 1rem;"></i>
                                    <span style="font-size: 0.85rem;
                                                 color: #6b7280;
                                                 font-weight: 600;
                                                 text-transform: uppercase;
                                                 letter-spacing: 0.5px;">
                                        Lama Keluhan
                                    </span>
                                </div>
                                <div style="font-size: 1.1rem;
                                            color: #1f2937;
                                            font-weight: 700;
                                            padding: 0.75rem 1rem;
                                            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
                                            border-left: 4px solid #f59e0b;
                                            border-radius: 6px;
                                            display: inline-block;
                                            min-width: 150px;">
                                    @php
                                        $lamaKeluhanValue = $hasil->riwayatKeluhans->first()->lama_keluhan ?? '-';
                                        // Tambahkan 'Bulan' jika belum ada kata 'bulan' (case-insensitive)
                                        if ($lamaKeluhanValue !== '-' && !preg_match('/bulan/i', $lamaKeluhanValue)) {
                                            $lamaKeluhanValue .= ' Bulan';
                                        }
                                    @endphp
                                    <i class="fas fa-hourglass-half" style="color: #f59e0b; margin-right: 0.5rem;"></i>
                                    {{ $lamaKeluhanValue }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Summary Cards -->
                <div class="summary-grid">
                    <div class="summary-card total">
                        <div class="icon"><i class="fas fa-chart-line"></i></div>
                        <h3>{{ $hasil->total_skor }}</h3>
                        <p>Total Skor</p>
                    </div>

                    <div class="summary-card positive">
                        <div class="icon"><i class="fas fa-smile"></i></div>
                        <h3>{{ $hasil->jawabanDetails->whereNotIn('nomor_soal', $negativeQuestions)->count() }}</h3>
                        <p>Pertanyaan Positif</p>
                    </div>

                    <div class="summary-card negative">
                        <div class="icon"><i class="fas fa-frown"></i></div>
                        <h3>{{ $hasil->jawabanDetails->whereIn('nomor_soal', $negativeQuestions)->count() }}</h3>
                        <p>Pertanyaan Negatif</p>
                    </div>

                    @php
                        // Icon dan class sesuai dengan dashboard user
                        $kategoriIcon = match ($hasil->kategori) {
                            'Perlu Dukungan Intensif' => 'fas fa-frown',
                            'Perlu Dukungan' => 'fas fa-meh',
                            'Cukup Sehat' => 'fas fa-smile',
                            'Sehat' => 'fas fa-thumbs-up',
                            'Sangat Sehat' => 'fas fa-star',
                            default => 'fas fa-heartbeat',
                        };

                        $kategoriClass = match ($hasil->kategori) {
                            'Perlu Dukungan Intensif' => 'category-intensif',
                            'Perlu Dukungan' => 'category-support',
                            'Cukup Sehat' => 'category-moderate',
                            'Sehat' => 'category-good',
                            'Sangat Sehat' => 'category-excellent',
                            default => 'category-good',
                        };
                    @endphp
                    <div class="summary-card {{ $kategoriClass }}">
                        <div class="icon"><i class="{{ $kategoriIcon }}"></i></div>
                        <h3 style="font-size: 1.2rem;">{{ $hasil->kategori }}</h3>
                        <p>Kategori Mental Health</p>
                    </div>
                </div>

                <!-- Questions Header -->
                <div class="questions-header">
                    <h3>
                        <i class="fas fa-list-ol"></i>
                        Detail Jawaban Per Pertanyaan ({{ $hasil->jawabanDetails->count() }} Pertanyaan)
                    </h3>
                </div>

                <!-- Questions List -->
                @foreach ($hasil->jawabanDetails->sortBy('nomor_soal') as $jawaban)
                    @php
                        $isNegative = in_array($jawaban->nomor_soal, $negativeQuestions);
                        $questionClass = $isNegative ? 'negative' : 'positive';
                    @endphp
                    <div class="question-item {{ $questionClass }}">
                        <div class="question-header">
                            <span class="question-number">
                                <i class="fas fa-hashtag"></i> {{ $jawaban->nomor_soal }}
                            </span>
                            <span class="question-type-badge {{ $isNegative ? 'badge-negative' : 'badge-positive' }}">
                                <i class="fas {{ $isNegative ? 'fa-exclamation-triangle' : 'fa-heart' }}"></i>
                                {{ $isNegative ? 'Negatif' : 'Positif' }}
                            </span>
                        </div>

                        <p class="question-text">
                            <i class="fas fa-quote-left" style="color: #d1d5db; font-size: 0.8rem;"></i>
                            {{ $questions[$jawaban->nomor_soal] ?? 'Pertanyaan tidak ditemukan' }}
                            <i class="fas fa-quote-right" style="color: #d1d5db; font-size: 0.8rem;"></i>
                        </p>

                        <div class="answer-box">
                            <span class="answer-label">
                                <i class="fas fa-check-circle"></i>
                                Jawaban
                            </span>
                            <div class="answer-score">
                                <span style="color: #6b7280; font-weight: 600;">Skor:</span>
                                <span class="score-badge">{{ $jawaban->skor }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <x-footer></x-footer>

    <script src="{{ asset('js/script-admin-mh.js') }}"></script>

    <script>
        // Function to print detail as PDF
        function printDetail() {
            try {
                Swal.fire({
                    title: 'Mencetak PDF...',
                    html: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Check if jsPDF is loaded
                if (typeof window.jspdf === 'undefined') {
                    throw new Error('jsPDF library not loaded');
                }

                // Use jsPDF for better PDF generation
                const {
                    jsPDF
                } = window.jspdf;
                const doc = new jsPDF('p', 'mm', 'a4');

                // Data dari Blade
                const nama = "{{ $hasil->dataDiri->nama ?? 'Tidak Ada Data' }}";
                const nim = "{{ $hasil->nim }}";
                const prodi = "{{ $hasil->dataDiri->program_studi ?? 'Tidak Ada Data' }}";
                const tanggalTes =
                    "{{ \Carbon\Carbon::parse($hasil->created_at)->locale('id')->setTimezone('Asia/Jakarta')->translatedFormat('l, d F Y') }}";
                const totalSkor = "{{ $hasil->total_skor }}";
                const kategori = "{{ $hasil->kategori }}";
                const tanggal =
                    "{{ \Carbon\Carbon::parse($hasil->created_at)->locale('id')->setTimezone('Asia/Jakarta')->translatedFormat('l, d F Y - H:i') }}";
                const keluhan = "{{ $hasil->riwayatKeluhans->first()->keluhan ?? 'Tidak ada keluhan' }}";
                @php
                    $lamaKeluhanPDF = $hasil->riwayatKeluhans->first()->lama_keluhan ?? '-';
                    // Tambahkan 'Bulan' jika belum ada kata 'bulan' (case-insensitive)
                    if ($lamaKeluhanPDF !== '-' && !preg_match('/bulan/i', $lamaKeluhanPDF)) {
                        $lamaKeluhanPDF .= ' Bulan';
                    }
                @endphp
                const lamaKeluhan = "{{ $lamaKeluhanPDF }}";

                let yPos = 20;

                // Header
                doc.setFontSize(18);
                doc.setFont(undefined, 'bold');
                doc.text('Hasil Test Mental Health RMHI-38', 105, yPos, {
                    align: 'center'
                });

                yPos += 7;
                doc.setFontSize(10);
                doc.setFont(undefined, 'normal');
                doc.text(tanggal + ' WIB', 105, yPos, {
                    align: 'center'
                });

                yPos += 10;
                doc.setLineWidth(0.5);
                doc.line(20, yPos, 190, yPos);

                yPos += 8;

                // Info Mahasiswa
                doc.setFontSize(11);
                doc.setFont(undefined, 'bold');
                doc.text('Informasi Mahasiswa', 20, yPos);
                yPos += 7;

                doc.setFontSize(10);
                doc.setFont(undefined, 'normal');
                // Menggunakan bullet point yang kompatibel
                doc.text('\u2022 NIM: ' + nim, 20, yPos);
                yPos += 6;
                doc.text('\u2022 Nama: ' + nama, 20, yPos);
                yPos += 6;
                doc.text('\u2022 Program Studi: ' + prodi, 20, yPos);
                yPos += 6;
                doc.text('\u2022 Tanggal Tes: ' + tanggalTes, 20, yPos);
                yPos += 10;

                // Summary
                doc.setFontSize(11);
                doc.setFont(undefined, 'bold');
                doc.text('Ringkasan Hasil', 20, yPos);
                yPos += 7;

                doc.setFontSize(10);
                doc.setFont(undefined, 'normal');
                doc.text('\u2022 Total Skor: ' + totalSkor, 20, yPos);
                yPos += 6;
                doc.text('\u2022 Kategori: ' + kategori, 20, yPos);
                yPos += 6;

                // Keluhan (dengan text wrapping untuk teks panjang)
                const keluhanLines = doc.splitTextToSize('\u2022 Keluhan: ' + keluhan, 170);
                doc.text(keluhanLines, 20, yPos);
                yPos += (keluhanLines.length * 6);

                doc.text('\u2022 Lama Keluhan: ' + lamaKeluhan, 20, yPos);
                yPos += 10;

                // Prepare table data
                const tableData = [];
                @foreach ($hasil->jawabanDetails->sortBy('nomor_soal') as $jawaban)
                    @php
                        $isNegative = in_array($jawaban->nomor_soal, $negativeQuestions);
                        $type = $isNegative ? 'Negatif' : 'Positif';
                        $questionText = $questions[$jawaban->nomor_soal] ?? 'Pertanyaan tidak ditemukan';
                        // Escape karakter khusus untuk JavaScript
                        $questionText = addslashes($questionText);
                    @endphp
                    tableData.push([
                        '{{ $jawaban->nomor_soal }}',
                        '{{ $type }}',
                        {!! json_encode($questionText) !!},
                        '{{ $jawaban->skor }}'
                    ]);
                @endforeach

                // Add table
                // Hitung margin kiri-kanan agar tabel berada di tengah
                // Lebar halaman A4 = 210mm, Total lebar kolom = 165mm (12+20+118+15)
                // Margin kiri-kanan = (210 - 165) / 2 = 22.5mm
                const tableWidth = 165; // Total lebar kolom
                const pageWidth = 210; // Lebar halaman A4
                const marginLeftRight = (pageWidth - tableWidth) / 2;

                doc.autoTable({
                    startY: yPos,
                    head: [
                        ['No.', 'Tipe', 'Pertanyaan', 'Skor']
                    ],
                    body: tableData,
                    theme: 'grid',
                    headStyles: {
                        fillColor: [59, 130, 246],
                        textColor: 255,
                        fontSize: 9,
                        fontStyle: 'bold',
                        halign: 'center', // Semua header rata tengah
                        valign: 'middle'
                    },
                    bodyStyles: {
                        fontSize: 8,
                        cellPadding: 3
                    },
                    columnStyles: {
                        0: {
                            cellWidth: 12,
                            halign: 'center'
                        },
                        1: {
                            cellWidth: 20,
                            halign: 'center'
                        },
                        2: {
                            cellWidth: 118
                        },
                        3: {
                            cellWidth: 15,
                            halign: 'center'
                        }
                    },
                    didParseCell: function(data) {
                        if (data.section === 'body' && data.column.index === 1) {
                            if (data.cell.raw === 'Positif') {
                                data.cell.styles.textColor = [5, 150, 105];
                                data.cell.styles.fontStyle = 'bold';
                            } else if (data.cell.raw === 'Negatif') {
                                data.cell.styles.textColor = [220, 38, 38];
                                data.cell.styles.fontStyle = 'bold';
                            }
                        }
                        if (data.section === 'body' && data.column.index === 3) {
                            data.cell.styles.fontStyle = 'bold';
                            data.cell.styles.fontSize = 9;
                        }
                    },
                    margin: {
                        left: marginLeftRight,
                        right: marginLeftRight
                    }
                });

                // Tambahkan watermark di semua halaman (kanan bawah)
                const pageCount = doc.internal.getNumberOfPages();
                const pdfPageWidth = doc.internal.pageSize.getWidth();
                const pageHeight = doc.internal.pageSize.getHeight();

                // Generate timestamp saat cetak
                const now = new Date();
                const tanggalCetak = now.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                });
                const waktuCetak = now.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false
                });
                const timestampCetak = `${tanggalCetak} - ${waktuCetak} WIB`;

                for (let i = 1; i <= pageCount; i++) {
                    doc.setPage(i);

                    // Watermark teks kecil di kanan bawah
                    doc.setFontSize(8);
                    doc.setTextColor(150, 150, 150); // Abu-abu
                    doc.setFont(undefined, 'italic');

                    // Baris 1: Generated by ANALOGY - ITERA (5mm dari bawah)
                    doc.text('Generated by ANALOGY - ITERA', pdfPageWidth - 10, pageHeight - 10, {
                        align: 'right'
                    });

                    // Baris 2: Timestamp cetak (2mm di bawah baris 1)
                    doc.text(`Dicetak: ${timestampCetak}`, pdfPageWidth - 10, pageHeight - 5, {
                        align: 'right'
                    });
                }

                // Save PDF
                doc.save('Detail-Jawaban-' + nim + '-' + new Date().getTime() + '.pdf');

                Swal.close();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'PDF berhasil dicetak',
                    timer: 2000,
                    showConfirmButton: false
                });
            } catch (error) {
                console.error('Error generating PDF:', error);
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat mencetak PDF: ' + error.message,
                    confirmButtonText: 'OK'
                });
            }
        }
    </script>
</body>

</html>
