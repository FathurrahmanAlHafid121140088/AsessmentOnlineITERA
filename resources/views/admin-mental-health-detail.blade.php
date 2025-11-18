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
    @vite(['resources/css/app-admin-dashboard.css', 'resources/css/app-admin-detail.css', 'resources/css/admin-mental-health-detail.css', 'resources/js/admin-detail.js'])
</head>

<body>
    <header>
        <div class="hamburger" id="hamburger">
            <i class="fas fa-bars"></i>
        </div>
        <div class="header-title" style="color: white">
            <h2>Detail Jawaban Mental Health</h2>
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

                <!-- Dropdown Mental Health - hanya tampil di halaman mental health -->
                @php
                    $isMentalHealthPage = request()->is('admin/mental-health*');
                @endphp

                @if($isMentalHealthPage)
                <li class="dropdown open">
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
                @else
                <li>
                    <a href="/admin/mental-health">
                        <i class="fas fa-brain" style="margin-right: 1rem;"></i> Mental Health
                    </a>
                </li>
                @endif

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
                @if ($hasil->riwayatKeluhans->isNotEmpty())
                    <div class="keluhan-section">
                        <!-- Header Keluhan -->
                        <div class="keluhan-header">
                            <h4>
                                <i class="fas fa-notes-medical"></i>
                                Informasi Keluhan
                            </h4>
                        </div>

                        <!-- Content Keluhan -->
                        <div class="keluhan-content">
                            <div class="keluhan-grid">
                                <!-- Keluhan Text -->
                                <div>
                                    <div class="keluhan-item-label">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <span>Keluhan</span>
                                    </div>
                                    <div class="keluhan-text">
                                        {{ $hasil->riwayatKeluhans->first()->keluhan ?? 'Tidak ada keluhan' }}
                                    </div>
                                </div>

                                <!-- Lama Keluhan -->
                                <div>
                                    <div class="keluhan-item-label">
                                        <i class="fas fa-clock"></i>
                                        <span>Lama Keluhan</span>
                                    </div>
                                    <div class="keluhan-duration">
                                        @php
                                            $lamaKeluhanValue = $hasil->riwayatKeluhans->first()->lama_keluhan ?? '-';
                                            // Tambahkan 'Bulan' jika belum ada kata 'bulan' (case-insensitive)
                                            if (
                                                $lamaKeluhanValue !== '-' &&
                                                !preg_match('/bulan/i', $lamaKeluhanValue)
                                            ) {
                                                $lamaKeluhanValue .= ' Bulan';
                                            }
                                        @endphp
                                        <i class="fas fa-hourglass-half"></i>
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
                        <h3>{{ $hasil->kategori }}</h3>
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
                const nim = "{{ $hasil->nim }}";
                const nama = "{{ $hasil->dataDiri->nama ?? 'Tidak Ada Data' }}";
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

                let yPos = 15;

                // Header
                doc.setFontSize(16);
                doc.setFont(undefined, 'bold');
                doc.text('Hasil Test Mental Health RMHI-38', 105, yPos, {
                    align: 'center'
                });

                yPos += 6;
                doc.setFontSize(10);
                doc.setFont(undefined, 'normal');
                doc.text(tanggal + ' WIB', 105, yPos, {
                    align: 'center'
                });

                yPos += 8;
                doc.setLineWidth(0.5);
                doc.line(20, yPos, 190, yPos);

                yPos += 7;

                // Info Mahasiswa - 2 kolom
                doc.setFontSize(11);
                doc.setFont(undefined, 'bold');
                doc.text('Informasi Mahasiswa', 20, yPos);
                yPos += 6;

                doc.setFontSize(9);
                // Kolom kiri - posisi value sejajar
                const leftLabelX = 20;
                const leftColonX = 40; // Posisi titik dua kolom kiri
                const leftValueX = 43; // Posisi value kolom kiri

                doc.setFont(undefined, 'bold');
                doc.text('\u2022 NIM', leftLabelX, yPos);
                doc.text(':', leftColonX, yPos);
                doc.setFont(undefined, 'normal');
                doc.text(nim, leftValueX, yPos);

                doc.setFont(undefined, 'bold');
                doc.text('\u2022 Nama', leftLabelX, yPos + 5);
                doc.text(':', leftColonX, yPos + 5);
                doc.setFont(undefined, 'normal');
                doc.text(nama, leftValueX, yPos + 5);

                // Kolom kanan - posisi value sejajar
                const rightLabelX = 110; // Posisi X untuk label kolom kanan
                const rightColonX = 145; // Posisi titik dua kolom kanan
                const rightValueX = 148; // Posisi X untuk value kolom kanan

                doc.setFont(undefined, 'bold');
                doc.text('\u2022 Prodi', rightLabelX, yPos);
                doc.text(':', rightColonX, yPos);
                doc.setFont(undefined, 'normal');
                doc.text(prodi, rightValueX, yPos);

                doc.setFont(undefined, 'bold');
                doc.text('\u2022 Tanggal Tes', rightLabelX, yPos + 5);
                doc.text(':', rightColonX, yPos + 5);
                doc.setFont(undefined, 'normal');
                doc.text(tanggalTes, rightValueX, yPos + 5);
                yPos += 12;

                // Summary - format kolom vertikal dengan alignment konsisten
                doc.setFontSize(11);
                doc.setFont(undefined, 'bold');
                doc.text('Ringkasan Hasil', 20, yPos);
                yPos += 6;

                doc.setFontSize(9);
                const summaryLabelX = 20;
                const summaryColonX = 59; // Posisi titik dua ringkasan
                const summaryValueX = 62; // Posisi X untuk semua value di ringkasan

                doc.setFont(undefined, 'bold');
                doc.text('\u2022 Total Skor', summaryLabelX, yPos);
                doc.text(':', summaryColonX, yPos);
                doc.setFont(undefined, 'normal');
                doc.text(String(totalSkor), summaryValueX, yPos);
                yPos += 5;

                doc.setFont(undefined, 'bold');
                doc.text('\u2022 Kategori', summaryLabelX, yPos);
                doc.text(':', summaryColonX, yPos);
                doc.setFont(undefined, 'normal');
                doc.text(kategori, summaryValueX, yPos);
                yPos += 5;

                // Keluhan dengan text wrapping
                doc.setFont(undefined, 'bold');
                doc.text('\u2022 Keluhan', summaryLabelX, yPos);
                doc.text(':', summaryColonX, yPos);
                doc.setFont(undefined, 'normal');
                const keluhanLines = doc.splitTextToSize(keluhan, 132);
                doc.text(keluhanLines, summaryValueX, yPos);
                yPos += (keluhanLines.length * 5);

                doc.setFont(undefined, 'bold');
                doc.text('\u2022 Lama Keluhan', summaryLabelX, yPos);
                doc.text(':', summaryColonX, yPos);
                doc.setFont(undefined, 'normal');
                doc.text(lamaKeluhan, summaryValueX, yPos);
                yPos += 9;

                // Prepare table data - hanya No, Tipe, Skor
                const allData = [];
                @foreach ($hasil->jawabanDetails->sortBy('nomor_soal') as $jawaban)
                    @php
                        $isNegative = in_array($jawaban->nomor_soal, $negativeQuestions);
                        $type = $isNegative ? 'N' : 'P'; // N = Negatif, P = Positif
                    @endphp
                    allData.push({
                        no: '{{ $jawaban->nomor_soal }}',
                        type: '{{ $type }}',
                        skor: '{{ $jawaban->skor }}',
                        isNegative: {{ $isNegative ? 'true' : 'false' }}
                    });
                @endforeach

                // Buat 4 tabel side-by-side (1-10, 11-20, 21-30, 31-38)
                const columnWidth = 43; // Lebar setiap kolom tabel
                const startX = 20;
                const spacing = 2; // Jarak antar kolom

                // Posisi X untuk setiap kolom
                const positions = [
                    startX,
                    startX + columnWidth + spacing,
                    startX + (columnWidth + spacing) * 2,
                    startX + (columnWidth + spacing) * 3
                ];

                // Header untuk semua kolom
                doc.setFontSize(11);
                doc.setFont(undefined, 'bold');
                doc.text('Detail Jawaban Per Pertanyaan', 20, yPos);
                yPos += 7;

                // Buat 4 tabel
                for (let col = 0; col < 4; col++) {
                    const startIndex = col * 10;
                    const endIndex = Math.min(startIndex + 10, allData.length);
                    const tableData = [];

                    for (let i = startIndex; i < endIndex; i++) {
                        tableData.push([
                            allData[i].no,
                            allData[i].type,
                            allData[i].skor
                        ]);
                    }

                    if (tableData.length > 0) {
                        doc.autoTable({
                            startY: yPos,
                            head: [
                                ['No', 'Tipe', 'Skor']
                            ],
                            body: tableData,
                            theme: 'grid',
                            headStyles: {
                                fillColor: [147, 51, 234], // Warna ungu
                                textColor: 255,
                                fontSize: 8,
                                fontStyle: 'bold',
                                halign: 'center',
                                valign: 'middle',
                                cellPadding: 2
                            },
                            bodyStyles: {
                                fontSize: 8,
                                cellPadding: 1.8,
                                halign: 'center',
                                minCellHeight: 5
                            },
                            columnStyles: {
                                0: {
                                    cellWidth: 10
                                },
                                1: {
                                    cellWidth: 13
                                },
                                2: {
                                    cellWidth: 20
                                }
                            },
                            didParseCell: function(data) {
                                if (data.section === 'body' && data.column.index === 1) {
                                    const rowIndex = startIndex + data.row.index;
                                    if (allData[rowIndex].isNegative) {
                                        data.cell.styles.textColor = [220, 38, 38]; // Merah
                                        data.cell.styles.fontStyle = 'bold';
                                    } else {
                                        data.cell.styles.textColor = [5, 150, 105]; // Hijau
                                        data.cell.styles.fontStyle = 'bold';
                                    }
                                }
                                if (data.section === 'body' && data.column.index === 2) {
                                    data.cell.styles.fontStyle = 'bold';
                                }
                            },
                            margin: {
                                left: positions[col]
                            },
                            tableWidth: columnWidth,
                            pageBreak: 'avoid'
                        });
                    }
                }

                // Tambahkan watermark di halaman pertama saja (kanan bawah)
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

                // Watermark teks kecil di kanan bawah
                doc.setFontSize(6);
                doc.setTextColor(150, 150, 150); // Abu-abu
                doc.setFont(undefined, 'italic');

                // Baris 1: Generated by ANALOGY - ITERA (8mm dari bawah)
                doc.text('Generated by ANALOGY - ITERA', pdfPageWidth - 10, pageHeight - 8, {
                    align: 'right'
                });

                // Baris 2: Timestamp cetak (4mm dari bawah)
                doc.text(`Dicetak: ${timestampCetak}`, pdfPageWidth - 10, pageHeight - 4, {
                    align: 'right'
                });

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
