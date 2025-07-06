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
                dapat berkembang jika tidak dikelola dengan baik. Diperlukan upaya aktif untuk menjaga dan meningkatkan
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
                    <p>Berdasarkan hasil kuesioner, Anda berada dalam kategori kesehatan mental "Sedang (Rentan)" dengan
                        skor 145 dari rentang 131-160. Hal ini menunjukkan bahwa Anda memiliki beberapa kekuatan mental
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
                        <div class="recommendation-item">• Terapkan teknik pernapasan dan relaksasi secara teratur untuk
                            mengelola kecemasan</div>
                        <div class="recommendation-item">• Tingkatkan kemampuan regulasi emosi melalui praktik
                            mindfulness</div>
                        <div class="recommendation-item">• Pertahankan aktivitas yang memberikan kepuasan hidup</div>
                        <div class="recommendation-item">• Lakukan aktivitas fisik rutin minimal 30 menit per hari</div>
                        <div class="recommendation-item">• Jaga hubungan sosial yang sehat dan positif</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="result-section category-info">
            <h2 data-aos="zoom-in" data-aos-delay="100">Informasi Kategori Kesehatan Mental</h2>

            <div class="category-list">
                <div data-aos="flip-left" data-aos-delay="200"
                    class="category-item category-very-poor {{ $hasil->total_skor >= 38 && $hasil->total_skor <= 90 ? 'active' : '' }}">
                    <h3>Sangat Buruk (Distres Berat)</h3>
                    <div class="category-range">38 - 90</div>
                    <p>Individu dalam kategori ini menunjukkan tanda-tanda distres psikologis yang signifikan dan
                        mungkin mengalami gangguan fungsi sehari-hari. Disarankan untuk segera mencari bantuan
                        profesional kesehatan mental.</p>
                </div>

                <div data-aos="flip-left" data-aos-delay="400"
                    class="category-item category-poor {{ $hasil->total_skor >= 91 && $hasil->total_skor <= 130 ? 'active' : '' }}">
                    <h3>Buruk (Distres Sedang)</h3>
                    <div class="category-range">91 - 130</div>
                    <p>Menunjukkan adanya distres psikologis tingkat sedang dengan beberapa gangguan fungsi. Intervensi
                        profesional mungkin diperlukan untuk mencegah perburukan kondisi.</p>
                </div>

                <div data-aos="flip-left" data-aos-delay="600"
                    class="category-item category-moderate {{ $hasil->total_skor >= 131 && $hasil->total_skor <= 160 ? 'active' : '' }}">
                    <h3>Sedang (Rentan)</h3>
                    <div class="category-range">131 - 160</div>
                    <p>Kesehatan mental cukup stabil namun rentan terhadap stres. Beberapa gejala psikologis ringan
                        mungkin muncul. Perlu aktif menjaga kesehatan mental.</p>
                </div>

                <div data-aos="flip-left" data-aos-delay="800"
                    class="category-item category-good {{ $hasil->total_skor >= 161 && $hasil->total_skor <= 190 ? 'active' : '' }}">
                    <h3>Baik (Sehat Secara Mental)</h3>
                    <div class="category-range">161 - 190</div>
                    <p>Menunjukkan kesehatan mental yang baik dengan kemampuan mengatasi tekanan hidup secara efektif.
                        Memiliki fungsi psikologis dan sosial yang positif.</p>
                </div>

                <div data-aos="flip-left" data-aos-delay="1000"
                    class="category-item category-excellent {{ $hasil->total_skor >= 191 && $hasil->total_skor <= 226 ? 'active' : '' }}">
                    <h3>Sangat Baik (Sejahtera Secara Mental)</h3>
                    <div class="category-range">191 - 226</div>
                    <p>Menunjukkan kesejahteraan mental yang optimal dengan tingkat positif yang tinggi. Mampu
                        berkembang dan berfungsi optimal dalam berbagai aspek kehidupan.</p>
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
