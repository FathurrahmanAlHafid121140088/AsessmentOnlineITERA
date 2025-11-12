<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tes Minat Rothwell-Miller (RMIB)</title>
    {{-- Pastikan CSS Bootstrap atau styling form Anda dimuat --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/karir-form.css') }}" rel="stylesheet">
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
                    <p class="mb-0">
                        Berikut adalah 9 kelompok pekerjaan. Berikan **peringkat 1 sampai 12** pada setiap pekerjaan di
                        masing-masing kelompok.
                        **Peringkat 1** untuk pekerjaan yang paling Anda sukai, dan **peringkat 12** untuk yang paling
                        tidak disukai.
                        **Setiap angka (1-12) hanya boleh digunakan satu kali per kelompok.**
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
                                        <input type="number" class="form-control form-control-sm job-rank-input"
                                            {{-- Gunakan $kelompokHuruf di ID dan Name --}}
                                            id="rank_{{ $kelompokHuruf }}_{{ $loop->parent->index }}_{{ $loop->index }}"
                                            name="jawaban[{{ $kelompokHuruf }}][{{ $pekerjaan }}]" min="1"
                                            max="12" required placeholder="1-12">
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
                            <label for="top1_alasan" class="form-label mt-2 small text-muted">Jelaskan alasan Anda
                                memilih pekerjaan ini:</label>
                            <textarea name="top1_alasan" id="top1_alasan" class="form-control" rows="3"
                                placeholder="Tulis alasan atau minat Anda terhadap pekerjaan ini..."></textarea>
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
                            <label for="top2_alasan" class="form-label mt-2 small text-muted">Jelaskan alasan Anda
                                memilih pekerjaan ini:</label>
                            <textarea name="top2_alasan" id="top2_alasan" class="form-control" rows="3"
                                placeholder="Tulis alasan atau minat Anda terhadap pekerjaan ini..."></textarea>
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
                            <label for="top3_alasan" class="form-label mt-2 small text-muted">Jelaskan alasan Anda
                                memilih pekerjaan ini:</label>
                            <textarea name="top3_alasan" id="top3_alasan" class="form-control" rows="3"
                                placeholder="Tulis alasan atau minat Anda terhadap pekerjaan ini..."></textarea>
                        </div>
                    </div>

                    {{-- Pekerjaan Lain di Luar List --}}
                    <div class="top-choice-section mt-4">
                        <h4 class="mb-3">Pekerjaan Lain yang Anda Minati</h4>
                        <p class="text-muted small">Jika ada pekerjaan lain yang Anda minati namun tidak terdapat dalam
                            daftar di atas, silakan tuliskan di bawah ini beserta alasan Anda.</p>

                        <div class="mb-3">
                            <label for="pekerjaan_lain" class="form-label fw-bold">Nama Pekerjaan Lain:</label>
                            <input type="text" name="pekerjaan_lain" id="pekerjaan_lain" class="form-control"
                                placeholder="Contoh: Data Scientist, UX Designer, dll.">
                        </div>
                        <div class="mb-3">
                            <label for="pekerjaan_lain_alasan" class="form-label">Jelaskan alasan minat Anda terhadap
                                pekerjaan ini:</label>
                            <textarea name="pekerjaan_lain_alasan" id="pekerjaan_lain_alasan" class="form-control" rows="4"
                                placeholder="Tulis alasan atau minat Anda terhadap pekerjaan ini..."></textarea>
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

    {{-- Load JS Bootstrap jika diperlukan --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    {{-- (Opsional) Tambahkan sedikit JS untuk validasi angka unik per kelompok --}}
    <script src="{{ asset('js/karir-form.js') }}"></script>
</body>

</html>
