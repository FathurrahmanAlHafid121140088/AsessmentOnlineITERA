<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
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
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/style.css" rel="stylesheet" />
    <link href="css/style-footer.css" rel="stylesheet" />

    <!-- AOS Library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">

</head>
<x-navbar></x-navbar>

<body id="page-top">
    <!-- Navigation-->
    <!-- Masthead-->
    <header class="masthead">
        <div class="container">
            <div class="masthead-subheading" data-aos="fade-down" data-aos-delay="100">Selamat Datang Di</div>
            <div class="masthead-heading text-uppercase" data-aos="fade-down" data-aos-delay="100">Assessment Online
                Psychology</div>
            <div class="masthead-subheading" data-aos="fade-down" data-aos-delay="150">Pusat Pengembangan Sumber Daya
                Manusia</div>
            <div class="masthead-subheading" data-aos="fade-down" data-aos-delay="200">Institut Teknologi Sumatera</div>
            <a class="btn btn-primary btn-xl text-uppercase" href="#services" data-aos="fade-down"
                data-aos-delay="250">Ayo Mulai Tes!</a>
        </div>

    </header>
    <!-- About-->
    <section class="page-section" id="about">
        <div class="container">
            <div class="text-center">
                <h2 data-aos="fade-down" data-aos-delay="100" class="section-heading text-uppercase">Langkah-langkah
                </h2>
                <h3 data-aos="fade-down" data-aos-delay="100" class="section-subheading text-muted">Melakukan Assessment
                    Online Psychology.</h3>
            </div>
            <ul class="timeline">
                <li data-aos="fade-right" data-aos-delay="100">
                    <div class="timeline-image"><img class="rounded-circle img-fluid" src="assets/img/about/1.jpg"
                            alt="..." /></div>
                    <div class="timeline-panel">
                        <div class="timeline-heading">
                            <h4 class="subheading">1️⃣ Registrasi & Login</h4>
                        </div>
                        <div class="timeline-body">
                            <p class="text-muted">Buat akun atau masuk ke sistem dengan username dan password.
                                <br>Pastikan data yang diinput aman dan rahasia.
                            </p>
                        </div>
                    </div>
                </li>
                <li data-aos="fade-left" data-aos-delay="100" class="timeline-inverted">
                    <div class="timeline-image"><img class="rounded-circle img-fluid" src="assets/img/about/2.jpg"
                            alt="..." /></div>
                    <div class="timeline-panel">
                        <div class="timeline-heading">
                            <h4 class="subheading">2️⃣ Pilih Jenis Tes</h4>
                        </div>
                        <div class="timeline-body">
                            <p class="text-muted">- Tes Mental Health → Cek tingkat stres, kecemasan, dan kesejahteraan
                                emosional.
                                <br> - Tes Peminatan Karir → Identifikasi minat dan potensi karir yang sesuai.
                            </p>
                        </div>
                    </div>
                </li>
                <li data-aos="fade-right" data-aos-delay="100">
                    <div class="timeline-image"><img class="rounded-circle img-fluid" src="assets/img/about/3.jpg"
                            alt="..." /></div>
                    <div class="timeline-panel">
                        <div class="timeline-heading">
                            <h4 class="subheading">3️⃣ Isi Kuesioner</h4>
                        </div>
                        <div class="timeline-body">
                            <p class="text-muted">Jawab pertanyaan dengan jujur sesuai kondisi Anda.
                                <br>Waktu pengerjaan sekitar 10-20 menit.
                            </p>
                        </div>
                    </div>
                </li>
                <li data-aos="fade-left" data-aos-delay="100" class="timeline-inverted">
                    <div class="timeline-image"><img class="rounded-circle img-fluid" src="assets/img/about/4.jpg"
                            alt="..." /></div>
                    <div class="timeline-panel">
                        <div class="timeline-heading">
                            <h4 class="subheading">4️⃣ Dapatkan Hasil Instan</h4>
                        </div>
                        <div class="timeline-body">
                            <p class="text-muted">Hasil otomatis ditampilkan setelah tes selesai.
                                <br> Skor dan interpretasi tersedia dalam bentuk diagnosis umum serta saran.
                            </p>
                        </div>
                    </div>
                </li>
                <li data-aos="fade-right" data-aos-delay="100">
                    <div class="timeline-image"><img class="rounded-circle img-fluid" src="assets/img/about/3.jpg"
                            alt="..." /></div>
                    <div class="timeline-panel">
                        <div class="timeline-heading">
                            <h4 class="subheading">5️⃣ Konsultasi dengan Psikolog (Opsional)</h4>
                        </div>
                        <div class="timeline-body">
                            <p class="text-muted">Jika membutuhkan saran lebih lanjut, Anda dapat menjadwalkan
                                konsultasi dengan psikolog profesional.</p>
                        </div>
                    </div>
                </li>
                <li class="timeline-inverted">
                    <div data-aos="zoom-in" data-aos-delay="00" class="timeline-image">
                        <h4>
                            Ayo
                            <br />
                            Cek
                            <br />
                            Keadaanmu!
                        </h4>
                    </div>
                </li>
            </ul>
        </div>
    </section>
    <!-- Services-->
    <section class="page-section" id="services" style="background-color: #f2f2f2;">
        <div class="container">
            <div class="text-center">
                <h2 data-aos="fade-down" data-aos-delay="100" class="section-heading text-uppercase">Layanan Kami
                </h2>
                <h3 data-aos="fade-down" data-aos-delay="100" class="section-subheading text-muted">Silahkan Pilih
                    Sesuai Dengan Keadaan Anda Saat Ini</h3>
            </div>
            <div class="row text-center g-4 ">
                <div data-aos="flip-right" data-aos-delay="100"
                    class="col-md-6 col-12 p-4 bg-white shadow-sm rounded-4">
                    <span class="fa-stack fa-4x">
                        <i class="fas fa-circle fa-stack-2x" style="color: #4361ee;"></i>
                        <i class="fas fa-brain fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="my-3">Mental Health</h4>
                    <p>Cek tingkat stres, kecemasan, dan kesehatan emosional Anda dengan metode ilmiah yang terpercaya.
                    </p>
                    <p class="text-muted">Metode RMIH-38 (RAND Mental Health Inventory-38) adalah alat evaluasi
                        kesehatan mental yang dirancang untuk menganalisis tingkat stres, kecemasan, dan kesejahteraan
                        emosional secara cepat dan akurat. Dengan 38 parameter berbasis penelitian ilmiah, metode ini
                        memberikan hasil instan yang dapat digunakan sebagai dasar konsultasi dengan psikolog.</p>
                    <a class="btn btn-primary btn-xl text-uppercase" href="/mental-health">Mulai Tes!</a>
                </div>

                <div data-aos="flip-left" data-aos-delay="100"
                    class="col-md-6 col-12 p-4 bg-white shadow-sm rounded-4">
                    <span class="fa-stack fa-4x" style="color: #4361ee;">
                        <i class="fas fa-circle fa-stack-2x text-primary" style="color: #4361ee;"></i>
                        <i class="fas fa-laptop fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="my-3">Peminatan Karir</h4>
                    <p>Temukan pekerjaan yang sesuai dengan bakat dan kepribadian Anda.</p>
                    <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima maxime quam
                        architecto quo inventore harum ex magni, dicta impedit.</p>
                    <a class="btn btn-primary btn-xl text-uppercase" href="/karir-home">Tes Sekarang!</a>
                </div>
            </div>
        </div>
    </section>

    <section class="vh-50">
        <div data-aos="fade-down" data-aos-delay="200" class="text-center">
            <h2 class="section-heading text-uppercase pt-4">Quotes</h2>
        </div>
        <div class="container py-4 h-100">
            <div class="d-flex row justify-content-center g-3">

                <!-- Card 1 -->
                <div data-aos="zoom-in" data-aos-delay="100" class="col-md-6 col-12">
                    <div class="card rounded-3"style="background-color: #f2f2f2;">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-center mb-3">
                                <img src="{{ asset('img/Noam-Shpancer-500x765.jpg') }}" alt="..."
                                    class="rounded-circle shadow-1-strong" width="80" height="80" />
                            </div>
                            <figure class="text-center mb-0">
                                <blockquote class="blockquote mb-3">
                                    <p><span class="font-italic">
                                            "Mental health… is not a destination, but a process. It's about how you
                                            drive, not where you're going."
                                        </span></p>
                                </blockquote>
                                <figcaption class="blockquote-footer mb-0">
                                    Noam Shpancer, Ph.D
                                </figcaption>
                            </figure>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div data-aos="zoom-in" data-aos-delay="100" class="col-md-6 col-12">
                    <div class="card rounded-3"style="background-color: #f2f2f2;">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-center mb-3">
                                <img src="{{ asset('img/Steve_Jobs_Headshot_2010-CROP_(cropped_2).jpg') }}"
                                    alt="..." class="rounded-circle shadow-1-strong" width="80"
                                    height="80" />
                            </div>
                            <figure class="text-center mb-0">
                                <blockquote class="blockquote mb-3">
                                    <p><span class="font-italic">
                                            "Your work is going to fill a large part of your life, and the only way to
                                            be truly satisfied is to do what you believe is great work."
                                        </span></p>
                                </blockquote>
                                <figcaption class="blockquote-footer mb-0">
                                    Steve Jobs
                                </figcaption>
                            </figure>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</body>
<!-- Footer-->
<x-footer></x-footer>
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
