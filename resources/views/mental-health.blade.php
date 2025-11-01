<!DOCTYPE html>
<html lang="en">

<head>

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>{{ $title }}</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet"
            type="text/css" />
        <!-- Core theme CSS (includes Bootstrap)-->
        @vite(['resources/css/app-mh-home.css'])


        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    </head>
</head>
<x-navbar></x-navbar>
<main>
    <!-- Hero Section -->
    <section id="hero" class="hero section">

        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center" data-aos="fade-up">
                    <h1 data-aos="fade-down" data-aos-delay="100">Selamat Datang di Tes Kesehatan Mental MHI-38</h1>
                    <p data-aos="fade-down" data-aos-delay="200">"Kenali kondisi kesehatan mental Anda dengan metode
                        ilmiah yang terpercaya."</p>
                    <div data-aos="zoom-in" data-aos-delay="300" class="d-flex">
                        <a href="/mental-health/isi-data-diri" class="btn-get-started">Mulai Tes Sekarang!</a>
                    </div>
                </div>
                <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-out" data-aos-delay="100">
                    <img src="../assets/img/hero-img.png" class="img-fluid animated" alt="">
                </div>
            </div>
        </div>

    </section><!-- /Hero Section -->

    <!-- About Section -->
    <section id="about" class="about section">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <span>Mental Health Inventory<br></span>
            <h2>Apa itu MHI-38?</h2>
            <p>Mental Health Inventory-38 (MHI-38) adalah instrumen penilaian yang digunakan untuk mengukur kesehatan
                mental seseorang dalam berbagai aspek, seperti kecemasan, depresi, dan kesejahteraan psikologis secara
                umum.</p>
        </div><!-- End Section Title -->

        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-6 position-relative align-self-start" data-aos="fade-up" data-aos-delay="100">
                    <img src="../assets/img/about.png" class="img-fluid" alt="">
                </div>
                <div class="col-lg-6 content" data-aos="fade-up" data-aos-delay="200">
                    <h3>Keunggulan MHI-38</h3>
                    <p class="fst-italic">
                        Berikut ini keunggulan Tes Mental Health menggunakan MHI-38:
                    </p>
                    <ul>
                        <li><i class="bi bi-check2-all"></i> <span>💡 Mengenali kondisi mental lebih awal.</span></li>
                        <li><i class="bi bi-check2-all"></i> <span>📊 Mendapat hasil secara cepat & mudah
                                dipahami.</span></li>
                        <li><i class="bi bi-check2-all"></i> <span>🔍 Berdasarkan penelitian ilmiah yang valid dan
                                reliable.</span></li>
                        <li><i class="bi bi-check2-all"></i> <span>🔗 Dapat digunakan sebagai referensi untuk konsultasi
                                ke profesional.</span></li>
                    </ul>
                    <h3>Cara Kerja Tes</h3>
                    <p>
                        1️⃣ Jawab 38 pertanyaan sesuai perasaan Anda saat ini <br>
                        2️⃣ Dapatkan hasil skor dan kategori kesehatan mental Anda <br>
                        3️⃣ Pelajari saran dan rekomendasi berdasarkan hasil tes <br>
                        4️⃣ Ambil langkah selanjutnya untuk menjaga kesehatan mental
                    </p>
                </div>
            </div>
        </div>
    </section>
</main><!-- /About Section -->

<x-footer></x-footer>
</body>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init();
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Core theme JS-->
<script src="js/script.js"></script>
<!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
<!-- * *                               SB Forms JS                               * *-->
<!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
<!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
<script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>

</html>
