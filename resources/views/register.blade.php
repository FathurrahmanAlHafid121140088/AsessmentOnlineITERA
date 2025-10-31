<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
            <!-- Favicon-->
            <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.ico') }}" />
            <!-- Font Awesome icons (free version)-->
            <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
            <!-- Google fonts-->
            <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
            <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
            <!-- Core theme CSS (includes Bootstrap)-->
            @vite(['resources/css/app-auth.css'])
            <!-- AOS Library -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
</head>
<body class="register-page">
    <div class="container">
        <div class="register-card">
            <div class="register-header">
                <h1>Daftar Akun</h1>
                <p>Buat akun baru untuk mulai menggunakan layanan kami</p>
            </div>
            <form id="registerForm">
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" class="input-field" placeholder="Masukkan username Anda" required>
                    <div class="input-icon"><i class="fa-regular fa-user"></i></div>
                </div>
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="text" id="email" class="input-field" placeholder="Masukkan email Anda" required>
                    <div class="input-icon"><i class="fa-regular fa-envelope"></i></div>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" class="input-field" placeholder="Masukkan password Anda" required>
                    <span class="password-toggle" id="togglePassword">👁️</span>
                    <div class="password-strength" id="passwordStrength"></div>
                </div>
                <div class="input-group">
                    <label for="confirmPassword">Konfirmasi Password</label>
                    <input type="password" id="confirmPassword" class="input-field" placeholder="Konfirmasi password Anda" required>
                    <span class="password-toggle" id="toggleConfirmPassword">👁️</span>
                    <div class="password-match" id="passwordMatch"></div>
                </div>
                <div class="show-password-checkbox">
                    <input type="checkbox" id="showPasswords">
                    <label for="showPasswords">Tampilkan semua password</label>
                </div>
                <button type="submit" class="register-btn">Daftar</button>
                <div class="login-link">
                    Sudah punya akun? <a href="/login">Masuk sekarang</a>
                </div>
            </form>
        </div>
    </div>

</body>
<script src="{{ asset('js/script-register.js') }}"></script>
</html>