<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ session('title', 'Default Title') }}</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.ico') }}" />
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/style-login.css" rel="stylesheet" />
    <!-- AOS Library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
</head>

<body>
    <div class="container">
        <div class="login-card">
            <div class="logo-header">
                <div class="logo-with-text">
                    <img src="{{ asset('assets/img/Logo_ITERA.png') }}" alt="Logo">
                    <div class="logo-text">
                        <h2>ANALOGY</h2>
                        <h4>PPSDM ITERA</h4>
                    </div>
                </div>
                <div class="logo-greeting">
                    <h1>Selamat Datang</h1>
                    <p>Silakan masuk ke akun Anda</p>
                </div>
            </div>

            <form action="{{ route('login.process') }}" method="POST">
                @csrf <!-- Token CSRF untuk keamanan -->
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email" class="input-field"
                        placeholder="Masukkan Email Anda" required>
                    <div class="input-icon"><i class="fa-regular fa-envelope"></i></div>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="input-field"
                        placeholder="Masukkan password Anda" required>
                    <span class="password-toggle" id="togglePassword">👁️</span>
                </div>
                <div class="show-password-checkbox">
                    <input type="checkbox" id="showPassword">
                    <label for="showPassword">Tampilkan password</label>
                </div>
                <button type="submit" class="login-btn">Masuk</button>
                <div class="signup-link">
                    Mahasiswa ITERA?
                    <a href="{{ route('google.redirect') }}" class="btn-165">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 262">
                            <path fill="#4285F4"
                                d="M255.878 133.451c0-10.734-.871-18.567-2.756-26.69H130.55v48.448h71.947c-1.45 12.04-9.283 30.172-26.69 42.356l-.244 1.622 38.755 30.023 2.685.268c24.659-22.774 38.875-56.282 38.875-96.027">
                            </path>
                            <path fill="#34A853"
                                d="M130.55 261.1c35.248 0 64.839-11.605 86.453-31.622l-41.196-31.913c-11.024 7.688-25.82 13.055-45.257 13.055-34.523 0-63.824-22.773-74.269-54.25l-1.531.13-40.298 31.187-.527 1.465C35.393 231.798 79.49 261.1 130.55 261.1">
                            </path>
                            <path fill="#FBBC05"
                                d="M56.281 156.37c-2.756-8.123-4.351-16.827-4.351-25.82 0-8.994 1.595-17.697 4.206-25.82l-.073-1.73L15.26 71.312l-1.335.635C5.077 89.644 0 109.517 0 130.55s5.077 40.905 13.925 58.602l42.356-32.782">
                            </path>
                            <path fill="#EB4335"
                                d="M130.55 50.479c24.514 0 41.05 10.589 50.479 19.438l36.844-35.974C195.245 12.91 165.798 0 130.55 0 79.49 0 35.393 29.301 13.925 71.947l42.211 32.783c10.59-31.477 39.891-54.251 74.414-54.251">
                            </path>
                        </svg>
                        <span>Login with Google</span>
                    </a>
                </div>

            </form>
        </div>
    </div>
</body>
<script src="{{ asset('js/script-login.js') }}"></script>
@if (session('error'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Login Gagal',
            text: '{{ session('error') }}',
            confirmButtonColor: '#344cbb'
        });
    </script>
@endif

@if (session('success'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{{ session('success') }}',
            confirmButtonColor: '#344cbb'
        });
    </script>
@endif

</html>
