<!DOCTYPE html>
<html lang="en">
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
        <link href="{{ asset('css/style-admin-home.css') }}" rel="stylesheet">
    </head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <h2>Admin Panel</h2>
            </div>
            <ul class="menu">
                <li class="active">
                    <a href="#"><i class="fas fa-home"></i> Home</a>
                </li>
                <li>
                    <a href="#"><i class="fas fa-brain"></i> Mental Health</a>
                </li>
                <li>
                    <a href="#"><i class="fas fa-briefcase"></i> Peminatan Karir</a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <header>
                <div class="header-title">
                    <h2>Dashboard</h2>
                </div>
                <div class="user-wrapper">
                    <div class="search-box">
                        <input type="text" placeholder="Search...">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="notification">
                        <i class="fas fa-bell"></i>
                        <span class="badge">5</span>
                    </div>
                    <div class="login-button">
                        <a href="#"><i class="fas fa-sign-in-alt"></i> Login</a>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <div class="cards">
                    <div class="card">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-info">
                            <h3>Total Users</h3>
                            <h2>1,250</h2>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-icon bg-success">
                            <i class="fas fa-brain"></i>
                        </div>
                        <div class="card-info">
                            <h3>Mental Health Tests</h3>
                            <h2>753</h2>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <div class="card-info">
                            <h3>Career Tests</h3>
                            <h2>498</h2>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-icon bg-danger">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="card-info">
                            <h3>Appointments</h3>
                            <h2>87</h2>
                        </div>
                    </div>
                </div>

                <div class="charts">
                    <div class="chart">
                        <div class="chart-header">
                            <h3>User Statistics</h3>
                            <div class="dropdown">
                                <button>Last 7 Days <i class="fas fa-chevron-down"></i></button>
                            </div>
                        </div>
                        <div class="chart-canvas">
                            <!-- Chart will be displayed here -->
                            <div class="placeholder-chart">
                                <div class="bar" style="height: 30%;"></div>
                                <div class="bar" style="height: 50%;"></div>
                                <div class="bar" style="height: 80%;"></div>
                                <div class="bar" style="height: 60%;"></div>
                                <div class="bar" style="height: 40%;"></div>
                                <div class="bar" style="height: 70%;"></div>
                                <div class="bar" style="height: 90%;"></div>
                            </div>
                            <div class="chart-labels">
                                <span>Sen</span>
                                <span>Sel</span>
                                <span>Rab</span>
                                <span>Kam</span>
                                <span>Jum</span>
                                <span>Sab</span>
                                <span>Min</span>
                            </div>
                        </div>
                    </div>
                    <div class="chart">
                        <div class="chart-header">
                            <h3>Test Distribution</h3>
                            <div class="dropdown">
                                <button>This Month <i class="fas fa-chevron-down"></i></button>
                            </div>
                        </div>
                        <div class="chart-canvas">
                            <div class="placeholder-pie">
                                <div class="pie-segment mental-health"></div>
                                <div class="pie-segment career"></div>
                            </div>
                            <div class="pie-legend">
                                <div><span class="mental-health-color"></span> Mental Health (60%)</div>
                                <div><span class="career-color"></span> Peminatan Karir (40%)</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tables">
                    <div class="table">
                        <div class="table-header">
                            <h3>Recent Activities</h3>
                            <button>View All</button>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Activity</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Budi Santoso</td>
                                    <td>Mental Health Assessment</td>
                                    <td><span class="status completed">Completed</span></td>
                                    <td>29 Apr 2025</td>
                                </tr>
                                <tr>
                                    <td>Siti Amalia</td>
                                    <td>Career Interest Test</td>
                                    <td><span class="status completed">Completed</span></td>
                                    <td>28 Apr 2025</td>
                                </tr>
                                <tr>
                                    <td>Ahmad Hidayat</td>
                                    <td>Counseling Session</td>
                                    <td><span class="status pending">Pending</span></td>
                                    <td>28 Apr 2025</td>
                                </tr>
                                <tr>
                                    <td>Dewi Lestari</td>
                                    <td>Mental Health Assessment</td>
                                    <td><span class="status in-progress">In Progress</span></td>
                                    <td>27 Apr 2025</td>
                                </tr>
                                <tr>
                                    <td>Rudi Hartono</td>
                                    <td>Career Interest Test</td>
                                    <td><span class="status cancelled">Cancelled</span></td>
                                    <td>27 Apr 2025</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Script sederhana untuk menangani toggle sidebar pada tampilan mobile
        document.addEventListener('DOMContentLoaded', function() {
            const menuItems = document.querySelectorAll('.sidebar .menu li');
            
            menuItems.forEach(item => {
                item.addEventListener('click', function() {
                    menuItems.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        });
    </script>
</body>
</html>