<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hasil Tes Minat Rothwell-Miller (RMIB)</title>
  <link href="{{ asset('css/karir-hitung.css') }}" rel="stylesheet">
</head>
<body>
  <div class="card">
    <div class="card-header">
      <h1 class="card-title">Hasil Tes Minat Rothwell-Miller (RMIB)</h1>
    </div>
    <div class="card-content">
      <div class="instruction-box">
        <p class="instruction-text">
          Berikut adalah hasil tes minat karir Anda berdasarkan metode Rothwell-Miller Interest Blank (RMIB).
        </p>
      </div>
      
      <div class="table-container">
        <table id="rmib-result-table" class="result-table">
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
              <td>1. OUT</td>
              <td class="marked">x</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td>2. ME</td>
              <td></td>
              <td class="marked">x</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td>3. COMP</td>
              <td></td>
              <td></td>
              <td class="marked">x</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td>4. SCI</td>
              <td></td>
              <td></td>
              <td></td>
              <td class="marked">x</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td>5. PERS</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td class="marked">x</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td>6. AESTH</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td class="marked">x</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td>7. LIT</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td class="marked">x</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td>8. MUS</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td class="marked">x</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td>9. S.S</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td class="marked">x</td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td>10. CLER</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td>11. PRAC</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td>12. MED</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <div class="button-container">
        <button class="print-button" id="print-button">Cetak Hasil</button>
        <button class="back-button" id="back-button">Kembali</button>
      </div>
    </div>
  </div>
  <script src="{{ asset('js/karir-hitung.js') }}"></script>
</body>
</html>