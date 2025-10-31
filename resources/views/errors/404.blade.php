<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            padding: 20px;
        }

        .error-container {
            text-align: center;
            max-width: 600px;
        }

        .error-code {
            font-size: 120px;
            font-weight: bold;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .error-title {
            font-size: 32px;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .error-message {
            font-size: 18px;
            margin-bottom: 30px;
            opacity: 0.9;
            line-height: 1.6;
        }

        .error-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 30px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: transform 0.2s, box-shadow 0.2s;
            display: inline-block;
        }

        .btn-primary {
            background-color: #fff;
            color: #667eea;
        }

        .btn-secondary {
            background-color: rgba(255, 255, 255, 0.2);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .illustration {
            font-size: 80px;
            margin-bottom: 20px;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        @media (max-width: 480px) {
            .error-code {
                font-size: 80px;
            }

            .error-title {
                font-size: 24px;
            }

            .error-message {
                font-size: 16px;
            }

            .illustration {
                font-size: 60px;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="illustration">🔍</div>
        <div class="error-code">404</div>
        <h1 class="error-title">Halaman Tidak Ditemukan</h1>
        <p class="error-message">
            Maaf, halaman yang Anda cari tidak dapat ditemukan.
            Halaman mungkin telah dipindahkan atau tidak ada.
        </p>
        <div class="error-actions">
            <a href="{{ url('/') }}" class="btn btn-primary">Kembali ke Beranda</a>
            <a href="javascript:history.back()" class="btn btn-secondary">Halaman Sebelumnya</a>
        </div>
    </div>
</body>
</html>
