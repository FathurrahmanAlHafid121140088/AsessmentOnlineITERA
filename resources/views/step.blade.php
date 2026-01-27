<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>{{ $title ?? 'Kuesioner Mental Health' }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.ico') }}" />

    {{-- Font Awesome --}}
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />

    {{-- Bootstrap & CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app-mh-quiz.css'])

    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary-color: #19198c;
            --primary-dark: #0f0f5c;
            --primary-light: #2929bc;
            --accent-color: #4a4aff;
            --success-color: #06ffa5;
            --text-dark: #1a1a2e;
            --text-muted: #6c757d;
            --bg-light: #f8f9fa;
            --shadow-sm: 0 2px 8px rgba(25, 25, 140, 0.08);
            --shadow-md: 0 8px 24px rgba(25, 25, 140, 0.12);
            --shadow-lg: 0 16px 48px rgba(25, 25, 140, 0.15);
        }

        /* Container Utama */
        .quiz-container {
            background: #ffffff;
            border: none;
            box-shadow: var(--shadow-lg);
            border-radius: 28px;
            overflow: hidden;
            position: relative;
        }

        .quiz-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #19198c, #4a4aff, #2929bc);
        }

        /* Quiz Header - User Info Card */
        .quiz-header {
            background: linear-gradient(135deg, rgba(25, 25, 140, 0.08) 0%, rgba(74, 74, 255, 0.08) 100%);
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 32px;
            border: 1px solid rgba(25, 25, 140, 0.15);
        }

        .quiz-profile-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #19198c, #4a4aff);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            box-shadow: 0 4px 12px rgba(25, 25, 140, 0.4);
        }

        .quiz-profile-text h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .quiz-profile-text .prodi {
            font-size: 0.95rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* Assessment Badge */
        .assessment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 20px;
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 24px;
        }

        .assessment-title {
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .assessment-title i {
            font-size: 1rem;
            color: var(--primary-color);
        }

        .question-badge {
            background: linear-gradient(135deg, #19198c, #2929bc);
            color: white;
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.95rem;
            box-shadow: 0 4px 12px rgba(25, 25, 140, 0.4);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Progress Bar */
        .progress-wrapper {
            margin-bottom: 40px;
        }

        .progress {
            height: 12px;
            background: #e9ecef;
            border-radius: 12px;
            overflow: visible;
            position: relative;
        }

        .progress-bar {
            background: linear-gradient(90deg, #2929bc, #19198c);
            border-radius: 12px;
            position: relative;
            transition: width 0.6s cubic-bezier(0.25, 0.8, 0.25, 1);
            box-shadow: 0 2px 8px rgba(25, 25, 140, 0.5);
        }

        .progress-bar::after {
            content: '';
            position: absolute;
            top: 50%;
            right: -6px;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            background: white;
            border: 4px solid #19198c;
            border-radius: 50%;
            box-shadow: 0 2px 8px rgba(25, 25, 140, 0.4);
        }

        .progress-label {
            display: flex;
            justify-content: space-between;
            margin-top: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
        }

        /* Question Card */
        .question-card {
            background: linear-gradient(135deg, #f8f9fa, #ffffff);
            border-radius: 20px;
            padding: 32px;
            margin-bottom: 32px;
            border: 2px solid rgba(67, 97, 238, 0.1);
            position: relative;
            overflow: hidden;
        }

        .question-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(67, 97, 238, 0.03) 0%, transparent 70%);
            pointer-events: none;
        }

        .question-number-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border-radius: 14px;
            font-weight: 800;
            font-size: 1.3rem;
            box-shadow: 0 6px 16px rgba(67, 97, 238, 0.4);
            margin-bottom: 20px;
            position: relative;
        }

        .question-number-badge::after {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 16px;
            padding: 2px;
            background: linear-gradient(135deg, #4a4aff, #2929bc);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            opacity: 0.3;
        }

        .question-text {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-dark);
            line-height: 1.7;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        /* ----------------------------------------------- */
        /* OPTIONS GRID - DESKTOP LAYOUT (VERTIKAL FLOW) */
        /* ----------------------------------------------- */
        .options-grid {
            display: grid;
            /* Buat 2 kolom sama lebar */
            grid-template-columns: 1fr 1fr;
            /* Batasi 3 baris agar urutan jadi 1-2-3 di kiri, 4-5-6 di kanan */
            grid-template-rows: repeat(3, auto);
            /* Alur pengisian: Kolom (Atas ke Bawah dulu) */
            grid-auto-flow: column;

            gap: 16px;
            margin-bottom: 32px;
        }

        /* Option Card */
        .option-card {
            position: relative;
            cursor: pointer;
        }

        .option-card input[type="radio"] {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .option-label {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 20px;
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 16px;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            position: relative;
            overflow: hidden;
            min-height: 80px;
            cursor: pointer;
            height: 100%;
            /* Agar tinggi seragam jika grid row auto */
        }

        .option-label::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, #19198c, #4a4aff);
            transform: scaleY(0);
            transition: transform 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        .option-radio {
            width: 24px;
            height: 24px;
            border: 2px solid #adb5bd;
            border-radius: 50%;
            flex-shrink: 0;
            position: relative;
            transition: all 0.3s ease;
            background: white;
        }

        .option-radio::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            width: 12px;
            height: 12px;
            background: white;
            border-radius: 50%;
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .option-text {
            flex: 1;
            font-size: 1rem;
            font-weight: 500;
            color: var(--text-dark);
            line-height: 1.5;
        }

        /* Hover Effect */
        .option-card:hover .option-label {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(25, 25, 140, 0.15);
            border-color: var(--primary-color);
        }

        .option-card:hover .option-label::before {
            transform: scaleY(1);
        }

        /* Selected State */
        .option-card input[type="radio"]:checked+.option-label {
            background: linear-gradient(135deg, #f0f4ff, #ffffff);
            border-color: var(--primary-color);
            box-shadow: 0 8px 24px rgba(25, 25, 140, 0.25), 0 0 0 4px rgba(25, 25, 140, 0.1);
            transform: translateY(-2px);
        }

        .option-card input[type="radio"]:checked+.option-label::before {
            transform: scaleY(1);
        }

        .option-card input[type="radio"]:checked+.option-label .option-radio {
            background: linear-gradient(135deg, #19198c, #2929bc);
            border-color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(25, 25, 140, 0.4);
        }

        .option-card input[type="radio"]:checked+.option-label .option-radio::after {
            transform: translate(-50%, -50%) scale(1);
        }

        .option-card input[type="radio"]:checked+.option-label .option-text {
            color: var(--primary-color);
            font-weight: 600;
        }

        /* Submit Button */
        .btn-submit {
            width: 100%;
            padding: 18px 32px;
            background: linear-gradient(135deg, #19198c, #2929bc);
            border: none;
            border-radius: 16px;
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            box-shadow: 0 8px 24px rgba(25, 25, 140, 0.4);
            position: relative;
            overflow: hidden;
        }

        .btn-submit::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(25, 25, 140, 0.5);
        }

        .btn-submit:hover::before {
            left: 100%;
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* =========================================
           RESPONSIVE STYLES (TABLET & MOBILE)
           ========================================= */

        @media (max-width: 768px) {

            /* Container Utama */
            .quiz-container {
                border-radius: 20px;
                padding: 1.25rem !important;
            }

            /* --- HEADER USER --- */
            .quiz-header {
                padding: 16px;
                margin-bottom: 24px;
                border-radius: 16px;
                text-align: left;
                /* Rata Kiri */
            }

            /* Flex tetap Row (Baris) di HP agar menyamping */
            .quiz-header .d-flex {
                flex-direction: row !important;
                gap: 12px !important;
                align-items: center !important;
            }

            /* Icon Profile */
            .quiz-profile-icon {
                width: 48px;
                height: 48px;
                font-size: 20px;
                margin: 0;
                border-radius: 14px;
                flex-shrink: 0;
            }

            /* Teks User */
            .quiz-profile-text {
                display: flex;
                flex-direction: column;
                justify-content: center;
                line-height: 1.2;
                overflow: hidden;
            }

            .quiz-profile-text h3 {
                font-size: 1.1rem;
                margin-bottom: 2px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 200px;
            }

            .quiz-profile-text .prodi {
                font-size: 0.8rem;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 200px;
            }

            /* Assessment Header */
            .assessment-header {
                flex-direction: row;
                justify-content: space-between;
                gap: 10px;
                text-align: left;
                padding: 12px 16px;
                border-radius: 14px;
            }

            .assessment-title {
                font-size: 0.7rem;
                letter-spacing: 0.5px;
            }

            .assessment-title span {
                display: none;
            }

            .assessment-title::after {
                content: 'MHI-38';
                display: block;
            }

            .question-badge {
                width: auto;
                padding: 6px 12px;
                font-size: 0.8rem;
            }

            /* Progress Bar */
            .progress-wrapper {
                margin-bottom: 24px;
            }

            .progress {
                height: 8px;
            }

            .progress-bar::after {
                width: 14px;
                height: 14px;
                border-width: 2px;
                right: -5px;
            }

            .progress-label {
                font-size: 0.7rem;
                margin-top: 6px;
            }

            /* Kartu Pertanyaan */
            .question-card {
                padding: 20px;
                border-radius: 16px;
                margin-bottom: 20px;
                text-align: left;
            }

            .question-number-badge {
                width: 36px;
                height: 36px;
                font-size: 1rem;
                border-radius: 10px;
                margin-bottom: 12px;
                margin-left: 0;
                margin-right: 0;
            }

            .question-text {
                font-size: 1rem;
                line-height: 1.5;
            }

            /* --------------------------------------- */
            /* OPTIONS GRID - MOBILE RESET (STACKING)  */
            /* --------------------------------------- */
            .options-grid {
                /* Kembalikan ke 1 Kolom */
                grid-template-columns: 1fr;
                /* Reset baris agar tidak terbatas */
                grid-template-rows: auto;
                /* Reset alur agar mengisi ke bawah */
                grid-auto-flow: row;

                gap: 12px;
                margin-bottom: 24px;
            }

            .option-label {
                padding: 14px;
                min-height: auto;
                border-radius: 12px;
            }

            .option-radio {
                width: 20px;
                height: 20px;
                border-width: 2px;
            }

            .option-radio::after {
                width: 10px;
                height: 10px;
            }

            .option-text {
                font-size: 0.9rem;
            }

            /* Tombol Submit */
            .btn-submit {
                padding: 14px;
                font-size: 0.95rem;
                border-radius: 12px;
            }
        }

        /* Tampilan Layar Sangat Kecil (HP Kecil < 480px) */
        @media (max-width: 480px) {
            .quiz-container {
                padding: 1rem !important;
                /* Padding lebih tipis */
                border-radius: 16px;
            }

            .quiz-header {
                padding: 12px;
                margin-bottom: 20px;
            }

            .quiz-profile-icon {
                width: 40px;
                height: 40px;
                font-size: 16px;
                border-radius: 10px;
            }

            .quiz-profile-text h3 {
                font-size: 0.95rem;
                margin-bottom: 0;
            }

            .quiz-profile-text .prodi {
                font-size: 0.7rem;
                margin-top: 2px;
            }

            .assessment-header {
                padding: 10px;
                margin-bottom: 20px;
            }

            .assessment-title {
                font-size: 0.65rem;
            }

            .question-badge {
                padding: 4px 10px;
                font-size: 0.7rem;
                border-radius: 8px;
            }

            .question-card {
                padding: 16px;
                margin-bottom: 16px;
                border-radius: 12px;
            }

            .question-number-badge {
                width: 32px;
                height: 32px;
                font-size: 0.9rem;
                margin-bottom: 10px;
                border-radius: 8px;
            }

            .question-text {
                font-size: 0.9rem;
                line-height: 1.4;
            }

            .options-grid {
                gap: 10px;
                margin-bottom: 20px;
            }

            .option-label {
                padding: 10px 12px;
                gap: 10px;
                min-height: auto;
                border-radius: 10px;
            }

            .option-radio {
                width: 18px;
                height: 18px;
                border-width: 2px;
            }

            .option-radio::after {
                width: 8px;
                height: 8px;
            }

            .option-text {
                font-size: 0.8rem;
                line-height: 1.3;
            }

            .btn-submit {
                padding: 12px;
                font-size: 0.85rem;
                border-radius: 10px;
            }
        }
    </style>
</head>

<body>
    {{-- Navbar --}}
    @if (View::exists('components.navbar'))
        <x-navbar></x-navbar>
    @else
        <nav class="navbar navbar-expand-lg navbar-dark mb-4"
            style="background: rgba(0,0,0,0.2); backdrop-filter: blur(10px);">
            <div class="container">
                <a class="navbar-brand fw-bold" href="#"><i class="fas fa-brain me-2"></i>Mental Health Test</a>
            </div>
        </nav>
    @endif

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="quiz-container p-4 p-md-5">

                    {{-- Header User Info --}}
                    <div class="quiz-header">
                        <div class="d-flex align-items-center gap-3">
                            {{-- Icon Profile --}}
                            <div class="quiz-profile-icon flex-shrink-0">
                                <i class="fas fa-user"></i>
                            </div>

                            {{-- Info User (Nama Class Baru) --}}
                            <div class="quiz-profile-text flex-grow-1 overflow-hidden">
                                <h3 class="mb-0 text-truncate">
                                    Halo, <span style="color: var(--primary-color);">{{ $nama ?? 'Mahasiswa' }}</span>
                                </h3>
                                <p class="prodi mb-0 text-truncate">
                                    <i class="fas fa-graduation-cap me-1"></i>
                                    {{ $prodi ?? 'Program Studi' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Assessment Header --}}
                    <div class="assessment-header">
                        <div class="assessment-title">
                            <i class="fas fa-clipboard-list"></i>
                            <span>MHI-38 Assessment</span>
                        </div>
                        <div class="question-badge">
                            <i class="fas fa-file-alt"></i>
                            <span>Pertanyaan {{ $step }} / 38</span>
                        </div>
                    </div>

                    {{-- Progress Bar Dinamis --}}
                    <div class="progress-wrapper">
                        @php
                            $percent = ($step / 38) * 100;
                            $remaining = 38 - $step;
                        @endphp
                        <div class="progress">
                            <div class="progress-bar" style="width: {{ $percent }}%"></div>
                        </div>
                        <div class="progress-label">
                            <span><i class="fas fa-hourglass-start me-1"></i>{{ round($percent) }}% Selesai</span>
                            <span>{{ $remaining }} Soal Tersisa <i class="fas fa-tasks ms-1"></i></span>
                        </div>
                    </div>

                    {{-- Question Card --}}
                    <div class="question-card">
                        <div class="question-number-badge">{{ $step }}</div>
                        <p class="question-text">
                            {{ $question }}
                        </p>
                    </div>

                    {{-- Form Utama --}}
                    <form action="{{ route('quiz.store', $step) }}" method="POST">
                        @csrf

                        {{-- Options Grid --}}
                        <div class="options-grid">
                            @php
                                $stepInt = (int) $step;
                                // LOGIKA OPSI JAWABAN
                                $options = match (true) {
                                    $stepInt === 1 => [
                                        6 => 'Sangat senang, tidak bisa lebih puas lagi',
                                        5 => 'Sangat senang hampir sepanjang waktu',
                                        4 => 'Secara umum, puas, senang',
                                        3 => 'Terkadang cukup puas, terkadang tidak puas',
                                        2 => 'Secara umum tidak puas, tidak bahagia',
                                        1 => 'Sangat tidak puas, sering tidak bahagia',
                                    ],
                                    in_array($stepInt, [4, 5, 6, 7, 10, 17, 18, 22, 23, 26, 31, 34]) => [
                                        6 => 'Sepanjang Waktu',
                                        5 => 'Hampir Sepanjang Waktu',
                                        4 => 'Cukup Sering',
                                        3 => 'Kadang-kadang',
                                        2 => 'Jarang',
                                        1 => 'Tidak Pernah',
                                    ],
                                    in_array($stepInt, [2, 11, 13, 19, 29, 30, 36]) => [
                                        1 => 'Sepanjang Waktu',
                                        2 => 'Hampir Sepanjang Waktu',
                                        3 => 'Cukup Sering',
                                        4 => 'Kadang-kadang',
                                        5 => 'Jarang',
                                        6 => 'Tidak Pernah',
                                    ],
                                    in_array($stepInt, [3, 15, 16, 20, 21, 24, 27, 32, 35]) => [
                                        1 => 'Selalu',
                                        2 => 'Sangat Sering',
                                        3 => 'Cukup Sering',
                                        4 => 'Kadang-kadang',
                                        5 => 'Hampir Tidak Pernah',
                                        6 => 'Tidak Pernah',
                                    ],
                                    $stepInt === 12 => [
                                        6 => 'Selalu',
                                        5 => 'Sangat Sering',
                                        4 => 'Cukup Sering',
                                        3 => 'Kadang-kadang',
                                        2 => 'Hampir Tidak Pernah',
                                        1 => 'Tidak Pernah',
                                    ],
                                    $stepInt === 8 => [
                                        6 => 'Tidak, sama sekali tidak.',
                                        5 => 'Mungkin sedikit.',
                                        4 => 'Ya, tetapi tidak terlalu dikhawatirkan.',
                                        3 => 'Ya, dan saya sedikit khawatir.',
                                        2 => 'Ya, dan saya cukup khawatir.',
                                        1 => 'Ya, saya sangat khawatir tentang itu.',
                                    ],
                                    $stepInt === 9 => [
                                        1 => 'Ya, tertekan setiap hari',
                                        2 => 'Ya, sangat tertekan hampir setiap hari.',
                                        3 => 'Ya, cukup tertekan beberapa kali.',
                                        4 => 'Ya, sedikit tertekan sesekali.',
                                        5 => 'Tidak, tidak pernah sama sekali.',
                                    ],
                                    $stepInt === 14 => [
                                        6 => 'Ya, sangat yakin.',
                                        5 => 'Ya, untuk sebagian besar.',
                                        4 => 'Ya, saya rasa begitu.',
                                        3 => 'Tidak, kurang baik.',
                                        2 => 'Tidak, dan saya agak terganggu.',
                                        1 => 'Tidak, dan saya sangat terganggu.',
                                    ],
                                    $stepInt === 25 => [
                                        1 => 'Benar-benar terganggu.',
                                        2 => 'Sangat terganggu.',
                                        3 => 'Cukup terganggu oleh rasa gugup.',
                                        4 => 'Agak terganggu, cukup untuk menyadarinya.',
                                        5 => 'Sedikit terganggu oleh rasa gugup.',
                                        6 => 'Tidak terganggu sama sekali.',
                                    ],
                                    $stepInt === 28 => [
                                        1 => 'Ya, sangat sering.',
                                        2 => 'Ya, cukup sering.',
                                        3 => 'Ya, beberapa kali.',
                                        4 => 'Ya, satu kali.',
                                        5 => 'Tidak, tidak pernah.',
                                    ],
                                    $stepInt === 33 => [
                                        1 => 'Ya, sangat ekstrem.',
                                        2 => 'Ya, sangat terganggu.',
                                        3 => 'Ya, cukup terganggu.',
                                        4 => 'Ya, agak terganggu.',
                                        5 => 'Ya, tidak terlalu mengganggu.',
                                        6 => 'Tidak, sama sekali tidak.',
                                    ],
                                    $stepInt === 37 => [
                                        6 => 'Selalu, setiap hari.',
                                        5 => 'Hampir setiap hari.',
                                        4 => 'Sebagian besar hari.',
                                        3 => 'Beberapa hari.',
                                        2 => 'Hampir tidak pernah.',
                                        1 => 'Tidak pernah bangun segar.',
                                    ],
                                    $stepInt === 38 => [
                                        1 => 'Ya, hampir tak tertahankan.',
                                        2 => 'Ya, cukup banyak tekanan.',
                                        3 => 'Ya, sedikit lebih dari biasanya.',
                                        4 => 'Ya, batas normal.',
                                        5 => 'Ya, sedikit saja.',
                                        6 => 'Tidak, sama sekali tidak.',
                                    ],
                                    default => [],
                                };
                            @endphp

                            {{-- Render Opsi Jawaban --}}
                            @foreach ($options as $value => $label)
                                <div class="option-card">
                                    {{-- Radio Input (Hidden tapi ada) --}}
                                    <input type="radio" name="answer" id="opt_{{ $value }}"
                                        value="{{ $value }}" required>

                                    {{-- Label sebagai Trigger --}}
                                    <label class="option-label" for="opt_{{ $value }}">
                                        <div class="option-radio"></div>
                                        <span class="option-text">{{ $label }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit" class="btn btn-submit">
                            <span>{{ $step == 38 ? 'Selesai & Lihat Hasil' : 'Lanjut ke Soal Berikutnya' }}</span>
                            <i class="fas {{ $step == 38 ? 'fa-check-circle' : 'fa-arrow-right' }} ms-2"></i>
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    @if (View::exists('components.footer'))
        <x-footer></x-footer>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    {{-- ============================================================ --}}
    {{--               FITUR KEAMANAN (ANTI-COPY & SS)                --}}
    {{-- ============================================================ --}}

    <style>
        /* 1. Mencegah Text Selection (Biar gak bisa dicopy) */
        body {
            -webkit-user-select: none;
            /* Safari */
            -ms-user-select: none;
            /* IE 10 and IE 11 */
            user-select: none;
            /* Standard syntax */
        }

        /* 2. Sembunyikan konten saat mau di-Print (Ctrl+P) */
        @media print {

            html,
            body {
                display: none;
            }
        }

        /* 3. Watermark Transparan (Agar ketahuan jika di-SS) */
        .watermark-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            /* Agar tetap bisa diklik tembus */
            z-index: 9999;
            display: flex;
            flex-wrap: wrap;
            align-content: space-around;
            justify-content: space-around;
            opacity: 0.04;
            /* Sangat tipis, tidak mengganggu baca tapi terlihat di foto */
            overflow: hidden;
        }

        .watermark-text {
            transform: rotate(-45deg);
            font-size: 1.5rem;
            font-weight: bold;
            color: #000;
        }

        /* 4. Efek Blur saat ganti tab */
        body.is-blurred {
            filter: blur(8px) grayscale(100%);
            pointer-events: none;
            /* Gak bisa diklik pas blur */
        }
    </style>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // 1. Matikan Klik Kanan
            document.addEventListener('contextmenu', event => event.preventDefault());

            // 2. Matikan Tombol Shortcut (Ctrl+C, Ctrl+U, Ctrl+P, F12, PrintScreen)
            document.addEventListener('keydown', function(e) {
                // Block F12
                if (e.key === 'F12') {
                    e.preventDefault();
                    return false;
                }

                // Block Ctrl+Combinations
                if (e.ctrlKey && (
                        e.key === 'u' || // View Source
                        e.key === 's' || // Save
                        e.key === 'p' || // Print
                        e.key === 'c' || // Copy
                        e.key === 'a' // Select All
                    )) {
                    e.preventDefault();
                    return false;
                }
            });

            // 3. Deteksi Tombol PrintScreen (Hanya bisa dikosongkan clipboardnya di beberapa browser)
            document.addEventListener('keyup', function(e) {
                if (e.key == 'PrintScreen') {
                    navigator.clipboard.writeText(''); // Hapus clipboard
                    alert('Screenshot dilarang demi kerahasiaan tes!');
                    document.body.style.display = 'none'; // Sembunyikan body sebentar
                    setTimeout(() => {
                        document.body.style.display = 'block';
                    }, 1000);
                }
            });

            // 4. Fitur Blur Otomatis saat Ganti Tab / Aplikasi Lain
            // Ini efektif mengganggu user yang mau buka Snipping Tool
            window.addEventListener('blur', function() {
                document.title = "Dilarang Curang!";
                document.body.classList.add('is-blurred');
            });

            window.addEventListener('focus', function() {
                document.title = "{{ $title ?? 'Kuesioner' }}";
                document.body.classList.remove('is-blurred');
            });

            // 5. SweetAlert Resume (Jika ada sesi resume)
            @if (session('resume_alert'))
                Swal.fire({
                    icon: 'info',
                    title: 'Melanjutkan Tes',
                    text: "{{ session('resume_alert') }}",
                    confirmButtonText: 'Siap Lanjut!',
                    confirmButtonColor: '#19198c',
                    allowOutsideClick: false
                });
            @endif

        });
    </script>
</body>

</html>
