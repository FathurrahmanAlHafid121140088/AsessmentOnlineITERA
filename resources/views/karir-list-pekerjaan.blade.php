<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Daftar Pekerjaan RMIB</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.ico') }}" />
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
    <!-- AOS Library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <link href="{{ asset('css/karir-admin.css') }}" rel="stylesheet">
</head>

<body>

    <div class="main-content">
        <div class="header">
            <h1>Daftar Pekerjaan RMIB</h1>
        </div>

        <div class="content-area">
            <div class="tabs">
                <a href="{{ route('admin.karir.detail', $hasil->id) }}" class="tab" style="text-decoration: none; color: inherit;">
                    Detail Hasil
                </a>
                <div class="tab active">List Pekerjaan</div>
            </div>

            <div class="peserta-info">
                <h3><i class="fas fa-briefcase" style="margin-right: 10px; color: #4361ee;"></i> Daftar Pekerjaan & Kategori RMIB</h3>
                <p style="color: #666; margin-bottom: 20px;">
                    Berikut adalah daftar lengkap pekerjaan yang tersedia dalam tes RMIB untuk
                    <strong>{{ $gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</strong>,
                    dikelompokkan berdasarkan kelompok (A-I) dan kategori minat.
                </p>

                @if(isset($daftarPekerjaan) && count($daftarPekerjaan) > 0)
                    @foreach($daftarPekerjaan as $kelompok => $pekerjaans)
                        <div style="margin-bottom: 30px;">
                            <h4 style="background: #4361ee; color: white; padding: 10px 15px; border-radius: 5px; margin-bottom: 15px;">
                                <i class="fas fa-folder-open" style="margin-right: 10px;"></i>Kelompok {{ $kelompok }}
                            </h4>
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 15px;">
                                @foreach($pekerjaans as $pekerjaan)
                                    <div style="background: #f8f9fa; border-left: 4px solid #4361ee; padding: 12px 15px; border-radius: 4px;">
                                        <div style="font-weight: 500; color: #333; margin-bottom: 5px;">
                                            {{ $pekerjaan->nama_pekerjaan }}
                                        </div>
                                        <div style="font-size: 13px; color: #666;">
                                            <i class="fas fa-tag" style="margin-right: 5px; color: #4361ee;"></i>
                                            <strong>{{ $pekerjaan->kategori }}</strong>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Data pekerjaan tidak tersedia.
                    </div>
                @endif
            </div>

            <div class="action-buttons" style="margin-top: 30px;">
                <a href="{{ route('admin.karir.detail', $hasil->id) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Detail Hasil
                </a>
                <a href="{{ route('admin.karir.index') }}" class="btn btn-secondary">
                    <i class="fas fa-home"></i> Ke Dashboard
                </a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle sidebar di mobile view (jika ada)
            const hamburger = document.querySelector('.fa-bars');
            if (hamburger) {
                hamburger.addEventListener('click', function() {
                    const sidebar = document.querySelector('.sidebar');
                    if (sidebar) {
                        sidebar.classList.toggle('show');
                    }
                });
            }
        });
    </script>
</body>

</html>
