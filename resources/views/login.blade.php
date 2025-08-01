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
            <div class="login-header">
                <h1>Selamat Datang</h1>
                <p>Silakan masuk ke akun Anda</p>
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
                <div class="forgot-password">
                    <a href="/lupa-password">Lupa password?</a>
                </div>
                <div class="signup-link">
                    Belum punya akun? <a href="/register">Daftar sekarang</a>
                </div>
            </form>
        </div>
    </div>
</body>
<script src="{{ asset('js/script-login.js') }}"></script>

</html>
