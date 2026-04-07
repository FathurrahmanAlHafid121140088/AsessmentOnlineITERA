<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Informed Consent - Tes Minat RMIB" />
    <meta name="author" content="" />
    <title>Informed Consent - Tes RMIB</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.ico') }}" />
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <!-- Core theme CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style-footer.css') }}" rel="stylesheet">
    <!-- Consent Page CSS -->
    <link href="{{ asset('css/karir-consent.css') }}" rel="stylesheet">

    <style>
        /* Background image needs asset() helper so kept inline */
        .consent-page {
            background-image: url('{{ asset('assets/bg.svg') }}');
        }
    </style>
</head>

<body>
    <div class="consent-page">
        <div class="consent-card">
            <!-- Header -->
            <div class="consent-card-header">
                <div class="consent-icon-wrapper">
                    <i class="fas fa-clipboard-check" style="font-size: 3.5rem; color: #fff;"></i>
                </div>
                <h1>Informed Consent & Disclaimer</h1>
                <p>Tes Minat Rothwell-Miller Interest Blank (RMIB)</p>
            </div>

            <!-- Body -->
            <div class="consent-card-body">
                <!-- Informasi Umum Tes -->
                <div class="consent-box consent-box-info">
                    <div class="consent-box-header">
                        <i class="fas fa-info-circle"></i>
                        <span>Informasi Umum Tes</span>
                    </div>
                    <div class="consent-box-content">
                        <p>Anda akan mengerjakan <strong>Tes Minat Rothwell-Miller Interest Blank (RMIB)</strong>.</p>
                        <ul>
                            <li><strong>Tujuan:</strong> Mengukur minat pekerjaan untuk membantu Anda mengenali potensi
                                karir</li>
                            <li><strong>Durasi:</strong> Estimasi pengerjaan ±15-20 menit</li>
                            <li><strong>Format:</strong> 9 kelompok pekerjaan (A-I), masing-masing berisi 12 pekerjaan
                                yang harus diurutkan</li>
                        </ul>
                    </div>
                </div>

                <!-- Disclaimer Hasil Tes -->
                <div class="consent-box consent-box-warning">
                    <div class="consent-box-header">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Disclaimer Hasil Tes</span>
                    </div>
                    <div class="consent-box-content">
                        <ul>
                            <li>Hasil tes bersifat <strong>indikatif/panduan</strong>, bukan penentu mutlak pilihan
                                karir Anda</li>
                            <li>Tes ini <strong>bukan diagnosis psikologis</strong> profesional</li>
                            <li>Tes ini <strong>bukan untuk self-diagnosis</strong></li>
                            <li>Hasil sebaiknya dikonsultasikan dengan konselor/psikolog untuk interpretasi lebih
                                mendalam</li>
                            <li>Minat dapat berubah seiring waktu dan pengalaman hidup Anda</li>
                        </ul>
                    </div>
                </div>

                <!-- Privasi & Keamanan Data -->
                <div class="consent-box consent-box-privacy">
                    <div class="consent-box-header">
                        <i class="fas fa-shield-alt"></i>
                        <span>Privasi & Keamanan Data</span>
                    </div>
                    <div class="consent-box-content">
                        <p>Data yang dikumpulkan meliputi:</p>
                        <ul>
                            <li>Identitas (Nama, NIM, Email, Program Studi)</li>
                            <li>Data demografis (Jenis Kelamin, Usia, Provinsi, Status Tinggal)</li>
                            <li>Jawaban tes dan hasil analisis minat</li>
                        </ul>
                        <p class="mb-0"><strong>Jaminan:</strong> Data Anda disimpan secara aman, digunakan hanya
                            untuk keperluan analisis dan pengembangan layanan, serta tidak akan dibagikan ke pihak
                            ketiga tanpa persetujuan.</p>
                    </div>
                </div>

                <!-- Instruksi Pengerjaan -->
                <div class="consent-box consent-box-success">
                    <div class="consent-box-header">
                        <i class="fas fa-list-ol"></i>
                        <span>Instruksi Pengerjaan</span>
                    </div>
                    <div class="consent-box-content">
                        <ul>
                            <li>Jawab dengan <strong>jujur</strong> sesuai minat pribadi Anda</li>
                            <li>Tidak ada jawaban benar atau salah</li>
                            <li><strong>Perhatian:</strong> Jawaban tidak dapat diubah setelah melanjutkan ke kelompok
                                berikutnya</li>
                            <li>Pastikan koneksi internet Anda stabil selama pengerjaan</li>
                            <li>Progress Anda akan tersimpan otomatis jika terjadi gangguan koneksi</li>
                        </ul>
                    </div>
                </div>

                <!-- Checkbox Persetujuan -->
                <div class="consent-box consent-box-checkbox">
                    <div class="consent-box-header">
                        <i class="fas fa-check-square"></i>
                        <span>Pernyataan Persetujuan</span>
                    </div>
                    <div class="consent-box-content">
                        <div class="form-check">
                            <input class="form-check-input consent-checkbox" type="checkbox" id="consent1">
                            <label class="form-check-label" for="consent1">
                                Saya telah membaca dan memahami informasi di atas
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input consent-checkbox" type="checkbox" id="consent2">
                            <label class="form-check-label" for="consent2">
                                Saya menyetujui pengumpulan dan penggunaan data saya untuk keperluan tes ini
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input consent-checkbox" type="checkbox" id="consent3">
                            <label class="form-check-label" for="consent3">
                                Saya memahami bahwa hasil tes bersifat indikatif dan bukan diagnosis profesional
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <form action="{{ route('karir.consent.store') }}" method="POST" id="consentForm">
                @csrf
                <div class="consent-card-footer">
                    <a href="{{ route('karir.dashboard') }}" class="btn-consent-back">
                        <i></i>Kembali
                    </a>
                    <button type="submit" class="btn btn-consent-agree" id="btnAgreeConsent" disabled>
                        <i></i>Saya Setuju & Lanjutkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnAgree = document.getElementById('btnAgreeConsent');
            const checkboxes = document.querySelectorAll('.consent-checkbox');

            // Check all checkboxes to enable/disable button
            function updateButtonState() {
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                btnAgree.disabled = !allChecked;
            }

            checkboxes.forEach(cb => {
                cb.addEventListener('change', updateButtonState);
            });
        });
    </script>
</body>

</html>
