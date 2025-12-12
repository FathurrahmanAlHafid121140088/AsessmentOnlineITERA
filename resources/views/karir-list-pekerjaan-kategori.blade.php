<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Daftar Pekerjaan RMIB per Kategori</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.ico') }}" />
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600,700" rel="stylesheet" type="text/css" />
    <!-- AOS Library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <link href="{{ asset('css/karir-admin.css') }}" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            font-size: 15px;
        }

        h1 {
            font-size: 32px;
        }

        h3 {
            font-size: 22px;
        }

        h4 {
            font-size: 19px;
        }

        p {
            font-size: 15px;
        }
    </style>
</head>

<body>

    <div class="main-content">
        <div class="header">
            <h1>Daftar Pekerjaan RMIB per Kategori</h1>
        </div>

        <div class="content-area">
            <div class="tabs">
                <a href="{{ route('admin.karir.detail', $hasil->id) }}" class="tab"
                    style="text-decoration: none; color: inherit;">
                    Detail Hasil
                </a>
                <a href="{{ route('admin.karir.list-pekerjaan', $hasil->id) }}" class="tab"
                    style="text-decoration: none; color: inherit;">
                    List per Kelompok
                </a>
                <div class="tab active">List per Kategori</div>
            </div>

            <div class="peserta-info">
                <h3><i class="fas fa-layer-group" style="margin-right: 10px; color: #4361ee;"></i> Daftar Pekerjaan
                    Berdasarkan Kategori Minat</h3>
                <p style="color: #666; margin-bottom: 20px; font-size: 15px;">
                    Berikut adalah daftar lengkap pekerjaan yang tersedia dalam tes RMIB untuk
                    <strong>{{ $gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</strong>,
                    dikelompokkan berdasarkan 12 kategori minat (Outdoor, Mechanical, Computational, dll).
                </p>

                @if (isset($daftarPekerjaanKategori) && count($daftarPekerjaanKategori) > 0)
                    @foreach ($daftarPekerjaanKategori as $kategori => $pekerjaans)
                        @php
                            // Ambil informasi kategori dari deskripsi
                            $infoKategori = $deskripsiKategori[$kategori] ?? [
                                'singkatan' => $kategori,
                                'nama' => $kategori,
                                'deskripsi' => '',
                            ];
                        @endphp
                        <div style="margin-bottom: 30px; page-break-inside: avoid;">
                            <div
                                style="background: linear-gradient(135deg, #4361ee 0%, #3730a3 100%); color: white; padding: 15px 20px; border-radius: 8px; margin-bottom: 15px; box-shadow: 0 2px 8px rgba(67, 97, 238, 0.2);">
                                <h4 style="margin: 0 0 5px 0; font-size: 19px; font-weight: 600;">
                                    <i class="fas fa-tag"
                                        style="margin-right: 10px;"></i>{{ $infoKategori['singkatan'] }} -
                                    {{ $infoKategori['nama'] }}
                                </h4>
                                <p style="margin: 0; font-size: 14px; opacity: 0.9;">{{ $infoKategori['deskripsi'] }}
                                </p>
                            </div>
                            <div
                                style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 12px;">
                                @foreach ($pekerjaans as $index => $pekerjaan)
                                    <div class="pekerjaan-card"
                                        style="background: #f8f9fa; border-left: 4px solid #4361ee; padding: 14px 16px; border-radius: 4px; transition: all 0.3s;">
                                        <div style="display: flex; align-items: center;">
                                            <span
                                                style="background: #4361ee; color: white; width: 26px; height: 26px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 600; margin-right: 12px;">
                                                {{ $index + 1 }}
                                            </span>
                                            <div style="font-weight: 500; color: #333; font-size: 15px;">
                                                {{ $pekerjaan->nama_pekerjaan }}
                                            </div>
                                        </div>
                                        <div style="font-size: 13px; color: #999; margin-top: 6px; margin-left: 38px;">
                                            Kelompok: {{ $pekerjaan->kelompok }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div style="text-align: right; margin-top: 12px; color: #666; font-size: 14px;">
                                <i class="fas fa-briefcase" style="margin-right: 5px;"></i>
                                Total: <strong>{{ count($pekerjaans) }}</strong> pekerjaan
                            </div>
                        </div>
                    @endforeach

                    <div
                        style="background: #e7f3ff; border: 1px solid #4361ee; border-radius: 8px; padding: 16px; margin-top: 30px;">
                        <h4 style="margin: 0 0 10px 0; color: #4361ee; font-size: 18px; font-weight: 600;">
                            <i class="fas fa-info-circle" style="margin-right: 8px;"></i>Informasi
                        </h4>
                        <p style="margin: 0; color: #666; font-size: 15px;">
                            Total <strong>{{ $daftarPekerjaanKategori->sum(fn($p) => count($p)) }}</strong> pekerjaan
                            tersebar di <strong>{{ count($daftarPekerjaanKategori) }}</strong> kategori minat RMIB.
                        </p>
                    </div>
                @else
                    <div class="alert alert-info" style="font-size: 15px;">
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
            // ...existing code...
        });
    </script>
</body>

</html>
