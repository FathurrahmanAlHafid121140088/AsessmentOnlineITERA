<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>{{ $title }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.ico') }}" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />

    @vite(['resources/css/app-mh-quiz.css'])
</head>

<body>
    <x-navbar></x-navbar>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7 quiz">
                <div class="quiz-container">

                    <h2 class="quiz-title">
                        <p class="intro-user">
                            <i class="fas fa-user-circle me-2 text-primary"></i>Halo,
                            <strong>{{ session('nama') }}</strong><br>
                            <i class="fas fa-graduation-cap me-2 text-secondary"></i>Dari
                            <strong>{{ session('program_studi') }}</strong>
                        </p>
                        <i class="fas fa-brain me-2"></i>Penilaian Mental Health MHI-38
                    </h2>

                    <hr class="my-4">

                    <div class="instruction-area py-3">

                        <div class="text-center mb-5">
                            <div class="d-inline-block p-3 rounded-circle bg-light mb-3">
                                <i class="fas fa-clipboard-list fa-3x text-primary opacity-75"
                                    style="color: #4361ee"></i>
                            </div>
                            <h4 class="fw-bold" style="color: #4361ee">Selamat Datang</h4>
                            <p class="text-muted mx-auto">
                                Anda akan mengikuti tes <strong>MHI-38</strong> untuk mengukur tingkat kesehatan
                                mental anda secara umum.
                            </p>
                        </div>

                        <div class="row mb-4 g-3">

                            <div class="col-md-6">
                                <div class="mh-feature-item">
                                    <div class="mh-icon-wrapper">
                                        <i class="fas fa-list-ol"></i>
                                    </div>
                                    <div class="mh-text-content">
                                        <h6 class="fw-bold">38 Pertanyaan</h6>
                                        <small class="text-muted">Pilihan Ganda</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mh-feature-item">
                                    <div class="mh-icon-wrapper">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="mh-text-content">
                                        <h6 class="fw-bold">5-10 Menit</h6>
                                        <small class="text-muted">Estimasi Waktu</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mh-feature-item">
                                    <div class="mh-icon-wrapper">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div class="mh-text-content">
                                        <h6 class="fw-bold">Tidak ada Jawaban Salah</h6>
                                        <small class="text-muted">Jawab sesuai keadaan anda saat ini</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mh-feature-item">
                                    <div class="mh-icon-wrapper">
                                        <i class="fas fa-lock"></i>
                                    </div>
                                    <div class="mh-text-content">
                                        <h6 class="fw-bold">One-Way System</h6>
                                        <small class="text-muted">Tidak bisa kembali ke soal sebelumnya</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mh-feature-item">
                                    <div class="mh-icon-wrapper">
                                        <i class="fas fa-user-shield"></i>
                                    </div>
                                    <div class="mh-text-content">
                                        <h6 class="fw-bold">Privasi Terjamin</h6>
                                        <small class="text-muted">Data dijamin kerahasiaannya</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mh-feature-item">
                                    <div class="mh-icon-wrapper">
                                        <i class="fas fa-building-columns"></i>
                                    </div>
                                    <div class="mh-text-content">
                                        <h6 class="fw-bold">Resmi PPSDM ITERA</h6>
                                        <small class="text-muted">Dikelola Sub Divisi PPSDM</small>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="mh-caution-content">
                            <strong class="d-block mh-caution-title mb-2">
                                <i class="fas fa-exclamation-triangle me-1 d-md-none"></i> Disclaimer Penting:
                            </strong>
                            <p class="mh-caution-text mb-0">
                                Tes ini merupakan alat <strong>skrining awal</strong> dan bukan pengganti diagnosa
                                medis/klinis profesional.
                                Hasil tes hanya memberikan gambaran kondisi saat ini. Jika Anda merasa membutuhkan
                                bantuan serius, hubungi profesional.
                            </p>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <a href="{{ route('quiz.start') }}" id="btn-start"
                                class="btn btn-primary btn-submit btn-lg shadow-sm py-3" style="border-radius: 50px;">
                                <i class="fas fa-play-circle me-2"></i> Saya Mengerti, Mulai Tes
                            </a>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <x-footer></x-footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startBtn = document.getElementById('btn-start');

            if (startBtn) {
                startBtn.addEventListener('click', function(e) {
                    e.preventDefault(); // Mencegah link langsung terbuka
                    const url = this.getAttribute('href'); // Ambil URL route('quiz.start')

                    Swal.fire({
                        title: 'Apakah Anda Siap?',
                        html: `
                        <div class="text-start">
                            <p>Mohon perhatikan hal berikut sebelum memulai:</p>
                            <ul class="mb-0 text-muted small" style="list-style-position: outside; padding-left: 1.25rem;">
                                <li>Pastikan koneksi internet lancar.</li>
                                <li>Sediakan waktu luang <strong>5-10 menit</strong>.</li>
                                <li class="mt-1">
                                    Jika browser tertutup, <span class="text-success fw-bold"><strong>jawaban tersimpan otomatis</strong></span>. 
                                    Jawab seluruh pertanyaan untuk melihat hasil.
                                </li>
                            </ul>
                        </div>
                    `,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#0d6efd',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Saya Siap!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Jika user klik Ya, arahkan ke URL start
                            window.location.href = url;
                        }
                    });
                });
            }
        });
    </script>

</body>

</html>
