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
    <link href="{{ asset('css/style-hasil-mh.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/styleform.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style-footer.css') }}" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<x-navbar></x-navbar>

<body>
    <header>
        <div data-aos="fade-down" data-aos-delay="100" class="header-icon-circle">
            <i class="fa-solid fa-laptop-medical header-icon"></i>
        </div>
        <h1 data-aos="fade-down" data-aos-delay="200">Hasil Penilaian Kesehatan Mental MHI-38</h1>
        <p data-aos="fade-down" data-aos-delay="300">Berdasarkan jawaban Anda pada kuesioner Mental Health</p>
    </header>


    <div class="container">
        <div data-aos="zoom-in" data-aos-delay="100" class="student-info">
            <h2>Informasi Mahasiswa</h2>
            <div class="student-info-grid">
                <div class="student-info-item">
                    <i class="fas fa-user icon-info"></i>
                    <div>
                        <span class="student-info-label">Nama</span>
                        <span class="student-info-value">{{ session('nama') }}</span>
                    </div>
                </div>
                <div class="student-info-item">
                    <i class="fas fa-id-card icon-info"></i>
                    <div>
                        <span class="student-info-label">NIM</span>
                        <span class="student-info-value">{{ session('nim') }}</span>
                    </div>
                </div>
                <div class="student-info-item">
                    <i class="fas fa-graduation-cap icon-info"></i>
                    <div>
                        <span class="student-info-label">Program Studi</span>
                        <span class="student-info-value">{{ session('program_studi') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="result-section">
            <h2>Ringkasan Hasil</h2>
            <!--
            <div class="score-card">
                <div data-aos="zoom-in" data-aos-delay="200" class="score-item">
                    <div class="score-label">Skor Total</div>
                    <div class="score-value">{{ $hasil->total_skor }}</div>
                    <div class="score-category">{{ $hasil->kategori }}</div>
                </div>
                -->
            <div data-aos="zoom-in" data-aos-delay="300" class="score-item score-range">
                <div class="score-range-title">
                    <i class="fas fa-sliders-h" style="color: #3498db; margin-right: 6px;"></i> Rentang Kategori
                </div>
                <div class="score-wrapper">
                    <div class="score-range-scale">
                        <div
                            class="range-item range-very-poor {{ $hasil->total_skor >= 38 && $hasil->total_skor <= 75 ? 'active' : '' }}">
                            38-75
                        </div>
                        <div
                            class="range-item range-poor {{ $hasil->total_skor >= 76 && $hasil->total_skor <= 113 ? 'active' : '' }}">
                            76-113
                        </div>
                        <div
                            class="range-item range-moderate {{ $hasil->total_skor >= 114 && $hasil->total_skor <= 151 ? 'active' : '' }}">
                            114-151
                        </div>
                        <div
                            class="range-item range-good {{ $hasil->total_skor >= 152 && $hasil->total_skor <= 189 ? 'active' : '' }}">
                            152-189
                        </div>
                        <div
                            class="range-item range-excellent {{ $hasil->total_skor >= 190 && $hasil->total_skor <= 226 ? 'active' : '' }}">
                            190-226
                        </div>
                    </div>
                    <div class="score-range-labels">
                        <div class="score-range-title mobile-only">
                            <i class="fas fa-info-circle" style="color: #3498db; margin-right: 6px;"></i> Keterangan
                        </div>
                        <span
                            class="range-very-poor {{ $hasil->total_skor >= 38 && $hasil->total_skor <= 75 ? 'active' : '' }}">
                            Berat
                        </span>

                        <span
                            class="range-poor {{ $hasil->total_skor >= 76 && $hasil->total_skor <= 113 ? 'active' : '' }}">
                            Sedang
                        </span>

                        <span
                            class="range-moderate {{ $hasil->total_skor >= 114 && $hasil->total_skor <= 151 ? 'active' : '' }}">
                            Rentan
                        </span>

                        <span
                            class="range-good {{ $hasil->total_skor >= 152 && $hasil->total_skor <= 189 ? 'active' : '' }}">
                            Sehat
                        </span>

                        <span
                            class="range-excellent {{ $hasil->total_skor >= 190 && $hasil->total_skor <= 226 ? 'active' : '' }}">
                            Sejahtera
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="result-section category-info">
            <div class="header">
                <h1 class="title">Kategori Mental Health</h1>
                <p class="subtitle">Evaluasi Kondisi Mental Health Berdasarkan Metode MHI-38</p>
            </div>
            <div class="category-list">
                @if ($hasil->total_skor >= 38 && $hasil->total_skor <= 75)
                    <div class="category-item category-very-poor active" data-aos="flip-right" data-aos-delay="50">
                        <div class="floating-particles">
                            <div class="particle" style="left: 20%; animation-delay: 0s;"></div>
                            <div class="particle" style="left: 50%; animation-delay: 1s;"></div>
                            <div class="particle" style="left: 80%; animation-delay: 2s;"></div>
                        </div>

                        <div class="active-badge">
                            <i class="fas fa-check-circle"></i> Status Aktif
                        </div>

                        <div class="card-header">
                            <div class="icon-container">
                                <div class="icon-bg"></div>
                                <div class="category-icon">
                                    <i class="fas fa-frown"></i>
                                </div>
                            </div>
                            <h3 class="category-title">Sangat Buruk</h3>
                            <p class="category-subtitle">Distres Berat</p>
                            <div class="category-range">Skor: 38 - 75</div>
                        </div>
                        <div class="card-body">
                            <div
                                class="category-description {{ $hasil->total_skor >= 38 && $hasil->total_skor <= 75 ? 'active' : 'hidden' }}">
                                Saat ini, kamu mungkin sedang berada dalam fase yang terasa sangat berat. Perasaan
                                seperti cemas berlebih, kehilangan semangat, sulit tidur, atau merasa tidak berdaya bisa
                                saja muncul dan mengganggu aktivitas harian. Tidak apa-apa jika semuanya terasa berat
                                sekarang — yang penting, kamu tidak harus menghadapinya sendirian. Mencari dukungan dari
                                tenaga profesional seperti psikolog atau psikiater bisa menjadi langkah awal yang bijak
                                dan penuh keberanian untuk mulai merasa lebih baik.
                            </div>
                            <div class="health-metrics">
                                <div class="metric">
                                    <div class="metric-icon"><i class="fas fa-head-side-virus"></i></div>
                                    <div class="metric-label">Luangkan waktu untuk beristirahat total, hindari beban
                                        mental yang berat</div>
                                </div>
                                <div class="metric">
                                    <div class="metric-icon"><i class="fas fa-user-md"></i></div>
                                    <div class="metric-label">Jangan ragu untuk berkonsultasi dengan tenaga profesional
                                        seperti psikolog atau konselor</div>
                                </div>
                                <div class="metric">
                                    <div class="metric-icon"><i class="fas fa-phone-alt"></i></div>
                                    <div class="metric-label">Ceritakan kondisi Anda pada orang terpercaya sebagai
                                        langkah awal pemulihan</div>
                                </div>
                            </div>

                            <div class="recommendation">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span>Anda mengalami tekanan psikologis yang serius. Segera temui profesional kesehatan
                                    mental seperti psikolog atau psikiater untuk mendapatkan penanganan intensif.
                                    Menunda bantuan dapat memperburuk kondisi dan menghambat fungsi harian Anda.</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="status-indicator">
                            <div class="status-dot"></div>
                            <span>Kategori Aktif</span>
                        </div>
                        <div class="severity-level">Kritis</div>
                    </div>
            </div>
            @endif
            @if ($hasil->total_skor >= 76 && $hasil->total_skor <= 113)
                <div class="category-item category-poor active" data-aos="flip-right" data-aos-delay="100">
                    <div class="floating-particles">
                        <div class="particle" style="left: 30%; animation-delay: 0.5s;"></div>
                        <div class="particle" style="left: 70%; animation-delay: 1.5s;"></div>
                    </div>

                    <div class="active-badge">
                        <i class="fas fa-check-circle"></i> Status Aktif
                    </div>

                    <div class="card-header">
                        <div class="icon-container">
                            <div class="icon-bg"></div>
                            <div class="category-icon">
                                <i class="fas fa-meh"></i>
                            </div>
                        </div>
                        <h3 class="category-title">Buruk</h3>
                        <p class="category-subtitle">Distres Sedang</p>
                        <div class="category-range">Skor: 76 - 113</div>
                    </div>

                    <div class="card-body">
                        <div
                            class="category-description {{ $hasil->total_skor >= 76 && $hasil->total_skor <= 113 ? 'active' : 'hidden' }}">
                            Kamu mungkin sedang menghadapi tekanan yang cukup terasa, seperti kelelahan emosional,
                            kesulitan tidur, atau perasaan tidak nyaman yang muncul berulang. Ini adalah sinyal bahwa
                            tubuh dan pikiranmu butuh perhatian lebih. Tidak ada yang salah dengan merasa lelah — semua
                            orang bisa mengalaminya. Mengambil waktu untuk mengenali perasaanmu, mencari ruang untuk
                            bercerita, atau mencoba teknik relaksasi bisa sangat membantu untuk mulai menata ulang
                            ketenanganmu.
                        </div>

                        <div class="health-metrics">
                            <div class="metric">
                                <div class="metric-icon"><i class="fas fa-journal-whills"></i></div>
                                <div class="metric-label">Catat perasaan Anda secara rutin untuk memahami pola emosi
                                    yang muncul</div>
                            </div>
                            <div class="metric">
                                <div class="metric-icon"><i class="fas fa-walking"></i></div>
                                <div class="metric-label">Lakukan aktivitas fisik ringan seperti berjalan kaki atau
                                    peregangan untuk meredakan ketegangan</div>
                            </div>
                            <div class="metric">
                                <div class="metric-icon"><i class="fas fa-headset"></i></div>
                                <div class="metric-label">Dengarkan musik menenangkan atau meditasi singkat untuk
                                    menstabilkan suasana hati</div>
                            </div>
                        </div>

                        <div class="recommendation">
                            <i class="fas fa-user-md"></i>
                            <span>Tingkat stres Anda cukup mengganggu dan perlu direspons dengan cepat. Konsultasi
                                dengan tenaga profesional dan mulai lakukan kegiatan pemulihan seperti konseling,
                                mindfulness, atau aktivitas positif yang terstruktur.</span>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="status-indicator">
                            <div class="status-dot"></div>
                            <span>Kategori Aktif</span>
                        </div>
                        <div class="severity-level">Sedang</div>
                    </div>
                </div>
            @endif
            @if ($hasil->total_skor >= 114 && $hasil->total_skor <= 151)
                <div class="category-item category-moderate active" data-aos="flip-right" data-aos-delay="150">
                    <div class="floating-particles">
                        <div class="particle" style="left: 40%; animation-delay: 1s;"></div>
                        <div class="particle" style="left: 60%; animation-delay: 2s;"></div>
                    </div>

                    <div class="active-badge">
                        <i class="fas fa-check-circle"></i> Status Aktif
                    </div>

                    <div class="card-header">
                        <div class="icon-container">
                            <div class="icon-bg"></div>
                            <div class="category-icon">
                                <i class="fas fa-smile"></i>
                            </div>
                        </div>
                        <h3 class="category-title">Sedang</h3>
                        <p class="category-subtitle">Rentan</p>
                        <div class="category-range">Skor: 114 - 151</div>
                    </div>

                    <div class="card-body">
                        <div
                            class="category-description {{ $hasil->total_skor >= 114 && $hasil->total_skor <= 151 ? 'active' : 'hidden' }}">
                            Secara umum, kamu dalam kondisi yang cukup baik, namun mungkin sesekali merasa tertekan atau
                            kurang bersemangat saat menghadapi situasi tertentu. Ini wajar dan bisa dialami siapa pun.
                            Tetap penting untuk menjaga keseimbangan hidup, istirahat yang cukup, waktu untuk diri
                            sendiri, serta tetap terhubung dengan orang-orang yang kamu percayai bisa membuat perbedaan
                            besar dalam menjaga ketahanan emosimu.
                        </div>

                        <div class="health-metrics">
                            <div class="metric">
                                <div class="metric-icon"><i class="fas fa-calendar-check"></i></div>
                                <div class="metric-label">Tentukan jadwal harian yang seimbang antara pekerjaan,
                                    istirahat, dan hiburan</div>
                            </div>
                            <div class="metric">
                                <div class="metric-icon"><i class="fas fa-hand-holding-heart"></i></div>
                                <div class="metric-label">Berikan penghargaan kecil untuk diri sendiri atas hal-hal
                                    yang berhasil dilakukan</div>
                            </div>
                            <div class="metric">
                                <div class="metric-icon"><i class="fas fa-user-friends"></i></div>
                                <div class="metric-label">Jaga komunikasi dengan teman dan keluarga meski hanya melalui
                                    pesan singkat</div>
                            </div>
                        </div>

                        <div class="recommendation">
                            <i class="fas fa-shield-alt"></i>
                            <span>Kondisi Anda relatif stabil namun cenderung rentan terhadap tekanan. Jaga keseimbangan
                                hidup dengan cukup tidur, atur waktu istirahat, hindari tekanan berlebih, dan tetap
                                terhubung secara sosial.</span>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="status-indicator">
                            <div class="status-dot"></div>
                            <span>Kategori Aktif</span>
                        </div>
                        <div class="severity-level">Hati-hati</div>
                    </div>
                </div>
            @endif
            @if ($hasil->total_skor >= 152 && $hasil->total_skor <= 189)
                <div class="category-item category-good active" data-aos="flip-right" data-aos-delay="200">
                    <div class="floating-particles">
                        <div class="particle" style="left: 25%; animation-delay: 0.3s;"></div>
                        <div class="particle" style="left: 75%; animation-delay: 1.3s;"></div>
                    </div>

                    <div class="active-badge">
                        <i class="fas fa-check-circle"></i> Status Aktif
                    </div>

                    <div class="card-header">
                        <div class="icon-container">
                            <div class="icon-bg"></div>
                            <div class="category-icon">
                                <i class="fas fa-thumbs-up"></i>
                            </div>
                        </div>
                        <h3 class="category-title">Baik</h3>
                        <p class="category-subtitle">Sehat Mental</p>
                        <div class="category-range">Skor: 152 - 189</div>
                    </div>

                    <div class="card-body">
                        <div
                            class="category-description {{ $hasil->total_skor >= 152 && $hasil->total_skor <= 189 ? 'active' : 'hidden' }}">
                            Hasil ini menunjukkan bahwa kamu memiliki kemampuan yang baik dalam menghadapi tekanan dan
                            menjaga keseimbangan emosional. Kamu cenderung mampu berpikir jernih, berelasi secara
                            positif, dan menjalani hari-hari dengan cukup stabil. Tetaplah jaga rutinitas sehat ini dan
                            beri ruang untuk tetap tumbuh — baik secara pribadi maupun sosial. Perjalanan menjaga
                            kesehatan mental adalah proses yang terus berkembang.
                        </div>

                        <div class="health-metrics">
                            <div class="metric">
                                <div class="metric-icon"><i class="fas fa-seedling"></i></div>
                                <div class="metric-label">Lanjutkan rutinitas yang mendukung kesehatan mental seperti
                                    tidur cukup dan makan teratur</div>
                            </div>
                            <div class="metric">
                                <div class="metric-icon"><i class="fas fa-smile"></i></div>
                                <div class="metric-label">Fokus pada hal-hal positif dan kelola stres dengan cara yang
                                    sehat</div>
                            </div>
                            <div class="metric">
                                <div class="metric-icon"><i class="fas fa-users"></i></div>
                                <div class="metric-label">Terlibat dalam kegiatan sosial atau komunitas yang membangun
                                    koneksi sehat</div>
                            </div>
                        </div>

                        <div class="recommendation">
                            <i class="fas fa-smile-beam"></i>
                            <span>Anda menunjukkan kesehatan mental yang baik. Pertahankan gaya hidup sehat, terus
                                beraktivitas positif, dan rawat relasi interpersonal agar tetap seimbang secara
                                emosional dan sosial.</span>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="status-indicator">
                            <div class="status-dot"></div>
                            <span>Kategori Aktif</span>
                        </div>
                        <div class="severity-level">Stabil</div>
                    </div>
                </div>
            @endif
            @if ($hasil->total_skor >= 190 && $hasil->total_skor <= 226)
                <div class="category-item category-excellent active" data-aos="flip-right" data-aos-delay="250">
                    <div class="floating-particles">
                        <div class="particle" style="left: 15%; animation-delay: 0.8s;"></div>
                        <div class="particle" style="left: 45%; animation-delay: 1.8s;"></div>
                        <div class="particle" style="left: 85%; animation-delay: 2.8s;"></div>
                    </div>

                    <div class="active-badge">
                        <i class="fas fa-check-circle"></i> Status Aktif
                    </div>

                    <div class="card-header">
                        <div class="icon-container">
                            <div class="icon-bg"></div>
                            <div class="category-icon">
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        <h3 class="category-title">Sangat Baik</h3>
                        <p class="category-subtitle">Sejahtera Mental</p>
                        <div class="category-range">Skor: 190 - 226</div>
                    </div>

                    <div class="card-body">
                        <div
                            class="category-description {{ $hasil->total_skor >= 190 && $hasil->total_skor <= 226 ? 'active' : 'hidden' }}">
                            Kamu berada pada kondisi mental yang sangat baik. Kemampuanmu dalam mengelola emosi,
                            berpikir positif, dan menjaga relasi sosial patut diapresiasi. Kamu tidak hanya mampu
                            menghadapi tekanan, tetapi juga menjadi sosok yang memberi dampak positif bagi orang lain.
                            Terus jaga dan rawat kondisi ini dengan kebiasaan baik, refleksi diri, serta terlibat dalam
                            hal-hal yang memberi makna bagi hidupmu.
                        </div>

                        <div class="health-metrics">
                            <div class="metric">
                                <div class="metric-icon"><i class="fas fa-lightbulb"></i></div>
                                <div class="metric-label">Terus eksplorasi minat dan ide baru sebagai bentuk
                                    pengembangan diri</div>
                            </div>
                            <div class="metric">
                                <div class="metric-icon"><i class="fas fa-sun"></i></div>
                                <div class="metric-label">Syukuri hal-hal kecil dan jalani hari dengan niat yang
                                    positif</div>
                            </div>
                            <div class="metric">
                                <div class="metric-icon"><i class="fas fa-hands-helping"></i></div>
                                <div class="metric-label">Bantu orang lain di sekitar sebagai bentuk empati dan
                                    memperkuat makna hidup</div>
                            </div>
                        </div>

                        <div class="recommendation">
                            <i class="fas fa-trophy"></i>
                            <span>Kesejahteraan mental Anda berada pada tingkat optimal. Pertahankan kebiasaan positif,
                                perluas wawasan diri, dan jadilah sumber dukungan atau inspirasi bagi lingkungan sekitar
                                Anda.</span>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="status-indicator">
                            <div class="status-dot"></div>
                            <span>Kategori Aktif</span>
                        </div>
                        <div class="severity-level">Optimal</div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!--
    <div data-aos="fade-right" data-aos-delay="200" class="category-description-active" <h3>Tentang Kategori
        "Sedang
        (Rentan)"</h3>
        <p>Individu dalam kategori ini memiliki tingkat kesehatan mental yang cukup, namun berada dalam kondisi
            rentan terhadap stres dan tekanan. Mungkin mengalami beberapa gejala gangguan psikologis ringan yang
            dapat berkembang jika tidak dikelola dengan baik. Diperlukan upaya aktif untuk menjaga dan
            meningkatkan
            kesejahteraan mental.</p>
    </div>

    <div data-aos="zoom-in" data-aos-delay="100" class="result-section">
        <h2>Penilaian Kesehatan Mental</h2>

        <div data-aos="fade-right" data-aos-delay="200" class="diagnosis-box diagnosis-moderate">
            <div class="diagnosis-title">
                <i class="icon icon-moderate">!</i>
                <h3>Kesehatan Mental Sedang (Rentan)</h3>
            </div>
            <div class="diagnosis-content">
                <p>Berdasarkan hasil kuesioner, Anda berada dalam kategori kesehatan mental "Sedang (Rentan)"
                    dengan
                    skor 145 dari rentang 131-160. Hal ini menunjukkan bahwa Anda memiliki beberapa kekuatan
                    mental
                    namun juga area yang perlu perhatian.</p>

                <h4>Kekuatan:</h4>
                <ul>
                    <li>Pengaruh positif umum yang baik (74%)</li>
                    <li>Kepuasan hidup yang cukup tinggi (75%)</li>
                </ul>

                <h4>Area yang perlu perhatian:</h4>
                <ul>
                    <li>Kontrol perilaku dan emosi (66%)</li>
                    <li>Kecemasan yang masih dalam level moderat (58%)</li>
                </ul>

                <div class="recommendations">
                    <h4>Rekomendasi:</h4>
                    <div class="recommendation-item">• Terapkan teknik pernapasan dan relaksasi secara teratur
                        untuk
                        mengelola kecemasan</div>
                    <div class="recommendation-item">• Tingkatkan kemampuan regulasi emosi melalui praktik
                        mindfulness</div>
                    <div class="recommendation-item">• Pertahankan aktivitas yang memberikan kepuasan hidup
                    </div>
                    <div class="recommendation-item">• Lakukan aktivitas fisik rutin minimal 30 menit per hari
                    </div>
                    <div class="recommendation-item">• Jaga hubungan sosial yang sehat dan positif</div>
                </div>
            </div>
        </div>
    </div>
-->
    <div class="tombol">
        <div data-aos="fade-down" data-aos-delay="200" class="action-buttons">
            <a href="/home" class="btn-back">
                <i class="fa-solid fa-house"></i>
                Kembali ke Halaman Utama
            </a>
        </div>
        <div data-aos="fade-down" data-aos-delay="200" class="action-buttons">
            <a href="https://wa.me/6285150876464" class="btn-back whatsapp-btn">
                <i class="fa-brands fa-whatsapp"></i>
                Konsultasi Lebih Lanjut
            </a>
        </div>
    </div>
    </div>
</body>
<x-footer></x-footer>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init();
</script>
<script src="{{ asset('js/script-hasil-mh.js') }}"></script>

<!-- Bootstrap core JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Core theme JS-->
<script src="js/script.js"></script>
<!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
<!-- * *                               SB Forms JS                               * *-->
<!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
<!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
<script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>

</html>
