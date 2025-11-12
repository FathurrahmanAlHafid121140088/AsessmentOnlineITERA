<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Isi Data Diri</title>
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
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('css/styleform.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style-footer.css') }}" rel="stylesheet">

</head>
<x-navbar></x-navbar>



<body>
    <main>
        <div class="formbold-main-wrapper">
            <div class="formbold-form-wrapper">
                <div class="text-center">
                    <img src="../assets/img/img-form.png" style="width: 250px">
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger"
                        style="background-color: #f8d7da; color: #721c24; padding: 1rem; border: 1px solid #f5c6cb; border-radius: .25rem; margin-bottom: 1rem;">
                        <strong>Mohon periksa kembali data Anda!</strong>
                        <ul style="margin-top: 0.5rem; margin-bottom: 0; padding-left: 1.5rem;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form id="form" action="{{ route('karir.datadiri.store') }}" method="POST"> @csrf
                    <div class="formbold-form-title">
                        <h2 class="">Form Data Diri</h2>
                        <p>Isi data diri dibawah ini dengan benar.</p>
                    </div>

                    <div class="formbold-input-flex">
                        <div>
                            <label for="nama" class="formbold-form-label">
                                <i class="fas fa-user fa-sm"></i> Nama
                            </label>
                            <input type="text" name="nama" id="nama" class="formbold-form-input"
                                placeholder="Masukkan nama lengkap" value="{{ old('nama', Auth::user()->name) }}" required />
                        </div>
                        <div>
                            <label for="nim" class="formbold-form-label">
                                <i class="fas fa-id-card fa-sm"></i> NIM
                            </label>
                            <input type="number" id="nim_display" class="formbold-form-input"
                                value="{{ Auth::user()->nim }}" disabled
                                style="background-color: #f0f2f5; cursor: not-allowed;" />
                        </div>
                    </div>
                    <div class="formbold-input-flex">
                        <div>
                            <label class="formbold-form-label">
                                <i class="fas fa-venus-mars fa-sm"></i> Jenis Kelamin
                            </label>
                            <div>
                                <label class="formbold-radio-label">
                                    <input type="radio" name="jenis_kelamin" value="L"
                                        class="formbold-input-radio" required> Laki-laki
                                </label>
                            </div>
                            <div>
                                <label class="formbold-radio-label">
                                    <input type="radio" name="jenis_kelamin" value="P"
                                        class="formbold-input-radio" required> Perempuan
                                </label>
                            </div>
                        </div>

                        <div class="formbold-mb-3">
                            <label for="provinsi" class="formbold-form-label">
                                <i class="fas fa-map-marked-alt fa-sm"></i> Asal Provinsi
                            </label>
                            <select name="provinsi" id="provinsi" class="formbold-form-input" required>
                                <option value="" disabled selected>Pilih Provinsi</option>
                                <option value="Aceh">Aceh</option>
                                <option value="Sumatera Utara">Sumatera Utara</option>
                                <option value="Sumatera Barat">Sumatera Barat</option>
                                <option value="Riau">Riau</option>
                                <option value="Jambi">Jambi</option>
                                <option value="Sumatera Selatan">Sumatera Selatan</option>
                                <option value="Bengkulu">Bengkulu</option>
                                <option value="Lampung">Lampung</option>
                                <option value="Kepulauan Bangka Belitung">Kepulauan Bangka Belitung</option>
                                <option value="Kepulauan Riau">Kepulauan Riau</option>
                                <option value="DKI Jakarta">DKI Jakarta</option>
                                <option value="Jawa Barat">Jawa Barat</option>
                                <option value="Jawa Tengah">Jawa Tengah</option>
                                <option value="DI Yogyakarta">DI Yogyakarta</option>
                                <option value="Jawa Timur">Jawa Timur</option>
                                <option value="Banten">Banten</option>
                                <option value="Bali">Bali</option>
                                <option value="Nusa Tenggara Barat">Nusa Tenggara Barat</option>
                                <option value="Nusa Tenggara Timur">Nusa Tenggara Timur</option>
                                <option value="Kalimantan Barat">Kalimantan Barat</option>
                                <option value="Kalimantan Tengah">Kalimantan Tengah</option>
                                <option value="Kalimantan Selatan">Kalimantan Selatan</option>
                                <option value="Kalimantan Timur">Kalimantan Timur</option>
                                <option value="Kalimantan Utara">Kalimantan Utara</option>
                                <option value="Sulawesi Utara">Sulawesi Utara</option>
                                <option value="Sulawesi Tengah">Sulawesi Tengah</option>
                                <option value="Sulawesi Selatan">Sulawesi Selatan</option>
                                <option value="Sulawesi Tenggara">Sulawesi Tenggara</option>
                                <option value="Gorontalo">Gorontalo</option>
                                <option value="Sulawesi Barat">Sulawesi Barat</option>
                                <option value="Maluku">Maluku</option>
                                <option value="Maluku Utara">Maluku Utara</option>
                                <option value="Papua">Papua</option>
                                <option value="Papua Barat">Papua Barat</option>
                                <option value="Papua Selatan">Papua Selatan</option>
                                <option value="Papua Tengah">Papua Tengah</option>
                                <option value="Papua Pegunungan">Papua Pegunungan</option>
                            </select>
                        </div>
                    </div>

                    <div class="formbold-input-flex">
                        <div>
                            <label for="alamat" class="formbold-form-label">
                                <i class="fas fa-home fa-sm"></i> Alamat
                            </label>
                            <textarea name="alamat" id="alamat" class="formbold-form-input" placeholder="Masukkan alamat" rows="3"
                                required></textarea>
                        </div>
                        <div>
                            <label for="usia" class="formbold-form-label">
                                <i class="fas fa-birthday-cake fa-sm"></i> Usia
                            </label>
                            <input type="number" name="usia" id="usia" class="formbold-form-input"
                                placeholder="Masukkan usia" required min="1" max="150" />
                        </div>
                    </div>

                    <div class="formbold-mb-3">
                        <label for="fakultas" class="formbold-form-label">
                            <i class="fas fa-university fa-sm"></i> Fakultas
                        </label>
                        <div>
                            <label class="formbold-radio-label">
                                <input type="radio" name="fakultas" value="FS" class="formbold-input-radio"
                                    required onchange="updateProdi()"> FS
                            </label>
                        </div>

                        <div>
                            <label class="formbold-radio-label">
                                <input type="radio" name="fakultas" value="FTIK" class="formbold-input-radio"
                                    required onchange="updateProdi()">
                                FTIK
                            </label>
                        </div>

                        <div>
                            <label class="formbold-radio-label">
                                <input type="radio" name="fakultas" value="FTI" class="formbold-input-radio"
                                    required onchange="updateProdi()"> FTI
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="formbold-mb-3">
                                <label for="prodi" class="formbold-form-label">
                                    <i class="fas fa-book-open fa-sm"></i> Program Studi
                                </label>
                                <select name="program_studi" id="program_studi" class="formbold-form-input" required>
                                    <option value="" disabled selected>Pilih program studi</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="formbold-mb-3">
                                <label for="sekolah" class="formbold-form-label">
                                    <i class="fas fa-school fa-sm"></i> Asal Sekolah
                                </label>
                                <select name="asal_sekolah" id="asal_sekolah" class="formbold-form-input" required>
                                    <option value="" disabled selected>Pilih asal sekolah</option>
                                    <option value="SMA">SMA</option>
                                    <option value="SMK">SMK</option>
                                    <option value="Boarding School">Boarding School</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="formbold-mb-3">
                        <label class="formbold-form-label">
                            <i class="fas fa-question-circle fa-sm"></i> Apakah program studi saat ini yang anda pilih sesuai dengan apa yang anda inginkan?
                        </label>
                        <div>
                            <label class="formbold-radio-label">
                                <input type="radio" name="prodi_sesuai_keinginan" value="Ya"
                                    class="formbold-input-radio" required> Ya
                            </label>
                        </div>
                        <div>
                            <label class="formbold-radio-label">
                                <input type="radio" name="prodi_sesuai_keinginan" value="Tidak"
                                    class="formbold-input-radio"> Tidak
                            </label>
                        </div>
                    </div>

                    <div class="formbold-input-flex">
                        <div>
                            <div class="formbold-mb-3">
                                <label for="email" class="formbold-form-label">
                                    <i class="fas fa-envelope fa-sm"></i> Email
                                </label>
                                <input type="email" name="email" id="email" class="formbold-form-input"
                                    placeholder="Masukkan email" value="{{ old('email', Auth::user()->email) }}" required />
                            </div>
                        </div>
                        <div class="formbold-mb-3">
                            <label for="status_tinggal" class="formbold-form-label">
                                <i class="fas fa-house-user fa-sm"></i> Status Tinggal
                            </label>
                            <div>
                                <label class="formbold-radio-label">
                                    <input type="radio" name="status_tinggal" value="Bersama Orang Tua"
                                        class="formbold-input-radio" required> Bersama orang tua
                                </label>
                            </div>
                            <div>
                                <label class="formbold-radio-label">
                                    <input type="radio" name="status_tinggal" value="Kost"
                                        class="formbold-input-radio"> Kost
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn-submit" id="submit-button">
                            <span>
                                Submit
                                <i class="fas fa-paper-plane"></i>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </main>
</body>
<x-footer></x-footer>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Core theme JS-->
<script src="js/script.js"></script>
<script src="{{ asset('js/karir-script-datadiri.js') }}"></script>
<!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
<!-- * *                               SB Forms JS                               * *-->
<!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
<!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->

</html>
