<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Interpretasi RMIB</title>
    <style>
        :root {
            --primary-color: #2D3047;
            --secondary-color: #419D78;
            --accent-color: #E0A458;
            --light-color: #EDF5FC;
            --danger-color: #E84855;
            --success-color: #3CAEA3;
            --gradient-bg: linear-gradient(135deg, var(--primary-color), #384273);
        }
        
        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .header-section {
            background: var(--gradient-bg);
            color: white;
            padding: 2rem 0;
            border-radius: 0 0 20px 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }
        
        .profile-card {
            background-color: white;
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .profile-header {
            background-color: var(--primary-color);
            color: white;
            padding: 1rem;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            margin-bottom: 2rem;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            background-color: var(--primary-color);
            color: white;
            font-weight: bold;
            padding: 1rem 1.5rem;
        }
        
        .table-header {
            background-color: var(--primary-color) !important;
            color: white;
        }
        
        .bar-chart {
            position: relative;
            height: 300px;
            margin-bottom: 2rem;
        }
        
        .bar {
            width: 80px;
            position: relative;
            border-radius: 6px 6px 0 0;
            transition: all 0.5s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .bar:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }
        
        .bar-label {
            text-align: center;
            font-weight: bold;
            padding: 10px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 0 0 6px 6px;
        }
        
        .match-badge {
            font-size: 1rem;
            padding: 0.25rem 0.6rem;
        }
        
        .interpretation-item {
            background-color: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border-left: 5px solid var(--secondary-color);
        }
        
        .interpretation-item:hover {
            transform: translateX(5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }
        
        .interpretation-label {
            display: inline-block;
            padding: 0.3rem 1rem;
            background-color: var(--secondary-color);
            color: white;
            border-radius: 50px;
            margin-right: 1rem;
        }
        
        .footer {
            background: var(--gradient-bg);
            color: white;
            padding: 2rem 0;
            margin-top: 4rem;
            border-radius: 20px 20px 0 0;
        }
        
        .footer a {
            color: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
        }
        
        .footer a:hover {
            color: white;
            text-decoration: none;
        }
        
        .social-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.1);
            margin-right: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .social-icon:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-3px);
        }
        
        .btn-action {
            padding: 0.7rem 1.5rem;
            font-weight: 600;
            border-radius: 30px;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .btn-primary:hover {
            background-color: #358d69;
            border-color: #358d69;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(65, 157, 120, 0.4);
        }
        
        .btn-secondary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-secondary:hover {
            background-color: #232639;
            border-color: #232639;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(45, 48, 71, 0.4);
        }
        
        .badge-success {
            background-color: var(--success-color);
        }
        
        .badge-danger {
            background-color: var(--danger-color);
        }
        
        .rank-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            margin-right: 1rem;
        }
        
        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease forwards;
        }
        
        .animated-item:nth-child(1) { animation-delay: 0.1s; }
        .animated-item:nth-child(2) { animation-delay: 0.2s; }
        .animated-item:nth-child(3) { animation-delay: 0.3s; }
        .animated-item:nth-child(4) { animation-delay: 0.4s; }
        .animated-item:nth-child(5) { animation-delay: 0.5s; }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="fw-bold mb-0">Interpretasi Tes RMIB</h1>
                    <p class="lead mb-0">Rothwell Miller Interest Blank</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <img src="/api/placeholder/120/40" alt="PPSDM ITERA Logo" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <div class="container py-4">
        <!-- Profile Section -->
        <div class="row mb-4 animated-item fade-in">
            <div class="col-md-8">
                <div class="card profile-card">
                    <div class="profile-header">
                        <h5 class="mb-0"><i class="fas fa-user-circle me-2"></i> Profil Peserta</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 fw-bold">Nama</div>
                            <div class="col-md-9">: John Doe</div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3 fw-bold">NIM</div>
                            <div class="col-md-9">: 12345678</div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3 fw-bold">Prodi</div>
                            <div class="col-md-9">: Teknik Informatika</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card profile-card">
                    <div class="profile-header">
                        <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i> Tanggal Tes</h5>
                    </div>
                    <div class="card-body text-center">
                        <h5 class="mb-0">19-05-2025</h5>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Score Results Section -->
        <div class="card animated-item fade-in">
            <div class="card-header">
                <h4 class="mb-0"><i class="fas fa-chart-bar me-2"></i> Hasil Total Skor Kategori</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-header">
                            <tr>
                                <th width="15%">RANK</th>
                                <th width="60%">KATEGORI</th>
                                <th width="25%">SKOR</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="animated-item fade-in">
                                <td><span class="rank-number">1</span></td>
                                <td>AETH</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar" role="progressbar" style="width: 75%; background-color: var(--secondary-color);" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">75</div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="animated-item fade-in">
                                <td><span class="rank-number">2</span></td>
                                <td>MED</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar" role="progressbar" style="width: 65%; background-color: var(--secondary-color);" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100">65</div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="animated-item fade-in">
                                <td><span class="rank-number">3</span></td>
                                <td>MUS</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar" role="progressbar" style="width: 60%; background-color: var(--secondary-color);" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">60</div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="animated-item fade-in">
                                <td><span class="rank-number">4</span></td>
                                <td>LIT</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar" role="progressbar" style="width: 52%; background-color: var(--accent-color);" aria-valuenow="52" aria-valuemin="0" aria-valuemax="100">52</div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="animated-item fade-in">
                                <td><span class="rank-number">5</span></td>
                                <td>SOC</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar" role="progressbar" style="width: 48%; background-color: var(--accent-color);" aria-valuenow="48" aria-valuemin="0" aria-valuemax="100">48</div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="animated-item fade-in">
                                <td><span class="rank-number">6</span></td>
                                <td>PERS</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar" role="progressbar" style="width: 45%; background-color: var(--accent-color);" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100">45</div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="animated-item fade-in">
                                <td><span class="rank-number">7</span></td>
                                <td>COMP</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar" role="progressbar" style="width: 40%; background-color: var(--accent-color);" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">40</div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="animated-item fade-in">
                                <td><span class="rank-number">8</span></td>
                                <td>SCI</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar" role="progressbar" style="width: 35%; background-color: var(--accent-color);" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100">35</div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="animated-item fade-in">
                                <td><span class="rank-number">9</span></td>
                                <td>BUS</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar" role="progressbar" style="width: 30%; background-color: var(--accent-color);" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100">30</div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="animated-item fade-in">
                                <td><span class="rank-number">10</span></td>
                                <td>PRAC</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar" role="progressbar" style="width: 25%; background-color: var(--accent-color);" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25</div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="animated-item fade-in">
                                <td><span class="rank-number">11</span></td>
                                <td>OUT</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar" role="progressbar" style="width: 17%; background-color: var(--danger-color);" aria-valuenow="17" aria-valuemin="0" aria-valuemax="100">17</div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="animated-item fade-in">
                                <td><span class="rank-number">12</span></td>
                                <td>TECH</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar" role="progressbar" style="width: 13%; background-color: var(--danger-color);" aria-valuenow="13" aria-valuemin="0" aria-valuemax="100">13</div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    
        <!-- Top Category Interpretation Section -->
        <div class="card mt-4 animated-item fade-in">
            <div class="card-header">
                <h4 class="mb-0"><i class="fas fa-star me-2"></i> Interpretasi 3 Skor Kategori Tertinggi</h4>
            </div>
            <div class="card-body">
                <!-- Bar Chart Visualization -->
                <div class="row justify-content-center mb-5">
                    <div class="col-md-10">
                        <div class="bar-chart d-flex justify-content-center align-items-end">
                            <div class="mx-4 text-center animated-item fade-in">
                                <div class="d-flex flex-column align-items-center">
                                    <div class="score-value mb-2 fw-bold">75</div>
                                    <div class="bar" style="height: 150px; background-color: #419D78;">
                                        <div class="position-absolute top-0 start-50 translate-middle badge bg-white text-dark rounded-pill px-2 py-1">
                                            75%
                                        </div>
                                    </div>
                                    <div class="bar-label" style="width: 80px;">AETH</div>
                                </div>
                            </div>
                            <div class="mx-4 text-center animated-item fade-in">
                                <div class="d-flex flex-column align-items-center">
                                    <div class="score-value mb-2 fw-bold">65</div>
                                    <div class="bar" style="height: 130px; background-color: #3CAEA3;">
                                        <div class="position-absolute top-0 start-50 translate-middle badge bg-white text-dark rounded-pill px-2 py-1">
                                            65%
                                        </div>
                                    </div>
                                    <div class="bar-label" style="width: 80px;">MED</div>
                                </div>
                            </div>
                            <div class="mx-4 text-center animated-item fade-in">
                                <div class="d-flex flex-column align-items-center">
                                    <div class="score-value mb-2 fw-bold">60</div>
                                    <div class="bar" style="height: 120px; background-color: #67C8FF;">
                                        <div class="position-absolute top-0 start-50 translate-middle badge bg-white text-dark rounded-pill px-2 py-1">
                                            60%
                                        </div>
                                    </div>
                                    <div class="bar-label" style="width: 80px;">MUS</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Interpretations List -->
                <div class="mt-5">
                    <div class="interpretation-item animated-item fade-in">
                        <div class="d-flex align-items-center mb-3">
                            <span class="interpretation-label">AETH</span>
                            <h5 class="mb-0">Minat Artistik-Estetik</h5>
                        </div>
                        <p class="mb-0">
                            Anda memiliki minat yang tinggi pada bidang seni dan estetika. Anda cenderung menikmati aktivitas 
                            yang melibatkan kreativitas, keindahan, dan ekspresi diri. Orang dengan minat AETH tinggi biasanya 
                            memiliki kepekaan terhadap nilai-nilai estetika, desain, dan seni rupa. Anda mungkin cocok dengan 
                            karir yang memungkinkan anda untuk mengekspresikan diri melalui seni visual, desain, atau kreativitas.
                        </p>
                    </div>
                    
                    <div class="interpretation-item animated-item fade-in">
                        <div class="d-flex align-items-center mb-3">
                            <span class="interpretation-label">MED</span>
                            <h5 class="mb-0">Minat Medis</h5>
                        </div>
                        <p class="mb-0">
                            Anda menunjukkan ketertarikan yang signifikan pada bidang kesehatan dan pengobatan. Minat MED yang tinggi 
                            menggambarkan keinginan untuk membantu orang lain melalui layanan kesehatan, pemahaman tentang tubuh manusia, 
                            dan proses penyembuhan. Anda mungkin memiliki empati yang kuat dan keinginan untuk berkontribusi pada 
                            kesejahteraan orang lain. Karir di bidang medis, keperawatan, atau layanan kesehatan bisa menjadi pilihan yang cocok.
                        </p>
                    </div>
                    
                    <div class="interpretation-item animated-item fade-in">
                        <div class="d-flex align-items-center mb-3">
                            <span class="interpretation-label">MUS</span>
                            <h5 class="mb-0">Minat Musikal</h5>
                        </div>
                        <p class="mb-0">
                            Anda memiliki ketertarikan yang kuat terhadap musik dan ekspresi musikal. Minat MUS yang tinggi mencerminkan 
                            apresiasi terhadap harmoni, ritme, dan berbagai bentuk ekspresi musikal. Anda mungkin senang terlibat dalam 
                            kegiatan mendengarkan, menciptakan, atau memainkan musik. Karir di bidang musik, produksi audio, atau 
                            pendidikan musik mungkin sesuai dengan minat anda.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Career Match Section -->
        <div class="card mt-4 animated-item fade-in">
            <div class="card-header">
                <h4 class="mb-0"><i class="fas fa-briefcase me-2"></i> Kecocokan Minat Pekerjaan</h4>
            </div>
            <div class="card-body">
                <p class="mb-4 lead">
                    Berdasarkan hasil peringkat tes 3 teratas dengan 3 jenis pekerjaan yang 
                    Anda pilih sebelumnya, berikut hasil kecocokannya:
                </p>
                
                <div class="row">
                    <div class="col-md-4 mb-3 animated-item fade-in">
                        <div class="card h-100 border-success" style="border-width: 2px !important;">
                            <div class="card-header text-white bg-success">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Desainer Grafis</h5>
                                    <span class="badge bg-white text-success">
                                        <i class="fas fa-check-circle"></i> Cocok
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="card-text">Pekerjaan ini sangat cocok dengan minat AETH Anda. Desainer grafis membutuhkan kreativitas, kepekaan estetika, dan kemampuan untuk mengkomunikasikan pesan secara visual.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3 animated-item fade-in">
                        <div class="card h-100 border-danger" style="border-width: 2px !important;">
                            <div class="card-header text-white bg-danger">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Dokter Spesialis</h5>
                                    <span class="badge bg-white text-danger">
                                        <i class="fas fa-times-circle"></i> Kurang Cocok
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="card-text">Meskipun memiliki keterkaitan dengan minat MED Anda, profesi ini memerlukan keterampilan teknis dan minat ilmiah yang lebih tinggi daripada yang ditunjukkan pada profil Anda.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3 animated-item fade-in">
                        <div class="card h-100 border-success" style="border-width: 2px !important;">
                            <div class="card-header text-white bg-success">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Produser Musik</h5>
                                    <span class="badge bg-white text-success">
                                        <i class="fas fa-check-circle"></i> Cocok
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="card-text">Pekerjaan ini sangat sesuai dengan minat MUS Anda. Produser musik bekerja dengan elemen-elemen musikal dan membutuhkan telinga yang sensitif terhadap harmoni dan komposisi.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Recommendation Section -->
        <div class="card mt-4 animated-item fade-in">
            <div class="card-header">
                <h4 class="mb-0"><i class="fas fa-lightbulb me-2"></i> Rekomendasi Pengembangan Karir</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center">
                                <div class="rounded-circle bg-light p-3 d-inline-flex mb-3" style="width: 80px; height: 80px;">
                                    <i class="fas fa-graduation-cap m-auto fa-2x text-primary"></i>
                                </div>
                                <h5>Pendidikan</h5>
                                <p>Pertimbangkan untuk mengambil kursus atau pelatihan dalam bidang desain, seni, atau musik untuk mengembangkan minat utama Anda.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center">
                                <div class="rounded-circle bg-light p-3 d-inline-flex mb-3" style="width: 80px; height: 80px;">
                                    <i class="fas fa-hands-helping m-auto fa-2x text-primary"></i>
                                </div>
                                <h5>Pengalaman</h5>
                                <p>Cari magang atau kerja paruh waktu di industri kreatif atau layanan kesehatan untuk mendapatkan pengalaman praktis.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center">