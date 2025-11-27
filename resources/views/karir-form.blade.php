<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tes Minat Rothwell-Miller (RMIB)</title>
    {{-- Pastikan CSS Bootstrap atau styling form Anda dimuat --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/karir-form.css') }}" rel="stylesheet">
    <!-- Font Awesome icons -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body>
    {{-- <x-navbar></x-navbar> --}}

    <div class="container mt-5 mb-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h1 class="card-title h3 mb-0">Tes Minat Rothwell-Miller (RMIB)</h1>
                <p class="mb-0">Peserta: {{ $dataDiri->nama }} ({{ $gender == 'L' ? 'Laki-laki' : 'Perempuan' }})</p>
            </div>
            <div class="card-body">
                <div class="alert alert-info" role="alert">
                    <h5 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Petunjuk Pengerjaan</h5>
                    <hr>
                    <p class="mb-2">
                        <strong>Berikut adalah 9 kelompok pekerjaan. Berikan peringkat 1 sampai 12 pada setiap
                            pekerjaan di masing-masing kelompok.
                            Peringkat 1 untuk pekerjaan yang paling Anda sukai, dan
                            peringkat 12 untuk yang paling tidak disukai.
                            Setiap angka (1-12) hanya boleh digunakan satu kali per kelompok.</strong>
                    </p>
                    <hr>
                    <p class="mb-2">
                        <i class="fas fa-note text-danger me-1"></i>
                        <strong>Penting: Tes ini mengukur tingkat kesukaan Anda terhadap suatu
                            pekerjaan,
                            terlepas dari pertimbangan gaji, beban kerja, tingkat kesulitan, atau faktor eksternal
                            lainnya.
                            Pilihlah berdasarkan minat dan ketertarikan murni Anda terhadap pekerjaan
                            tersebut.</strong>
                    </p>
                    <hr>
                    <p class="mb-0 ">
                        <i class="fas fa-clock me-1"></i>
                        <strong>Tidak perlu terburu-buru dalam mengerjakan tes ini. Luangkan waktu untuk
                            mempertimbangkan setiap pilihan dengan baik.
                            Sangat disarankan untuk mengerjakan dengan serius agar mendapatkan hasil
                            interpretasi yang akurat dan sesuai dengan minat Anda yang sebenarnya.</strong>
                    </p>
                </div>

                <form id="rmibForm" method="POST"
                    action="{{ route('karir.tes.store', ['data_diri' => $dataDiri->id]) }}">
                    @csrf

                    {{-- Grid Container untuk Kelompok Pekerjaan --}}
                    <div class="job-groups-grid">
                        {{-- Loop untuk membuat 9 Kelompok (A-I) --}}
                        @foreach ($pekerjaanPerKelompok as $index => $daftarPekerjaan)
                            {{-- Hitung karakter kelompok (A-I) dari index (0-8) --}}
                            @php
                                $kelompokHuruf = chr(65 + $index);
                            @endphp

                            <div class="job-ranking-group">
                                {{-- Tampilkan karakter kelompok (A-I) --}}
                                <h4 class="mb-3 text-center">Kelompok {{ $kelompokHuruf }}</h4>

                                {{-- Loop untuk 12 Pekerjaan per Kelompok --}}
                                @foreach ($daftarPekerjaan as $pekerjaan)
                                    {{-- $pekerjaan adalah string --}}
                                    <div class="job-item">
                                        <label
                                            for="rank_{{ $kelompokHuruf }}_{{ $loop->parent->index }}_{{ $loop->index }}"
                                            class="form-label">
                                            {{ $pekerjaan }}
                                        </label>
                                        <select class="form-select form-select-sm job-rank-input"
                                            id="rank_{{ $kelompokHuruf }}_{{ $loop->parent->index }}_{{ $loop->index }}"
                                            name="jawaban[{{ $kelompokHuruf }}][{{ $pekerjaan }}]"
                                            data-kelompok="{{ $kelompokHuruf }}" required>
                                            <option value="">--</option>
                                            @for ($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>

                    {{-- Select Top 1/2/3 --}}
                    <div class="top-choice-section">
                        <h4 class="mb-3">Pekerjaan yang Paling Anda Sukai (Top 3)</h4>
                        <p class="text-muted small">Pilih 3 pekerjaan yang paling mewakili minat Anda dari semua pilihan
                            di atas.</p>

                        <div class="mb-4">
                            <label for="top1" class="form-label fw-bold">Pilihan Peringkat 1:</label>
                            <select name="top1" id="top1" class="form-select" required>
                                <option value="" disabled selected>-- Pilih Pekerjaan --</option>
                                {{-- Loop untuk mengelompokkan pekerjaan berdasarkan Kelompok A - G --}}
                                @foreach ($pekerjaanPerKelompok as $index => $daftarPekerjaan)
                                    @php
                                        $kelompokHuruf = chr(65 + $index); // A, B, C, ...
                                    @endphp
                                    <optgroup label="Kelompok {{ $kelompokHuruf }}">
                                        @foreach ($daftarPekerjaan as $job)
                                            <option value="{{ $job }}">{{ $job }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="top2" class="form-label fw-bold">Pilihan Peringkat 2:</label>
                            <select name="top2" id="top2" class="form-select" required>
                                <option value="" disabled selected>-- Pilih Pekerjaan --</option>
                                {{-- Loop untuk mengelompokkan pekerjaan berdasarkan Kelompok A - G --}}
                                @foreach ($pekerjaanPerKelompok as $index => $daftarPekerjaan)
                                    @php
                                        $kelompokHuruf = chr(65 + $index); // A, B, C, ...
                                    @endphp
                                    <optgroup label="Kelompok {{ $kelompokHuruf }}">
                                        @foreach ($daftarPekerjaan as $job)
                                            <option value="{{ $job }}">{{ $job }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="top3" class="form-label fw-bold">Pilihan Peringkat 3:</label>
                            <select name="top3" id="top3" class="form-select" required>
                                <option value="" disabled selected>-- Pilih Pekerjaan --</option>
                                {{-- Loop untuk mengelompokkan pekerjaan berdasarkan Kelompok A - G --}}
                                @foreach ($pekerjaanPerKelompok as $index => $daftarPekerjaan)
                                    @php
                                        $kelompokHuruf = chr(65 + $index); // A, B, C, ...
                                    @endphp
                                    <optgroup label="Kelompok {{ $kelompokHuruf }}">
                                        @foreach ($daftarPekerjaan as $job)
                                            <option value="{{ $job }}">{{ $job }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Pekerjaan Lain di Luar List --}}
                    <div class="top-choice-section mt-4">
                        <h4 class="mb-3">Pekerjaan Lain yang Anda Minati</h4>
                        <p class="text-muted small">Jika ada pekerjaan lain yang Anda minati namun tidak terdapat dalam
                            daftar di atas, silakan tuliskan di bawah ini.</p>

                        <div class="mb-3">
                            <label for="pekerjaan_lain" class="form-label fw-bold">Nama Pekerjaan Lain:</label>
                            <input type="text" name="pekerjaan_lain" id="pekerjaan_lain" class="form-control"
                                placeholder="Contoh: Data Scientist, UX Designer, dll.">
                        </div>
                    </div>

                    {{-- Tombol Submit --}}
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-lg" id="submit-button">
                            Kirim Jawaban
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Arrow Scroll Button -->
    <div id="scroll-arrows">
        <button id="scroll-up" title="Scroll ke Atas">
            <i class="fas fa-arrow-up"></i>
        </button>
        <button id="scroll-down" title="Scroll ke Bawah">
            <i class="fas fa-arrow-down"></i>
        </button>
    </div>

    {{-- Load JS Bootstrap jika diperlukan --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    {{-- (Opsional) Tambahkan sedikit JS untuk validasi angka unik per kelompok --}}
    <script src="{{ asset('js/karir-form.js') }}"></script>
</body>

</html>
