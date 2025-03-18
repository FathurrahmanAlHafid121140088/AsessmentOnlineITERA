<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tes Minat Rothwell-Miller (RMIB)</title>
  <link href="{{ asset('css/karir-form.css') }}" rel="stylesheet">
</head>
<body>
  <div class="card">
    <div class="card-header">
      <h1 class="card-title">Tes Minat Rothwell-Miller (RMIB)</h1>
    </div>
    <div class="card-content">
      <div class="instruction-box">
        <p class="instruction-text">
          Berikut adalah beberapa jenis pekerjaan yang terbagi dalam 9 kategori. Setiap kategori memiliki 12 jenis pekerjaan atau aktivitas yang mungkin diminati/disukai. Tugas peserta adalah memberikan peringkat 1 sampai 12 pada setiap pekerjaan yang ada. Peringkat 1 untuk pekerjaan yang paling disukai, sedangkan peringkat 12 adalah pekerjaan yang paling tidak disukai. Peringkat disesuaikan dengan minat dan juga pandangan peserta, dan tidak harus dilakukan secara berurutan.
        </p>
      </div>
      
      <div id="categories-container">
        <!-- Categories will be inserted here by JavaScript -->
      </div>
      
      <div class="button-container">
        <button class="submit-button" id="submit-button">Kirim Hasil Tes</button>
      </div>
    </div>
  </div>
  <script src="{{ asset('js/karir-form.js') }}"></script>
</body>
</html>