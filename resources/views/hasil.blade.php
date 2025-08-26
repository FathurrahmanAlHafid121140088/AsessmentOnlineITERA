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

        <div class="alert-box">
            <i class="fas fa-exclamation-triangle alert-icon"></i>
            <span class="alert-text">Hasil tes dibawah ini hanya berlaku selama 6 bulan</span>
        </div>

        <div class="result-section">
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
                            Perlu Dukungan Intensif
                        </span>

                        <span
                            class="range-poor {{ $hasil->total_skor >= 76 && $hasil->total_skor <= 113 ? 'active' : '' }}">
                            Perlu Dukungan
                        </span>

                        <span
                            class=" range-moderate {{ $hasil->total_skor >= 114 && $hasil->total_skor <= 151 ? 'active' : '' }}">
                            Cukup Sehat
                        </span>

                        <span
                            class="range-good {{ $hasil->total_skor >= 152 && $hasil->total_skor <= 189 ? 'active' : '' }}">
                            Sehat
                        </span>

                        <span
                            class="range-excellent {{ $hasil->total_skor >= 190 && $hasil->total_skor <= 226 ? 'active' : '' }}">
                            Sangat Sehat
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
                            <h3 class="category-title">Perlu Dukungan Intensif</h3>
                            <p class="category-subtitle">Distres Berat</p>
                            <div class="category-range">Skor: 38 - 75</div>
                        </div>

                        <div class="card-body">
                            <div
                                class="category-description {{ $hasil->total_skor >= 38 && $hasil->total_skor <= 75 ? 'active' : 'hidden' }}">
                                Saat ini, kondisi Anda menunjukkan adanya tekanan psikologis yang cukup berat hingga
                                memengaruhi
                                aktivitas sehari-hari. Mungkin Anda merasa sangat terbebani, sulit mengendalikan
                                pikiran, cepat lelah,
                                atau kehilangan semangat untuk beraktivitas. Hal ini wajar dan bukan kelemahan
                                pribadi—namun sinyal bahwa
                                tubuh dan pikiran Anda sedang membutuhkan perhatian lebih. Ingatlah, mencari dukungan
                                dan pertolongan
                                bukanlah tanda kelemahan, melainkan langkah berani untuk memulihkan kesehatan diri.
                            </div>

                            <div class="health-metrics">
                                <!-- Fisik -->
                                <div class="metric">
                                    <div class="metric-icon"><i class="fas fa-bed"></i></div>
                                    <div class="metric-label">Cobalah menjaga pola tidur teratur dan cukup istirahat
                                        agar tubuh kembali bugar.</div>
                                </div>
                                <div class="metric">
                                    <div class="metric-icon"><i class="fas fa-utensils"></i></div>
                                    <div class="metric-label">Perhatikan asupan nutrisi seimbang, konsumsi makanan
                                        bergizi untuk mendukung energi harian.</div>
                                </div>
                                <div class="metric">
                                    <div class="metric-icon"><i class="fas fa-walking"></i></div>
                                    <div class="metric-label">Lakukan aktivitas fisik ringan seperti jalan santai atau
                                        peregangan untuk melepaskan ketegangan.</div>
                                </div>

                                <!-- Mental -->
                                <div class="metric">
                                    <div class="metric-icon"><i class="fas fa-brain"></i></div>
                                    <div class="metric-label">Luangkan waktu untuk latihan relaksasi, pernapasan dalam,
                                        atau meditasi sederhana.</div>
                                </div>
                                <div class="metric">
                                    <div class="metric-icon"><i class="fas fa-user-friends"></i></div>
                                    <div class="metric-label">Ceritakan perasaan Anda kepada orang terdekat yang
                                        dipercaya agar tidak merasa sendirian.</div>
                                </div>
                                <div class="metric">
                                    <div class="metric-icon"><i class="fas fa-user-md"></i></div>
                                    <div class="metric-label">Segera pertimbangkan untuk berkonsultasi dengan psikolog
                                        atau psikiater guna mendapatkan dukungan profesional.</div>
                                </div>
                            </div>

                            <div class="recommendation">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span>Kondisi Anda saat ini menunjukkan tekanan psikologis yang serius dan sudah
                                    mengganggu fungsi sehari-hari.
                                    Jangan menunda untuk mencari bantuan profesional. Dukungan dari psikolog atau
                                    psikiater dapat membantu
                                    Anda menemukan cara pemulihan yang lebih tepat dan aman.</span>
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
                            <h3 class="category-title">Perlu Dukungan</h3>
                            <p class="category-subtitle">Distres Sedang</p>
                            <div class="category-range">Skor: 76 - 113</div>
                        </div>

                        <div class="card-body">
                            <div
                                class="category-description {{ $hasil->total_skor >= 76 && $hasil->total_skor <= 113 ? 'active' : 'hidden' }}">
                                Saat ini Anda mungkin mengalami masalah yang cukup signifikan dalam emosi maupun fungsi
                                sosial,
                                seperti mudah tersinggung, sulit tidur, atau merasa cepat lelah dalam berinteraksi.
                                Kondisi ini bisa mengganggu keseharian Anda, dan penting untuk tidak menanggungnya
                                sendirian.
                                Dukungan dari keluarga, teman, atau tenaga profesional dapat membantu mengurangi beban
                                yang Anda rasakan.
                                Ingatlah, meminta bantuan adalah langkah penting untuk kembali menata keseimbangan
                                hidup.
                            </div>

                            <div class="health-metrics">
                                <!-- Fisik -->
                                <div class="metric">
                                    <div class="metric-icon"><i class="fas fa-bed"></i></div>
                                    <div class="metric-label">Tidur cukup dengan pola yang teratur untuk menjaga
                                        kestabilan energi harian.</div>
                                </div>
                                <div class="metric">
                                    <div class="metric-icon"><i class="fas fa-apple-alt"></i></div>
                                    <div class="metric-label">Konsumsi makanan sehat dan hindari kafein/alkohol
                                        berlebihan yang dapat memperburuk stres.</div>
                                </div>
                                <div class="metric">
                                    <div class="metric-icon"><i class="fas fa-bicycle"></i></div>
                                    <div class="metric-label">Luangkan waktu untuk olahraga ringan seperti bersepeda
                                        atau yoga agar tubuh lebih rileks.</div>
                                </div>

                                <!-- Mental -->
                                <div class="metric">
                                    <div class="metric-icon"><i class="fas fa-comments"></i></div>
                                    <div class="metric-label">Buka diri untuk berbicara dengan keluarga atau sahabat
                                        terpercaya tentang perasaan Anda.</div>
                                </div>
                                <div class="metric">
                                    <div class="metric-icon"><i class="fas fa-music"></i></div>
                                    <div class="metric-label">Gunakan musik atau aktivitas kreatif sebagai sarana
                                        melepas emosi negatif.</div>
                                </div>
                                <div class="metric">
                                    <div class="metric-icon"><i class="fas fa-user-md"></i></div>
                                    <div class="metric-label">Pertimbangkan konsultasi dengan psikolog/psikiater untuk
                                        mendapat strategi penanganan yang tepat.</div>
                                </div>
                            </div>

                            <div class="recommendation">
                                <i class="fas fa-hands-helping"></i>
                                <span>Ada masalah signifikan dalam emosi atau fungsi sosial yang membutuhkan perhatian.
                                    Dukungan keluarga sangat penting, dan bantuan profesional dapat mempercepat
                                    pemulihan.
                                    Jangan ragu untuk mencari pertolongan agar keseharian Anda kembali lebih
                                    seimbang.</span>
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
                            <h3 class="category-title">Cukup Sehat</h3>
                            <p class="category-subtitle">Rentan</p>
                            <div class="category-range">Skor: 114 - 151</div>
                        </div>

                        <div class="card-body">
                            <div
                                class="category-description {{ $hasil->total_skor >= 114 && $hasil->total_skor <= 151 ? 'active' : 'hidden' }}">
                                Ada keseimbangan antara faktor positif dan negatif, namun masih terdapat risiko yang
                                perlu diperhatikan.
                                Kamu mungkin sesekali merasa tertekan atau kurang bersemangat saat menghadapi situasi
                                tertentu. Tetap
                                jaga pola hidup sehat, istirahat cukup, dan hubungan sosial agar kesehatan mentalmu
                                tetap terpelihara.
                            </div>

                            <div class="health-metrics">
                                {{-- MENTAL --}}
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
                                    <div class="metric-label">Jaga komunikasi dengan teman dan keluarga meski hanya
                                        melalui pesan singkat</div>
                                </div>

                                {{-- FISIK --}}
                                <div class="metric">
                                    <div class="metric-icon"><i class="fas fa-walking"></i></div>
                                    <div class="metric-label">Luangkan waktu untuk aktivitas fisik ringan seperti
                                        berjalan atau peregangan</div>
                                </div>
                                <div class="metric">
                                    <div class="metric-icon"><i class="fas fa-apple-alt"></i></div>
                                    <div class="metric-label">Konsumsi makanan bergizi seimbang dan cukup minum air
                                        putih</div>
                                </div>
                                <div class="metric">
                                    <div class="metric-icon"><i class="fas fa-bed"></i></div>
                                    <div class="metric-label">Pastikan tidur cukup dan berkualitas setiap malam</div>
                                </div>
                            </div>

                            <div class="recommendation">
                                <i class="fas fa-shield-alt"></i>
                                <span>Kondisi Anda relatif stabil namun tetap memiliki risiko. Lakukan pemeliharaan
                                    kesehatan mental
                                    melalui keseimbangan hidup, tidur cukup, manajemen stres, serta tetap terhubung
                                    dengan dukungan sosial.</span>
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
                            <h3 class="category-title">Sehat</h3>
                            <p class="category-subtitle">Sehat Mental</p>
                            <div class="category-range">Skor: 152 - 189</div>
                        </div>

                        <div class="card-body">
                            <div
                                class="category-description {{ $hasil->total_skor >= 152 && $hasil->total_skor <= 189 ? 'active' : 'hidden' }}">
                                Kesehatan mental Anda berada dalam kondisi baik. Anda mampu mengelola stres dengan cukup
                                baik, menjaga
                                keseimbangan emosi, serta berfungsi secara sosial dan emosional secara optimal. Tetaplah
                                memberi perhatian
                                pada diri agar kestabilan ini dapat terus terjaga.
                            </div>
                            <div class="health-metrics">
                                <!-- Mental -->
                                <div class="metric">
                                    <div class="metric-icon"><i class="fas fa-seedling"></i></div>
                                    <div class="metric-label">Lanjutkan rutinitas sehat untuk mendukung stabilitas
                                        emosi</div>
                                </div>
                                <div class="metric">
                                    <div class="metric-icon"><i class="fas fa-smile"></i></div>
                                    <div class="metric-label">Kelola stres dengan teknik relaksasi atau mindfulness
                                    </div>
                                </div>
                                <div class="metric">
                                    <div class="metric-icon"><i class="fas fa-users"></i></div>
                                    <div class="metric-label">Bangun hubungan sosial yang positif untuk menjaga
                                        keseimbangan emosi</div>
                                </div>

                                <!-- Fisik -->
                                <div class="metric">
                                    <div class="metric-icon"><i class="fas fa-walking"></i></div>
                                    <div class="metric-label">Lakukan aktivitas fisik ringan seperti jalan kaki atau
                                        olahraga rutin</div>
                                </div>
                                <div class="metric">
                                    <div class="metric-icon"><i class="fas fa-apple-alt"></i></div>
                                    <div class="metric-label">Jaga pola makan seimbang dengan nutrisi yang cukup</div>
                                </div>
                                <div class="metric">
                                    <div class="metric-icon"><i class="fas fa-bed"></i></div>
                                    <div class="metric-label">Tidur yang cukup untuk pemulihan tubuh dan pikiran</div>
                                </div>
                            </div>

                            <div class="recommendation">
                                <i class="fas fa-smile-beam"></i>
                                <span>Anda menunjukkan kesehatan mental yang baik. Pertahankan gaya hidup sehat, terus
                                    beraktivitas positif, dan rawat relasi interpersonal agar tetap optimal secara
                                    emosional dan fisik.</span>
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
                        <!-- Partikel hias -->
                        <div class="floating-particles">
                            <div class="particle" style="left: 15%; animation-delay: 0.8s;"></div>
                            <div class="particle" style="left: 45%; animation-delay: 1.8s;"></div>
                            <div class="particle" style="left: 85%; animation-delay: 2.8s;"></div>
                        </div>

                        <!-- Badge aktif -->
                        <div class="active-badge">
                            <i class="fas fa-check-circle"></i> Status Aktif
                        </div>

                        <!-- Header Card -->
                        <div class="card-header">
                            <div class="icon-container">
                                <div class="icon-bg"></div>
                                <div class="category-icon">
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                            <h3 class="category-title">Sangat Sehat</h3>
                            <p class="category-subtitle">Sejahtera Mental</p>
                            <div class="category-range">Skor: 190 - 226</div>
                        </div>

                        <!-- Body -->
                        <div class="card-body">
                            <!-- Deskripsi -->
                            <div
                                class="category-description {{ $hasil->total_skor >= 190 && $hasil->total_skor <= 226 ? 'active' : 'hidden' }}">
                                Kamu berada pada kondisi mental dan fisik yang sangat optimal, dengan kesejahteraan
                                psikologis
                                yang tinggi, hubungan sosial yang harmonis, serta ketahanan diri yang kuat dalam
                                menghadapi
                                tekanan maupun tantangan kehidupan. Kemampuanmu dalam mengelola emosi, menjaga pikiran
                                positif,
                                dan menumbuhkan energi yang seimbang membuatmu tidak hanya berdaya secara pribadi,
                                tetapi juga
                                mampu menjadi sumber dukungan bagi orang lain. Teruslah pelihara keadaan ini dengan
                                kebiasaan
                                sehat, refleksi diri yang konsisten, serta aktivitas yang memberi makna agar kualitas
                                hidupmu
                                semakin bertumbuh secara berkelanjutan.
                            </div>

                            <!-- METRIC MENTAL -->
                            <h4 class="metrics-title">Aspek Mental</h4>
                            <div class="health-metrics">
                                <div class="metric">
                                    <div class="metric-icon"><i class="fas fa-lightbulb"></i></div>
                                    <div class="metric-label">Terus kembangkan ide baru dan keterampilan sebagai bentuk
                                        pengayaan diri</div>
                                </div>
                                <div class="metric">
                                    <div class="metric-icon"><i class="fas fa-smile"></i></div>
                                    <div class="metric-label">Kelola emosi dengan tenang serta nikmati hal-hal kecil
                                        dalam kehidupan</div>
                                </div>
                                <div class="metric">
                                    <div class="metric-icon"><i class="fas fa-users"></i></div>
                                    <div class="metric-label">Jalin relasi sosial yang sehat dan berikan dukungan
                                        kepada orang lain</div>
                                </div>
                            </div>

                            <!-- METRIC FISIK -->
                            <h4 class="metrics-title">Aspek Fisik</h4>
                            <div class="health-metrics">
                                <div class="metric">
                                    <div class="metric-icon"><i class="fas fa-dumbbell"></i></div>
                                    <div class="metric-label">Pertahankan aktivitas fisik rutin seperti olahraga ringan
                                        maupun intensitas sedang</div>
                                </div>
                                <div class="metric">
                                    <div class="metric-icon"><i class="fas fa-apple-alt"></i></div>
                                    <div class="metric-label">Konsumsi makanan bergizi seimbang untuk menjaga energi
                                        dan vitalitas</div>
                                </div>
                                <div class="metric">
                                    <div class="metric-icon"><i class="fas fa-bed"></i></div>
                                    <div class="metric-label">Tidur yang cukup dan berkualitas untuk memulihkan tubuh
                                        dan pikiran</div>
                                </div>
                            </div>

                            <!-- Rekomendasi -->
                            <div class="recommendation">
                                <i class="fas fa-trophy"></i>
                                <span>Kondisi Anda berada pada tingkat optimal. Pertahankan gaya hidup sehat secara
                                    menyeluruh,
                                    perluas wawasan diri, dan jadilah sumber inspirasi serta dukungan positif bagi
                                    lingkungan sekitar.</span>
                            </div>
                        </div>

                        <!-- Footer -->
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
        <div class="alert-box-blue">
            <i class="fas fa-info-circle alert-icon-blue"></i>
            <span class="alert-text">
                "Kalau hasil tes kamu Cukup Sehat sampai Perlu Dukungan Intensif, segera
                hubungi Mental Health Care ITERA via WhatsApp untuk konseling lanjutan."
            </span>
        </div>
        <div class="tombol">
            <div data-aos="fade-down" data-aos-delay="200" class="action-buttons">
                <a href="https://wa.me/6285150876464" class="btn-back whatsapp-btn">
                    <i class="fa-brands fa-whatsapp"></i>
                    Konsultasi Lebih Lanjut
                </a>
            </div>
            <div data-aos="fade-down" data-aos-delay="200" class="action-buttons">
                <a href="/home" class="btn-back">
                    <i class="fa-solid fa-house"></i>
                    Kembali ke Halaman Utama
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
