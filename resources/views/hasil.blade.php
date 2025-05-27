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

    </head>
    <x-navbar></x-navbar>
    <body>
      <header>
        <div data-aos="fade-down" data-aos-delay="100" class="header-icon-circle">
          <i class="fa-solid fa-laptop-medical header-icon"></i>
        </div>
        <h1 data-aos="fade-down" data-aos-delay="200">Hasil Diagnosis Kesehatan Mental MHI-38</h1>
        <p data-aos="fade-down" data-aos-delay="300">Berdasarkan jawaban Anda pada kuesioner Mental Health</p>
    </header>
    
    <div class="container">
        <div class="result-section">
            <h2>Ringkasan Hasil</h2>
            
            <div class="score-card">
                <div data-aos="zoom-in" data-aos-delay="200" class="score-item">
                    <div class="score-label">Skor Total</div>
                    <div class="score-value">145</div>
                    <div class="score-category">Sedang (Rentan)</div>
                </div>
                
                <div data-aos="zoom-in" data-aos-delay="300" class="score-item score-range">
                    <div class="score-range-title">Rentang Kategori</div>
                    <div class="score-range-scale">
                        <div class="range-item range-very-poor">38-90</div>
                        <div class="range-item range-poor">91-130</div>
                        <div class="range-item range-moderate active">131-160</div>
                        <div class="range-item range-good">161-190</div>
                        <div class="range-item range-excellent">191-226</div>
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
            <p>Individu dalam kategori ini memiliki tingkat kesehatan mental yang cukup, namun berada dalam kondisi rentan terhadap stres dan tekanan. Mungkin mengalami beberapa gejala gangguan psikologis ringan yang dapat berkembang jika tidak dikelola dengan baik. Diperlukan upaya aktif untuk menjaga dan meningkatkan kesejahteraan mental.</p>
        </div>
        
        <div data-aos="fade-right" data-aos-delay="100" class="score-breakdown">
            <h3>Detail Aspek yang Diukur</h3>
            
            <div data-aos="fade-right" data-aos-delay="200" class="aspect-item">
                <div class="aspect-header">
                    <span>Kecemasan</span>
                    <span>22/38</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill progress-moderate" style="width: 58%"></div>
                </div>
                <div class="aspect-description">Tingkat kekhawatiran, gugup, tegang, atau panik.</div>
            </div>
            
            <div data-aos="fade-right" data-aos-delay="300" class="aspect-item">
                <div class="aspect-header">
                    <span>Depresi</span>
                    <span>19/38</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill progress-mild" style="width: 50%"></div>
                </div>
                <div class="aspect-description">Tingkat perasaan sedih, murung, atau putus asa.</div>
            </div>
            
            <div data-aos="fade-right" data-aos-delay="400" class="aspect-item">
                <div class="aspect-header">
                    <span>Hilangnya Kontrol Perilaku/Emosi</span>
                    <span>25/38</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill progress-moderate" style="width: 66%"></div>
                </div>
                <div class="aspect-description">Kemampuan mengendalikan perilaku, pikiran, dan perasaan.</div>
            </div>
            
            <div data-aos="fade-right" data-aos-delay="500" class="aspect-item">
                <div class="aspect-header">
                    <span>Pengaruh Positif Umum</span>
                    <span>28/38</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill progress-good" style="width: 74%"></div>
                </div>
                <div class="aspect-description">Perasaan gembira, bahagia, dan menikmati hidup.</div>
            </div>
            
            <div data-aos="fade-right" data-aos-delay="600" class="aspect-item">
                <div class="aspect-header">
                    <span>Ikatan Emosional</span>
                    <span>24/38</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill progress-moderate" style="width: 63%"></div>
                </div>
                <div class="aspect-description">Kemampuan membangun dan mempertahankan hubungan dengan orang lain.</div>
            </div>
            
            <div data-aos="fade-right" data-aos-delay="700" class="aspect-item">
                <div class="aspect-header">
                    <span>Kepuasan Hidup</span>
                    <span>27/36</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill progress-good" style="width: 75%"></div>
                </div>
                <div class="aspect-description">Perasaan puas terhadap kehidupan secara keseluruhan.</div>
            </div>
        </div>
    </div>
        
        <div data-aos="zoom-in" data-aos-delay="100" class="result-section">
            <h2>Diagnosis Kesehatan Mental</h2>
            
            <div data-aos="fade-right" data-aos-delay="200" class="diagnosis-box diagnosis-moderate">
                <div class="diagnosis-title">
                    <i class="icon icon-moderate">!</i>
                    <h3>Kesehatan Mental Sedang (Rentan)</h3>
                </div>
                <div class="diagnosis-content">
                    <p>Berdasarkan hasil kuesioner, Anda berada dalam kategori kesehatan mental "Sedang (Rentan)" dengan skor 145 dari rentang 131-160. Hal ini menunjukkan bahwa Anda memiliki beberapa kekuatan mental namun juga area yang perlu perhatian.</p>
                    
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
                        <div class="recommendation-item">• Terapkan teknik pernapasan dan relaksasi secara teratur untuk mengelola kecemasan</div>
                        <div class="recommendation-item">• Tingkatkan kemampuan regulasi emosi melalui praktik mindfulness</div>
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
                <div data-aos="flip-left" data-aos-delay="200" class="category-item category-very-poor">
                    <h3>Sangat Buruk (Distres Berat)</h3>
                    <div class="category-range">38 - 90</div>
                    <p>Individu dalam kategori ini menunjukkan tanda-tanda distres psikologis yang signifikan dan mungkin mengalami gangguan fungsi sehari-hari. Disarankan untuk segera mencari bantuan profesional kesehatan mental.</p>
                </div>
                
                <div data-aos="flip-left" data-aos-delay="400" class="category-item category-poor">
                    <h3>Buruk (Distres Sedang)</h3>
                    <div class="category-range">91 - 130</div>
                    <p>Menunjukkan adanya distres psikologis tingkat sedang dengan beberapa gangguan fungsi. Intervensi profesional mungkin diperlukan untuk mencegah perburukan kondisi.</p>
                </div>
                
                <div data-aos="flip-left" data-aos-delay="600" class="category-item category-moderate active">
                    <h3>Sedang (Rentan)</h3>
                    <div class="category-range">131 - 160</div>
                    <p>Kesehatan mental cukup stabil namun rentan terhadap stres. Beberapa gejala psikologis ringan mungkin muncul. Perlu aktif menjaga kesehatan mental.</p>
                </div>
                
                <div data-aos="flip-left" data-aos-delay="800" class="category-item category-good">
                    <h3>Baik (Sehat Secara Mental)</h3>
                    <div class="category-range">161 - 190</div>
                    <p>Menunjukkan kesehatan mental yang baik dengan kemampuan mengatasi tekanan hidup secara efektif. Memiliki fungsi psikologis dan sosial yang positif.</p>
                </div>
                
                <div data-aos="flip-left" data-aos-delay="1000" class="category-item category-excellent">
                    <h3>Sangat Baik (Sejahtera Secara Mental)</h3>
                    <div class="category-range">191 - 226</div>
                    <p>Menunjukkan kesejahteraan mental yang optimal dengan tingkat positif yang tinggi. Mampu berkembang dan berfungsi optimal dalam berbagai aspek kehidupan.</p>
                </div>
            </div>
        </div>

        <div class="result-section articles-section">
            <h2 data-aos="zoom-in" data-aos-delay="100">Artikel & Sumber Daya</h2>
            <p data-aos="zoom-in" data-aos-delay="150">Berikut adalah beberapa artikel dan sumber daya yang mungkin membantu Anda:</p>
            
            <div class="article-cards">
                <div data-aos="flip-left" data-aos-delay="200" class="article-card">
                    <img src="placeholder-anxiety.jpg" alt="Mengelola Kecemasan" class="article-img">
                    <div class="article-content">
                        <span class="article-category">Kecemasan</span>
                        <h3 class="article-title">7 Teknik Efektif untuk Mengelola Kecemasan Sehari-hari</h3>
                        <p class="article-desc">Temukan cara praktis untuk mengatasi gejala kecemasan dan mengurangi dampaknya pada kehidupan sehari-hari.</p>
                        <a href="#" class="article-link">Baca Selengkapnya →</a>
                    </div>
                </div>
                
                <div data-aos="flip-left" data-aos-delay="400" class="article-card">
                    <img src="placeholder-emotion.jpg" alt="Kontrol Emosi" class="article-img">
                    <div class="article-content">
                        <span class="article-category">Kontrol Emosi</span>
                        <h3 class="article-title">Meningkatkan Regulasi Emosi: Panduan Praktis</h3>
                        <p class="article-desc">Pelajari teknik-teknik efektif untuk meningkatkan kemampuan mengendalikan emosi dan respons terhadap stres.</p>
                        <a href="#" class="article-link">Baca Selengkapnya →</a>
                    </div>
                </div>
                
                <div data-aos="flip-left" data-aos-delay="600" class="article-card">
                    <img src="placeholder-mindfulness.jpg" alt="Mindfulness" class="article-img">
                    <div class="article-content">
                        <span class="article-category">Mindfulness</span>
                        <h3 class="article-title">Praktik Mindfulness untuk Kesehatan Mental Sehari-hari</h3>
                        <p class="article-desc">Teknik sederhana mindfulness yang dapat diintegrasikan ke dalam rutinitas harian untuk meningkatkan kesadaran dan kesejahteraan.</p>
                        <a href="#" class="article-link">Baca Selengkapnya →</a>
                    </div>
                </div>
            </div>
        </div>
        
        
        <div data-aos="fade-down" data-aos-delay="200" class="action-buttons">
          <a href="/home" class="btn-back">
            <i class="fa-solid fa-house"></i>
              Kembali ke Halaman Utama
          </a>
      </div>
    </div>

    </body>
    <x-footer></x-footer>
    <script>
      // Script untuk menyesuaikan kategori skor dan tampilan
      document.addEventListener('DOMContentLoaded', function() {
          // Contoh implementasi jika halaman menerima data dari backend
          // const scoreData = { 
          //     total: 145,
          //     anxiety: 22,
          //     depression: 19,
          //     behavioralControl: 25,
          //     positiveAffect: 28,
          //     emotionalTies: 24,
          //     lifeSatisfaction: 27
          // };
          // updateScoreDisplay(scoreData);
      });
      
      function updateScoreDisplay(data) {
          // Fungsi untuk memperbarui tampilan berdasarkan data skor
          // Implementasi akan bergantung pada struktur data yang diterima
      }
  </script>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
      AOS.init();
    </script>
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