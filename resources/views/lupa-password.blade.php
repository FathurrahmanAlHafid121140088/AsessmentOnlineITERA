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
            <link href="css/style-lupa-password.css" rel="stylesheet" />
            <!-- AOS Library -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
</head>
<body>
    <div class="container">
        <div class="forgot-card">
            <div class="forgot-header">
                <h1>Lupa Password?</h1>
                <p>Jangan khawatir, kami akan membantu Anda memulihkan akun</p>
            </div>
            <div class="info-box">
                <p>Masukkan email akun Anda. Kami akan mengirimkan link untuk mengatur ulang password Anda.</p>
            </div>
            <form id="forgotPasswordForm">
                <div id="emailStep">
                    <div class="input-group">
                        <label for="email">Email</label>
                        <input type="text" id="email" class="input-field" placeholder="Masukkan email Anda" required>
                        <div class="input-icon"><i class="fa-regular fa-envelope"></i></div>
                    </div>
                    <button type="button" id="sendCodeBtn" class="reset-btn">Kirim Kode Verifikasi</button>
                </div>
                
                <div id="verificationStep" class="verification-section">
                    <div class="input-group">
                        <label for="verificationCode">Kode Verifikasi</label>
                        <input type="text" id="verificationCode" class="input-field" placeholder="Masukkan kode 6 digit" required maxlength="6">
                        <div class="input-icon">🔐</div>
                    </div>
                    <div class="input-group">
                        <label for="newPassword">Password Baru</label>
                        <input type="password" id="newPassword" class="input-field" placeholder="Masukkan password baru" required>
                        <span class="password-toggle" id="toggleNewPassword" style="cursor: pointer; position: absolute; right: 15px; top: 43px;">👁️</span>
                    </div>
                    <div class="input-group">
                        <label for="confirmNewPassword">Konfirmasi Password Baru</label>
                        <input type="password" id="confirmNewPassword" class="input-field" placeholder="Konfirmasi password baru" required>
                        <span class="password-toggle" id="toggleConfirmNewPassword" style="cursor: pointer; position: absolute; right: 15px; top: 43px;">👁️</span>
                    </div>
                    <button type="button" id="resetPasswordBtn" class="reset-btn">Reset Password</button>
                </div>
                
                <div id="successMessage" class="success-message">
                    Password Anda berhasil direset! Anda dapat menggunakan password baru untuk login.
                </div>
                
                <div class="login-link">
                    <a href="/login">Kembali ke halaman login</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>