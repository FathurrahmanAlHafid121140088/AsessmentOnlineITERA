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

            <div class="score-card">
                <div data-aos="zoom-in" data-aos-delay="200" class="score-item">
                    <div class="score-label">Skor Total</div>
                    <div class="score-value">{{ $hasil->total_skor }}</div>
                    <div class="score-category">{{ $hasil->kategori }}</div>
                </div>
                <div data-aos="zoom-in" data-aos-delay="300" class="score-item score-range">
                    <div class="score-range-title">Rentang Kategori</div>
                    <div class="score-range-scale">
                        <div
                            class="range-item range-very-poor {{ $hasil->total_skor >= 38 && $hasil->total_skor <= 90 ? 'active' : '' }}">
                            38-90
                        </div>
                        <div
                            class="range-item range-poor {{ $hasil->total_skor >= 91 && $hasil->total_skor <= 130 ? 'active' : '' }}">
                            91-130
                        </div>
                        <div
                            class="range-item range-moderate {{ $hasil->total_skor >= 131 && $hasil->total_skor <= 160 ? 'active' : '' }}">
                            131-160
                        </div>
                        <div
                            class="range-item range-good {{ $hasil->total_skor >= 161 && $hasil->total_skor <= 190 ? 'active' : '' }}">
                            161-190
                        </div>
                        <div
                            class="range-item range-excellent {{ $hasil->total_skor >= 191 && $hasil->total_skor <= 226 ? 'active' : '' }}">
                            191-226
                        </div>
                    </div>
                    <div class="score-range-labels">
                        <span>Sangat Buruk</span>
                        <span>Sangat Baik</span>
                    </div>
                </div>
            </div>
        </div>


        <div data-aos="fade-right" data-aos-delay="200" class="category-description">
            <h3>Tentang Kategori "Sedang (Rentan)"</h3>
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
        <div class="result-section category-info">
            <div class="header">
                <h1 class="title">Kategori Mental Health</h1>
                <p class="subtitle">Evaluasi Kondisi Mental Health Berdasarkan Metode MHI-38</p>
            </div>
            <div class="category-list">
                <div class="category-item category-very-poor {{ $hasil->total_skor >= 38 && $hasil->total_skor <= 90 ? 'active' : '' }}"
                    data-aos="flip-right" data-aos-delay="50">
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
                        <div class="category-range">Skor: 38 - 90</div>
                    </div>

                    <div class="card-body">
                        <p class="category-description">
                            Menunjukkan tanda-tanda distres psikologis yang signifikan dengan gangguan fungsi
                            sehari-hari.
                            Kondisi ini memerlukan intervensi medis segera untuk mencegah komplikasi lebih lanjut.
                        </p>

                        <div class="health-metrics">
                            <div class="metric">
                                <div class="metric-icon"><i class="fas fa-brain"></i></div>
                                <div class="metric-label">Kognitif</div>
                            </div>
                            <div class="metric">
                                <div class="metric-icon"><i class="fas fa-heart"></i></div>
                                <div class="metric-label">Emosional</div>
                            </div>
                            <div class="metric">
                                <div class="metric-icon"><i class="fas fa-users"></i></div>
                                <div class="metric-label">Sosial</div>
                            </div>
                        </div>

                        <div class="recommendation">
                            <i class="fas fa-user-md"></i> Konsultasi segera dengan psikiater atau psikolog klinis
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

                <div class="category-item category-poor {{ $hasil->total_skor >= 91 && $hasil->total_skor <= 130 ? 'active' : '' }}"
                    data-aos="flip-right" data-aos-delay="100">
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
                        <div class="category-range">Skor: 91 - 130</div>
                    </div>

                    <div class="card-body">
                        <p class="category-description">
                            Mengalami distres psikologis tingkat sedang dengan beberapa gangguan fungsi.
                            Memerlukan perhatian khusus dan monitoring berkelanjutan dari tenaga kesehatan.
                        </p>

                        <div class="health-metrics">
                            <div class="metric">
                                <div class="metric-icon"><i class="fas fa-brain"></i></div>
                                <div class="metric-label">Kognitif</div>
                            </div>
                            <div class="metric">
                                <div class="metric-icon"><i class="fas fa-heart"></i></div>
                                <div class="metric-label">Emosional</div>
                            </div>
                            <div class="metric">
                                <div class="metric-icon"><i class="fas fa-users"></i></div>
                                <div class="metric-label">Sosial</div>
                            </div>
                        </div>

                        <div class="recommendation">
                            <i class="fas fa-heart"></i> Pertimbangkan terapi psikologis dan konseling
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

                <div class="category-item category-moderate {{ $hasil->total_skor >= 131 && $hasil->total_skor <= 160 ? 'active' : '' }}"
                    data-aos="flip-right" data-aos-delay="150">
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
                        <div class="category-range">Skor: 131 - 160</div>
                    </div>

                    <div class="card-body">
                        <p class="category-description">
                            Kondisi mental cukup stabil namun rentan terhadap stres. Gejala ringan mungkin muncul
                            dalam situasi tertentu. Diperlukan strategi preventif yang tepat.
                        </p>

                        <div class="health-metrics">
                            <div class="metric">
                                <div class="metric-icon"><i class="fas fa-brain"></i></div>
                                <div class="metric-label">Kognitif</div>
                            </div>
                            <div class="metric">
                                <div class="metric-icon"><i class="fas fa-heart"></i></div>
                                <div class="metric-label">Emosional</div>
                            </div>
                            <div class="metric">
                                <div class="metric-icon"><i class="fas fa-users"></i></div>
                                <div class="metric-label">Sosial</div>
                            </div>
                        </div>

                        <div class="recommendation">
                            <i class="fas fa-shield-alt"></i> Terapkan teknik manajemen stres dan self-care
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

                <div class="category-item category-good {{ $hasil->total_skor >= 161 && $hasil->total_skor <= 190 ? 'active' : '' }}"
                    data-aos="flip-right" data-aos-delay="200">
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
                        <div class="category-range">Skor: 161 - 190</div>
                    </div>

                    <div class="card-body">
                        <p class="category-description">
                            Menunjukkan kesehatan mental yang baik dengan kemampuan adaptasi yang efektif.
                            Fungsi psikososial optimal dan resiliensi yang memadai terhadap tekanan.
                        </p>

                        <div class="health-metrics">
                            <div class="metric">
                                <div class="metric-icon"><i class="fas fa-brain"></i></div>
                                <div class="metric-label">Kognitif</div>
                            </div>
                            <div class="metric">
                                <div class="metric-icon"><i class="fas fa-heart"></i></div>
                                <div class="metric-label">Emosional</div>
                            </div>
                            <div class="metric">
                                <div class="metric-icon"><i class="fas fa-users"></i></div>
                                <div class="metric-label">Sosial</div>
                            </div>
                        </div>

                        <div class="recommendation">
                            <i class="fas fa-thumbs-up"></i> Pertahankan gaya hidup sehat dan rutinitas positif
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

                <div class="category-item category-excellent {{ $hasil->total_skor >= 191 && $hasil->total_skor <= 226 ? 'active' : '' }}"
                    data-aos="flip-right" data-aos-delay="250">
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
                        <div class="category-range">Skor: 191 - 226</div>
                    </div>

                    <div class="card-body">
                        <p class="category-description">
                            Menunjukkan kesejahteraan mental yang optimal dengan tingkat flourishing yang tinggi.
                            Mampu berkembang dan berfungsi secara maksimal dalam semua aspek kehidupan.
                        </p>

                        <div class="health-metrics">
                            <div class="metric">
                                <div class="metric-icon"><i class="fas fa-brain"></i></div>
                                <div class="metric-label">Kognitif</div>
                            </div>
                            <div class="metric">
                                <div class="metric-icon"><i class="fas fa-heart"></i></div>
                                <div class="metric-label">Emosional</div>
                            </div>
                            <div class="metric">
                                <div class="metric-icon"><i class="fas fa-users"></i></div>
                                <div class="metric-label">Sosial</div>
                            </div>
                        </div>

                        <div class="recommendation">
                            <i class="fas fa-trophy"></i> Jaga kondisi optimal dan berperan sebagai support system
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
            </div>
        </div>

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
                    Hubungi Kami
                </a>
            </div>
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
