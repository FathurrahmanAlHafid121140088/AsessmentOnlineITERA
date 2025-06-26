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

</head>
<x-navbar></x-navbar>

<body>
    <main>
        <div class="formbold-main-wrapper">
            <div class="formbold-form-wrapper">
                <div class="text-center">
                    <img src="../assets/img/img-form.png" style="width: 250px">
                </div>
                <form action="{{ route('mental-health.store-data-diri') }}" method="POST"> @csrf
                    <div class="formbold-form-title">
                        <h2 class="">Form Data Diri</h2>
                        <p>Isi data diri dibawah ini dengan benar.</p>
                    </div>

                    <div class="formbold-input-flex">
                        <div>
                            <label for="nama" class="formbold-form-label"> Nama </label>
                            <input type="text" name="nama" id="nama" class="formbold-form-input"
                                placeholder="Masukkan nama lengkap" required />
                        </div>
                        <div>
                            <label for="nim" class="formbold-form-label"> NIM </label>
                            <input type="number" name="nim" id="nim" class="formbold-form-input"
                                placeholder="Masukkan NIM" required />
                        </div>
                    </div>

                    <div class="formbold-mb-3">
                        <label class="formbold-form-label">Jenis Kelamin</label>
                        <div>
                            <label class="formbold-radio-label">
                                <input type="radio" name="jenis_kelamin" value="L" class="formbold-input-radio"
                                    required> Laki-laki
                            </label>
                        </div>
                        <div>
                            <label class="formbold-radio-label">
                                <input type="radio" name="jenis_kelamin" value="P" class="formbold-input-radio"
                                    required> Perempuan
                            </label>
                        </div>
                    </div>

                    <div class="formbold-input-flex">
                        <div>
                            <label for="alamat" class="formbold-form-label"> Alamat </label>
                            <textarea name="alamat" id="alamat" class="formbold-form-input" placeholder="Masukkan alamat" rows="3"
                                required></textarea>
                        </div>
                        <div>
                            <label for="usia" class="formbold-form-label"> Usia </label>
                            <input type="number" name="usia" id="usia" class="formbold-form-input"
                                placeholder="Masukkan usia" required min="1" max="150" />
                        </div>
                    </div>

                    <div class="formbold-mb-3">
                        <label class="formbold-form-label">Fakultas</label>

                        <div>
                            <label class="formbold-radio-label">
                                <input type="radio" name="fakultas" value="Fakultas Sains"
                                    class="formbold-input-radio" required onchange="updateProdi()"> Fakultas Sains
                            </label>
                        </div>

                        <div>
                            <label class="formbold-radio-label">
                                <input type="radio" name="fakultas"
                                    value="Fakultas Teknologi Infrastruktur dan Kewilayahan"
                                    class="formbold-input-radio" required onchange="updateProdi()">
                                Fakultas Teknologi Infrastruktur dan Kewilayahan
                            </label>
                        </div>

                        <div>
                            <label class="formbold-radio-label">
                                <input type="radio" name="fakultas" value="Fakultas Teknologi Industri"
                                    class="formbold-input-radio" required onchange="updateProdi()"> Fakultas Teknologi
                                Industri
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="formbold-mb-3">
                                <label for="program_studi" class="formbold-form-label">Program Studi</label>
                                <select name="program_studi" id="program_studi" class="formbold-form-input" required>
                                    <option value="" disabled selected>Pilih program studi</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="formbold-mb-3">
                                <label for="email" class="formbold-form-label"> Email </label>
                                <input type="email" name="email" id="email" class="formbold-form-input"
                                    placeholder="Masukkan email" required />
                            </div>
                        </div>
                    </div>

                    <div class="formbold-input-flex">
                        <div>
                            <label for="keluhan" class="formbold-form-label"> Keluhan </label>
                            <textarea name="keluhan" id="keluhan" class="formbold-form-input" rows="4"
                                placeholder="Masukkan keluhan yang dialami saat ini" required></textarea>
                        </div>

                        <div class="formbold-mb-3">
                            <label class="formbold-form-label">Lama Keluhan (dalam bulan)</label>
                            <div>
                                <label class="formbold-radio-label">
                                    <input type="radio" name="lama_keluhan" value="1"
                                        class="formbold-input-radio" required> 1 Bulan
                                </label>
                            </div>
                            <div>
                                <label class="formbold-radio-label">
                                    <input type="radio" name="lama_keluhan" value="2"
                                        class="formbold-input-radio"> 2 Bulan
                                </label>
                            </div>
                            <div>
                                <label class="formbold-radio-label">
                                    <input type="radio" name="lama_keluhan" value="3"
                                        class="formbold-input-radio"> 3 Bulan
                                </label>
                            </div>
                            <div>
                                <label class="formbold-radio-label">
                                    <input type="radio" name="lama_keluhan" value="4"
                                        class="formbold-input-radio"> 4 Bulan
                                </label>
                            </div>
                            <div>
                                <label class="formbold-radio-label">
                                    <input type="radio" name="lama_keluhan" value="5"
                                        class="formbold-input-radio"> 5 Bulan
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="formbold-input-flex">
                        <div class="formbold-mb-3">
                            <label class="formbold-form-label">Sudah Pernah Mengikuti Tes Psikologi?</label>
                            <div>
                                <label class="formbold-radio-label">
                                    <input type="radio" name="pernah_tes" value="Ya"
                                        class="formbold-input-radio" required> Ya
                                </label>
                            </div>
                            <div>
                                <label class="formbold-radio-label">
                                    <input type="radio" name="pernah_tes" value="Tidak"
                                        class="formbold-input-radio"> Tidak
                                </label>
                            </div>
                        </div>
                        <div class="formbold-mb-3">
                            <label class="formbold-form-label">Sudah Pernah Konsultasi dengan Psikolog?</label>
                            <div>
                                <label class="formbold-radio-label">
                                    <input type="radio" name="pernah_konsul" value="Ya"
                                        class="formbold-input-radio" required> Ya
                                </label>
                            </div>
                            <div>
                                <label class="formbold-radio-label">
                                    <input type="radio" name="pernah_konsul" value="Tidak"
                                        class="formbold-input-radio"> Tidak
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn-submit" id="submit-button">
                            <span>
                                Submit
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <g stroke-width="0" id="SVGRepo_bgCarrier"></g>
                                    <g stroke-linejoin="round" stroke-linecap="round" id="SVGRepo_tracerCarrier"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path fill="#ffffff"
                                            d="M20.33 3.66996C20.1408 3.48213 19.9035 3.35008 19.6442 3.28833C19.3849 3.22659 19.1135 3.23753 18.86 3.31996L4.23 8.19996C3.95867 8.28593 3.71891 8.45039 3.54099 8.67255C3.36307 8.89471 3.25498 9.16462 3.23037 9.44818C3.20576 9.73174 3.26573 10.0162 3.40271 10.2657C3.5397 10.5152 3.74754 10.7185 4 10.85L10.07 13.85L13.07 19.94C13.1906 20.1783 13.3751 20.3785 13.6029 20.518C13.8307 20.6575 14.0929 20.7309 14.36 20.73H14.46C14.7461 20.7262 15.0199 20.6269 15.2439 20.4474C15.4679 20.2679 15.628 20.0184 15.7 19.73L20.55 4.10996C20.6274 3.85435 20.615 3.58147 20.5147 3.33484C20.4144 3.08822 20.2336 2.87921 20.0013 2.74113C19.7691 2.60305 19.4959 2.54454 19.225 2.57751C18.954 2.61048 18.7005 2.73366 18.5 2.92996L14.21 7.10996L10.58 4.73996L19.66 3.24996C19.984 3.20112 20.2933 3.06673 20.5337 2.86512C20.7741 2.6635 20.9355 2.40406 20.9946 2.11819C21.0536 1.83233 21.0078 1.53699 20.8665 1.28631C20.7252 1.03563 20.4972 0.838383 20.2223 0.732496C19.9475 0.626609 19.644 0.620237 19.363 0.71437C19.0819 0.808503 18.8414 0.999229 18.68 1.25996L12.07 9.66996L6.15 4.85996C5.98744 4.71724 5.78033 4.62711 5.55746 4.60562C5.33458 4.58414 5.11094 4.63282 4.91833 4.74312C4.72572 4.85342 4.57521 5.01921 4.48847 5.21444C4.40172 5.40968 4.38484 5.62654 4.44156 5.83172C4.49827 6.0369 4.62537 6.216 4.80199 6.33321C4.97862 6.45042 5.19311 6.49996 5.41 6.46996L12.57 6.29996L15.21 9.19996L14.47 13.57L6.85 19.16C6.7034 19.2755 6.57822 19.4195 6.48272 19.5842C6.38722 19.7489 6.32412 19.9308 6.29781 20.1189C6.27149 20.3069 6.28239 20.4973 6.32944 20.6783C6.37649 20.8592 6.4581 21.026 6.56954 21.1658C6.68099 21.3056 6.81848 21.4141 6.972 21.481C7.12552 21.5478 7.29093 21.5706 7.454 21.547C7.61707 21.5234 7.77399 21.4536 7.908 21.344L15.55 16.06L20.33 3.66996Z" />
                                    </g>
                                </svg>
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
<script src="{{ asset('js/script-datadiri.js') }}"></script>
<!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
<!-- * *                               SB Forms JS                               * *-->
<!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
<!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
<script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>

</html>
