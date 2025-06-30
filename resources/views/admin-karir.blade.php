<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sistem Tes RMIB</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #4361ee;
            --primary-light: #4895ef;
            --dark: #3a3a3a;
            --dark-light: #4f4f4f;
            --light: #f5f7fa;
            --danger: #ef476f;
            --success: #06d6a0;
            --warning: #ffd166;
            --gray: #adb5bd;
            --white: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: var(--dark);
            color: var(--white);
            padding: 20px 0;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }

        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar-header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--white);
        }

        .sidebar-menu {
            padding: 20px 0;
            flex-grow: 1;
        }

        .menu-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: all 0.2s;
            color: var(--gray);
        }

        .menu-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--white);
        }

        .menu-item.active {
            background-color: var(--primary);
            color: var(--white);
            border-left: 4px solid var(--warning);
        }

        .menu-item i {
            margin-right: 14px;
            font-size: 18px;
            width: 25px;
            text-align: center;
        }

        .main-content {
            flex-grow: 1;
            padding: 25px;
            display: flex;
            flex-direction: column;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .header h1 {
            font-size: 1.8rem;
            color: var(--dark);
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .content-area {
            background-color: var(--white);
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 25px;
            flex-grow: 1;
        }

        .tabs {
            display: flex;
            border-bottom: 1px solid #e9ecef;
            margin-bottom: 20px;
        }

        .tab {
            padding: 12px 25px;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
            font-weight: 500;
            color: var(--gray);
        }

        .tab.active {
            border-bottom: 3px solid var(--primary);
            color: var(--primary);
        }

        .tab:hover {
            color: var(--primary-light);
        }

        .result-section {
            margin-bottom: 30px;
        }

        .result-section h2 {
            font-size: 1.3rem;
            margin-bottom: 15px;
            color: var(--dark);
            display: flex;
            align-items: center;
        }

        .result-section h2 i {
            margin-right: 10px;
            color: var(--primary);
        }

        .result-info {
            background-color: #e7f5ff;
            border-left: 4px solid var(--primary);
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }

        .rmib-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .rmib-table th, .rmib-table td {
            padding: 12px 15px;
            text-align: center;
            border: 1px solid #e9ecef;
        }

        .rmib-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: var(--dark);
        }

        .rmib-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .rmib-table tr:hover {
            background-color: #f1f3f5;
        }

        .highlight {
            background-color: #ffe8cc !important;
        }

        .text-primary {
            color: var(--primary);
        }

        .kategori-column {
            text-align: left;
            font-weight: 500;
        }

        .top-rank {
            background-color: #e7f5ff;
            font-weight: 600;
        }

        .btn {
            padding: 8px 16px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            transition: all 0.2s;
        }

        .btn i {
            margin-right: 8px;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-light);
        }

        .btn-secondary {
            background-color: #e9ecef;
            color: var(--dark);
        }

        .btn-secondary:hover {
            background-color: #dee2e6;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .percentage-bar {
            height: 10px;
            background-color: #e9ecef;
            border-radius: 5px;
            margin-top: 5px;
            position: relative;
            overflow: hidden;
        }

        .percentage-fill {
            height: 100%;
            background-color: var(--primary);
            border-radius: 5px;
        }

        .chart-container {
            height: 300px;
            margin-top: 20px;
            margin-bottom: 30px;
        }

        .peserta-info {
            margin-bottom: 25px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 6px;
            border-left: 4px solid var(--primary);
        }

        .peserta-info h3 {
            color: var(--dark);
            margin-bottom: 10px;
            font-size: 1.1rem;
        }

        .peserta-info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 0.85rem;
            color: var(--gray);
            margin-bottom: 4px;
        }

        .info-value {
            font-weight: 500;
            color: var(--dark);
        }

        @media screen and (max-width: 1024px) {
            .sidebar {
                width: 80px;
            }

            .sidebar-header h2, .menu-item span {
                display: none;
            }

            .menu-item i {
                margin-right: 0;
            }

            .peserta-info-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media screen and (max-width: 768px) {
            body {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
                padding: 15px;
                flex-direction: row;
                justify-content: space-between;
            }

            .sidebar-header {
                border: none;
                padding: 0;
            }

            .sidebar-menu {
                display: flex;
                padding: 0;
                margin-left: 20px;
            }

            .menu-item {
                padding: 8px 12px;
            }

            .menu-item i {
                margin-right: 0;
            }

            .menu-item span {
                display: none;
            }

            .main-content {
                padding: 15px;
            }

            .peserta-info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
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