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
            <form action="{{ url('/karir-form') }}" method="GET">
                <div class="formbold-form-title">
                    <h2 class="">Form Data Diri</h2>
                    <p>Isi data diri dibawah ini dengan benar.</p>
                </div>
    
                <div class="formbold-input-flex">
                    <div>
                        <label for="firstname" class="formbold-form-label"> First name </label>
                        <input type="text" name="firstname" id="firstname" class="formbold-form-input" placeholder="Masukkan nama depan" />
                    </div>
                    <div>
                        <label for="lastname" class="formbold-form-label"> Last name </label>
                        <input type="text" name="lastname" id="lastname" class="formbold-form-input" placeholder="Masukkan nama belakang" />
                    </div>
                </div>
    
                <div class="formbold-mb-3">
                    <label class="formbold-form-label">Jenis Kelamin</label>
                    <div>
                        <label class="formbold-radio-label">
                            <input type="radio" name="gender" value="Laki-laki" class="formbold-input-radio" required> Laki-laki
                        </label>
                    </div>
                    <div>
                        <label class="formbold-radio-label">
                            <input type="radio" name="gender" value="Perempuan" class="formbold-input-radio" required> Perempuan
                        </label>
                    </div>
                </div>
    
                <div class="formbold-input-flex">
                    <div>
                        <label for="email" class="formbold-form-label"> Email </label>
                        <input type="email" name="email" id="email" class="formbold-form-input" placeholder="Masukkan email" />
                    </div>
                    <div>
                        <label for="phone" class="formbold-form-label"> Phone number </label>
                        <input type="text" name="phone" id="phone" class="formbold-form-input" placeholder="Masukkan nomor telepon" />
                    </div>
                </div>
    
                <div class="row">
                    <div class="col-lg-6">
                        <div class="formbold-mb-3">
                            <label for="address" class="formbold-form-label"> Street Address </label>
                            <input type="text" name="address" id="address" class="formbold-form-input" placeholder="Masukkan alamat jalan" />
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="formbold-mb-3">
                            <label for="address2" class="formbold-form-label"> Street Address Line 2 </label>
                            <input type="text" name="address2" id="address2" class="formbold-form-input" placeholder="Opsional: Tambahan alamat" />
                        </div>
                    </div>
                </div>
    
                <div class="formbold-input-flex">
                    <div>
                        <label for="state" class="formbold-form-label"> State/Province </label>
                        <input type="text" name="state" id="state" class="formbold-form-input" placeholder="Masukkan provinsi" />
                    </div>
                    <div>
                        <label for="country" class="formbold-form-label"> Country </label>
                        <input type="text" name="country" id="country" class="formbold-form-input" placeholder="Masukkan negara" />
                    </div>
                </div>
    
                <div class="formbold-input-flex">
                    <div>
                        <label for="post" class="formbold-form-label"> Post/Zip code </label>
                        <input type="text" name="post" id="post" class="formbold-form-input" placeholder="Masukkan kode pos" />
                    </div>
                    <div>
                        <label for="area" class="formbold-form-label"> Area Code </label>
                        <input type="text" name="area" id="area" class="formbold-form-input" placeholder="Masukkan kode area" />
                    </div>
                </div>
    
                <div class="text-center">
                    <button class="btn-submit" id="submit-button">
                        <span>
                            Submit 
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <g stroke-width="0" id="SVGRepo_bgCarrier"></g>
                                <g stroke-linejoin="round" stroke-linecap="round" id="SVGRepo_tracerCarrier"></g>
                                <g id="SVGRepo_iconCarrier"> 
                                    <path fill="#ffffff" d="M20.33 3.66996C20.1408 3.48213 19.9035 3.35008 19.6442 3.28833C19.3849 3.22659 19.1135 3.23753 18.86 3.31996L4.23 8.19996C3.95867 8.28593 3.71891 8.45039 3.54099 8.67255C3.36307 8.89471 3.25498 9.16462 3.23037 9.44818C3.20576 9.73174 3.26573 10.0162 3.40271 10.2657C3.5397 10.5152 3.74754 10.7185 4 10.85L10.07 13.85L13.07 19.94C13.1906 20.1783 13.3751 20.3785 13.6029 20.518C13.8307 20.6575 14.0929 20.7309 14.36 20.73H14.46C14.7461 20.7089 15.0192 20.6023 15.2439 20.4239C15.4686 20.2456 15.6345 20.0038 15.72 19.73L20.67 5.13996C20.7584 4.88789 20.7734 4.6159 20.7132 4.35565C20.653 4.09541 20.5201 3.85762 20.33 3.66996ZM4.85 9.57996L17.62 5.31996L10.53 12.41L4.85 9.57996ZM14.43 19.15L11.59 13.47L18.68 6.37996L14.43 19.15Z"></path> 
                                </g>
                            </svg>
                        </span>
                        <span>Sure ?</span>
                        <span>Done!</span>
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
<script src="{{ asset('js/karir-datadiri.js') }}"></script>
<!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
<!-- * *                               SB Forms JS                               * *-->
<!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
<!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
<script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
</html>