<!DOCTYPE html>
<html lang="id">
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
        <!-- AOS Library -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
        <link href="{{ asset('css/karir-admin.css') }}" rel="stylesheet">
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>RMIB Admin</h2>
            <i class="fas fa-bars"></i>
        </div>
        <div class="sidebar-menu">
            <div class="menu-item">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </div>
            <div class="menu-item active">
                <i class="fas fa-chart-bar"></i>
                <span>Hasil Tes</span>
            </div>
            <div class="menu-item">
                <i class="fas fa-users"></i>
                <span>Peserta</span>
            </div>
            <div class="menu-item">
                <i class="fas fa-clipboard-list"></i>
                <span>Master Soal</span>
            </div>
            <div class="menu-item">
                <i class="fas fa-cog"></i>
                <span>Pengaturan</span>
            </div>
            <div class="menu-item">
                <i class="fas fa-file-export"></i>
                <span>Laporan</span>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Hasil Tes RMIB</h1>
            <div class="user-info">
                <img src="/api/placeholder/40/40" alt="Admin Avatar">
                <span>Admin</span>
            </div>
        </div>

        <div class="content-area">
            <div class="tabs">
                <div class="tab active">Detail Hasil</div>
                <div class="tab">Interpretasi</div>
                <div class="tab">Riwayat</div>
            </div>

            <div class="peserta-info">
                <h3>Informasi Peserta</h3>
                <div class="peserta-info-grid">
                    <div class="info-item">
                        <span class="info-label">Nama Peserta</span>
                        <span class="info-value">Ahmad Fauzi</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email</span>
                        <span class="info-value">ahmad.fauzi@email.com</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Tanggal Tes</span>
                        <span class="info-value">15 Mei 2025</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Usia</span>
                        <span class="info-value">25 Tahun</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Pendidikan</span>
                        <span class="info-value">S1 Psikologi</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Durasi Tes</span>
                        <span class="info-value">28 Menit</span>
                    </div>
                </div>
            </div>

            <div class="result-section">
                <h2><i class="fas fa-calculator"></i> Perhitungan Hasil Tes RMIB</h2>
                
                <div class="result-info">
                    Berikut adalah hasil tes minat karir berdasarkan metode Rothwell-Miller Interest Blank (RMIB) untuk <strong>Ahmad Fauzi</strong>. Kategori dengan nilai persentase tertinggi menunjukkan bidang minat yang paling dominan.
                </div>

                <div class="chart-container" id="chartContainer"></div>

                <table class="rmib-table">
                    <thead>
                        <tr>
                            <th>KATEGORI</th>
                            <th>A</th>
                            <th>B</th>
                            <th>C</th>
                            <th>D</th>
                            <th>E</th>
                            <th>F</th>
                            <th>G</th>
                            <th>H</th>
                            <th>I</th>
                            <th>SUM</th>
                            <th>RANK</th>
                            <th>%</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="kategori-column">1. OUT (Outdoor)</td>
                            <td>6</td>
                            <td>9</td>
                            <td>3</td>
                            <td>9</td>
                            <td>3</td>
                            <td>5</td>
                            <td>9</td>
                            <td>2</td>
                            <td>12</td>
                            <td>58</td>
                            <td>5</td>
                            <td>46.3</td>
                        </tr>
                        <tr>
                            <td class="kategori-column">2. ME (Mechanical)</td>
                            <td>7</td>
                            <td>12</td>
                            <td>6</td>
                            <td>7</td>
                            <td>5</td>
                            <td>9</td>
                            <td>2</td>
                            <td>3</td>
                            <td>8</td>
                            <td>59</td>
                            <td>6</td>
                            <td>45.4</td>
                        </tr>
                        <tr>
                            <td class="kategori-column">3. COMP (Computational)</td>
                            <td>11</td>
                            <td>5</td>
                            <td>9</td>
                            <td>6</td>
                            <td>2</td>
                            <td>8</td>
                            <td>1</td>
                            <td>4</td>
                            <td>9</td>
                            <td>55</td>
                            <td>4</td>
                            <td>49.1</td>
                        </tr>
                        <tr>
                            <td class="kategori-column">4. SCI (Scientific)</td>
                            <td>10</td>
                            <td>2</td>
                            <td>4</td>
                            <td>10</td>
                            <td>7</td>
                            <td>12</td>
                            <td>8</td>
                            <td>10</td>
                            <td>3</td>
                            <td>66</td>
                            <td>10</td>
                            <td>38.9</td>
                        </tr>
                        <tr class="top-rank">
                            <td class="kategori-column">5. PERS (Persuasive)</td>
                            <td>4</td>
                            <td>4</td>
                            <td>2</td>
                            <td>11</td>
                            <td>4</td>
                            <td>11</td>
                            <td>6</td>
                            <td>5</td>
                            <td>1</td>
                            <td>48</td>
                            <td>2</td>
                            <td>55.6</td>
                        </tr>
                        <tr>
                            <td class="kategori-column">6. AESTH (Aesthetic)</td>
                            <td>3</td>
                            <td>8</td>
                            <td>8</td>
                            <td>8</td>
                            <td>12</td>
                            <td>2</td>
                            <td>12</td>
                            <td>11</td>
                            <td>2</td>
                            <td>66</td>
                            <td>11</td>
                            <td>38.9</td>
                        </tr>
                        <tr>
                            <td class="kategori-column">7. LIT (Literary)</td>
                            <td>9</td>
                            <td>10</td>
                            <td>10</td>
                            <td>4</td>
                            <td>11</td>
                            <td>4</td>
                            <td>7</td>
                            <td>1</td>
                            <td>6</td>
                            <td>62</td>
                            <td>9</td>
                            <td>42.6</td>
                        </tr>
                        <tr>
                            <td class="kategori-column">8. MUS (Musical)</td>
                            <td>8</td>
                            <td>7</td>
                            <td>11</td>
                            <td>3</td>
                            <td>9</td>
                            <td>10</td>
                            <td>10</td>
                            <td>6</td>
                            <td>7</td>
                            <td>71</td>
                            <td>12</td>
                            <td>34.3</td>
                        </tr>
                        <tr class="highlight top-rank">
                            <td class="kategori-column">9. S.S (Social Service)</td>
                            <td>1</td>
                            <td>1</td>
                            <td>5</td>
                            <td>12</td>
                            <td>6</td>
                            <td>7</td>
                            <td>3</td>
                            <td>7</td>
                            <td>4</td>
                            <td>46</td>
                            <td>1</td>
                            <td>57.4</td>
                        </tr>
                        <tr>
                            <td class="kategori-column">10. CLER (Clerical)</td>
                            <td>5</td>
                            <td>6</td>
                            <td>12</td>
                            <td>5</td>
                            <td>8</td>
                            <td>1</td>
                            <td>11</td>
                            <td>8</td>
                            <td>5</td>
                            <td>61</td>
                            <td>8</td>
                            <td>43.5</td>
                        </tr>
                        <tr class="top-rank">
                            <td class="kategori-column">11. PRAC (Practical)</td>
                            <td>2</td>
                            <td>3</td>
                            <td>7</td>
                            <td>2</td>
                            <td>10</td>
                            <td>3</td>
                            <td>5</td>
                            <td>9</td>
                            <td>10</td>
                            <td>51</td>
                            <td>3</td>
                            <td>52.8</td>
                        </tr>
                        <tr>
                            <td class="kategori-column">12. MED (Medical)</td>
                            <td>12</td>
                            <td>11</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>6</td>
                            <td>4</td>
                            <td>12</td>
                            <td>11</td>
                            <td>59</td>
                            <td>7</td>
                            <td>45.4</td>
                        </tr>
                    </tbody>
                </table>

                <div class="result-section">
                    <h2><i class="fas fa-trophy"></i> Minat Tertinggi</h2>
                    <div class="top-results">
                        <div>
                            <p><strong>1. Social Service (S.S)</strong> - 57.4%</p>
                            <div class="percentage-bar">
                                <div class="percentage-fill" style="width: 57.4%"></div>
                            </div>
                        </div>
                        <br>
                        <div>
                            <p><strong>2. Persuasive (PERS)</strong> - 55.6%</p>
                            <div class="percentage-bar">
                                <div class="percentage-fill" style="width: 55.6%"></div>
                            </div>
                        </div>
                        <br>
                        <div>
                            <p><strong>3. Practical (PRAC)</strong> - 52.8%</p>
                            <div class="percentage-bar">
                                <div class="percentage-fill" style="width: 52.8%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="action-buttons">
                    <button class="btn btn-primary"><i class="fas fa-print"></i> Cetak Hasil</button>
                    <button class="btn btn-primary"><i class="fas fa-file-pdf"></i> Export PDF</button>
                    <button class="btn btn-secondary"><i class="fas fa-envelope"></i> Kirim Hasil</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Data RMIB
            const labels = [
                'Outdoor (OUT)', 'Mechanical (ME)', 'Computational (COMP)', 
                'Scientific (SCI)', 'Persuasive (PERS)', 'Aesthetic (AESTH)',
                'Literary (LIT)', 'Musical (MUS)', 'Social Service (S.S)',
                'Clerical (CLER)', 'Practical (PRAC)', 'Medical (MED)'
            ];
            
            const percentages = [46.3, 45.4, 49.1, 38.9, 55.6, 38.9, 42.6, 34.3, 57.4, 43.5, 52.8, 45.4];
            
            // Membuat chart
            const ctx = document.createElement('canvas');
            document.getElementById('chartContainer').appendChild(ctx);
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Persentase Minat (%)',
                        data: percentages,
                        backgroundColor: [
                            'rgba(67, 97, 238, 0.7)',
                            'rgba(67, 97, 238, 0.7)',
                            'rgba(67, 97, 238, 0.7)',
                            'rgba(67, 97, 238, 0.7)',
                            'rgba(67, 97, 238, 0.9)',
                            'rgba(67, 97, 238, 0.7)',
                            'rgba(67, 97, 238, 0.7)',
                            'rgba(67, 97, 238, 0.7)',
                            'rgba(67, 97, 238, 1)',
                            'rgba(67, 97, 238, 0.7)',
                            'rgba(67, 97, 238, 0.9)',
                            'rgba(67, 97, 238, 0.7)'
                        ],
                        borderColor: [
                            'rgba(67, 97, 238, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            title: {
                                display: true,
                                text: 'Persentase (%)'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Kategori Minat'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.raw + '%';
                                }
                            }
                        }
                    }
                }
            });

            // Toggle sidebar di mobile view
            document.querySelector('.fa-bars').addEventListener('click', function() {
                const sidebar = document.querySelector('.sidebar');
                sidebar.classList.toggle('show');
            });

            // Tab switching
            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    tabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        });
    </script>
</body>
</html>