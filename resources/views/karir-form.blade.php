<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tes Minat Rothwell-Miller (RMIB)</title>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/karir-form.css') }}" rel="stylesheet">
</head>

<body>
    <div class="card">
        <div class="card-header">
            <h1 class="card-title">Tes Minat Rothwell-Miller (RMIB)</h1>
            <p>Peserta: {{ $dataDiri->nama }} ({{ $dataDiri->gender == 'L' ? 'Laki-laki' : 'Perempuan' }})</p>
        </div>
        <div class="card-content">
            <div class="instruction-box">
                <p class="instruction-text">
                    Berikut adalah beberapa jenis pekerjaan yang terbagi dalam 9 kategori. Setiap kategori memiliki 12
                    jenis pekerjaan atau aktivitas yang mungkin diminati/disukai. Tugas peserta adalah memberikan
                    peringkat 1 sampai 12 pada setiap pekerjaan yang ada. Peringkat 1 untuk pekerjaan yang paling
                    disukai, sedangkan peringkat 12 adalah pekerjaan yang paling tidak disukai. Peringkat disesuaikan
                    dengan minat dan juga pandangan peserta, dan tidak harus dilakukan secara berurutan.
                </p>
            </div>

            <form method="POST" action="{{ route('karir.jawaban.store', ['id' => $dataDiri->id]) }}"> @csrf
                <!-- Tempat form isian pekerjaan -->
                <div id="categories-container" class="categories-grid"></div>

                <!-- Select Top 1/2/3 -->
                <div class="top-choice-section">
                    <h4>Pekerjaan yang Paling Anda Sukai (Top 3)</h4>

                    <div class="form-group">
                        <label for="top1">Top 1</label>
                        <select name="top1" id="top1" class="form-control" required>
                            <option value="">-- Pilih Pekerjaan --</option>
                            @if (isset($pekerjaan) && is_array($pekerjaan))
                                @foreach ($pekerjaan as $kategori => $jobs)
                                    @if (is_array($jobs))
                                        @foreach ($jobs as $job)
                                            <option value="{{ $job }}">{{ $job }}</option>
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="top2">Top 2</label>
                        <select name="top2" id="top2" class="form-control" required>
                            <option value="">-- Pilih Pekerjaan --</option>
                            @if (isset($pekerjaan) && is_array($pekerjaan))
                                @foreach ($pekerjaan as $kategori => $jobs)
                                    @if (is_array($jobs))
                                        @foreach ($jobs as $job)
                                            <option value="{{ $job }}">{{ $job }}</option>
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="top3">Top 3</label>
                        <select name="top3" id="top3" class="form-control" required>
                            <option value="">-- Pilih Pekerjaan --</option>
                            @if (isset($pekerjaan) && is_array($pekerjaan))
                                @foreach ($pekerjaan as $kategori => $jobs)
                                    @if (is_array($jobs))
                                        @foreach ($jobs as $job)
                                            <option value="{{ $job }}">{{ $job }}</option>
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" id="submit-button">Kirim Jawaban</button>
            </form>
        </div>
    </div>

    <script>
        // Data dari controller Laravel
        window.gender = "{{ $gender }}";
        window.pekerjaanData = @json($pekerjaan ?? []);
        window.dataDiriId = "{{ $dataDiri->id }}";
    </script>
    <script src="{{ asset('js/karir-form.js') }}"></script>
</body>

</html>
