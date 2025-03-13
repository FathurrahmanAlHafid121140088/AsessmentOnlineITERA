<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tes Minat Rothwell-Miller (RMIB)</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    }
    
    body {
      min-height: 100vh;
      background-color: #f3f4f6;
      padding: 1rem;
      display: flex;
      justify-content: center;
      align-items: flex-start;
    }
    
    .card {
      width: 100%;
      max-width: 1000px;
      background-color: white;
      border-radius: 0.5rem;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      margin: 1rem auto;
    }
    
    .card-header {
      padding: 1.5rem;
      border-bottom: 1px solid #e5e7eb;
    }
    
    .card-title {
      font-size: 1.5rem;
      font-weight: 600;
      text-align: center;
      color: #111827;
    }
    
    .card-content {
      padding: 1.5rem;
    }
    
    .instruction-box {
      background-color: #eff6ff;
      padding: 1rem;
      border-radius: 0.5rem;
      margin-bottom: 1.5rem;
    }
    
    .instruction-text {
      font-size: 0.875rem;
      color: #1f2937;
      line-height: 1.5;
    }
    
    .category-container {
      margin-bottom: 1.5rem;
    }
    
    .category-title {
      font-size: 1.125rem;
      font-weight: 600;
      margin-bottom: 1rem;
      color: #1f2937;
    }
    
    table {
      width: 100%;
      border-collapse: collapse;
    }
    
    th, td {
      border: 1px solid #d1d5db;
      padding: 0.5rem 1rem;
    }
    
    th {
      background-color: #f9fafb;
      text-align: left;
      font-weight: 600;
    }
    
    th:last-child {
      width: 6rem;
      text-align: center;
    }
    
    input[type="number"] {
      width: 100%;
      padding: 0.25rem;
      border: 1px solid #d1d5db;
      border-radius: 0.25rem;
      text-align: center;
    }
    
    .error-message {
      color: #ef4444;
      font-size: 0.875rem;
      margin-top: 0.5rem;
      display: none;
    }
    
    .error-message.visible {
      display: block;
    }
    
    .button-container {
      text-align: center;
      margin-top: 1.5rem;
    }
    
    .submit-button {
      background-color: #3b82f6;
      color: white;
      padding: 0.5rem 1.5rem;
      border-radius: 0.25rem;
      border: none;
      font-size: 1rem;
      cursor: pointer;
      transition: background-color 0.2s;
    }
    
    .submit-button:hover {
      background-color: #2563eb;
    }
    
    .table-container {
      overflow-x: auto;
      margin-bottom: 0.5rem;
    }
    
    @media (max-width: 640px) {
      .card-title {
        font-size: 1.25rem;
      }
      
      th, td {
        padding: 0.5rem;
      }
    }
  </style>
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

  <script>
    // Data kategori dan pekerjaan
    const kategoriRMIB = [
      {
        name: 'A - Outdoor (OUT)',
        jobs: [
          'Petani',
          'Insinyur Sipil',
          'Akuntan',
          'Ilmuwan',
          'Manager Penjualan',
          'Seniman',
          'Wartawan',
          'Pianis Konser',
          'Guru SD',
          'Manager Bank',
          'Tukang Kayu',
          'Dokter'
        ]
      },
      {
        name: 'B - Mechanical (ME)',
        jobs: [
          'Ahhli Pembuat Alat',
          'Ahli Statistik',
          'Insinyur Kimia Industri',
          'Penyiar Radio',
          'Artis Profesional',
          'Pengarang',
          'Dirigen Orkestra',
          'Psikolog Pendidikan',
          'Sekretaris Perusahaan',
          'Ahli Bangunan',
          'Ahli Bedah',
          'Ahli Kehutanan'
        ]
      },
      {
        name: 'C - Computational (COMP)',
        jobs: [
          'Auditor',
          'Ahli Meteorologi',
          'Salesman',
          'Arsitek',
          'Penulis Drama',
          'Komponis',
          'Kepala Sekolah',
          'Pegawai Kotapraja (PNS)',
          'Ahli Meubel/ Furniture',
          'Dokter Hewan',
          'Juru Ukur Tanah',
          'Tukang Bubut/ Lemer'
        ]
      },
      {
        name: 'D - Scientific (SCI)',
        jobs: [
          'Ahli Biologi',
          'Agen Biro Periklanan',
          'Dekorator Interior',
          'Ahli Sejarah',
          'Kritikus Musik',
          'Pekerja Sosial',
          'Pegawai Asuransi',
          'Tukang Cat',
          'Apoteker',
          'Penjelajah',
          'Tukang Listrik',
          'Penilai Pajak Pendapatan'
        ]
      },
      {
        name: 'E - Personal Contact (PERS)',
        jobs: [
          'Petugas Wawancara',
          'Perancang Perhiasan',
          'Ahli Perpustakaan',
          'Guru Musik',
          'Pembina Rohani',
          'Petugas Arsip',
          'Tukang Batu',
          'Dokter Gigi',
          'Prospektor (🔍)',
          'Montir',
          'Guru Ilmu Pasti',
          'Ahli Pertanian'
        ]
      },
      {
        name: 'F - Aesthetic (AESTH)',
        jobs: [
          'Fotografer',
          'Penulis Majalah',
          'Pemain Orgen Tunggal (🔍)',
          'Organisasi Pramuka',
          'Petugas Pengiriman Barang',
          'Petugas Mesin Perkayuan',
          'Ahli Kacamata',
          'Ahli Sortir Kulit',
          'Instalator',
          'Asisten Kasir Bank',
          'Ahli Botani',
          'Pedagang Keliling'
        ]
      },
      {
        name: 'G - Literary (LIT)',
        jobs: [
          'Kritikus Buku',
          'Ahli Pustaka Musik',
          'Pengurus Karang Taruna (🔍)',
          'Pegawai Kantor',
          'Tukang Plester Tembok',
          'Ahli Rontgent',
          'Nelayan',
          'Pembuat Arloji',
          'Kasir',
          'Astronomi',
          'Juru Lelang',
          'Penata Panggung'
        ]
      },
      {
        name: 'H - Musical (MUS)',
        jobs: [
          'Pemain Musik Band',
          'Ahli Penyuluh Jabatan',
          'Pegawai Pos',
          'Tukang Ledeng/ Pipa Air',
          'Ahli Fisioterapi',
          'Sopir Angkutan Umum',
          'Montir Radio',
          'Juru Bayar',
          'Ahli Geologi',
          'Petugas Hubungan Masyarakat',
          'Penata Etalase',
          'Penulis Sandiawara Radio'
        ]
      },
      
      // Tambahkan sisa kategori sesuai kebutuhan
    ];

    // Objek untuk menyimpan peringkat
    const peringkat = {};
    
    // Inisialisasi peringkat
    kategoriRMIB.forEach(kategori => {
      peringkat[kategori.name] = {};
      kategori.jobs.forEach(job => {
        peringkat[kategori.name][job] = null;
      });
    });

    // Fungsi untuk mengecek peringkat unik
    function cekPeringkatUnik(namaKategori) {
      const peringkatKategori = Object.values(peringkat[namaKategori]);
      const peringkatValid = peringkatKategori.filter(r => r !== null && r >= 1 && r <= 12);
      const peringkatUnik = new Set(peringkatValid);
      return peringkatUnik.size === peringkatValid.length;
    }

    // Fungsi untuk membangun UI
    function buildUI() {
      const categoriesContainer = document.getElementById('categories-container');
      
      kategoriRMIB.forEach(kategori => {
        // Buat container untuk kategori
        const categoryDiv = document.createElement('div');
        categoryDiv.className = 'category-container';
        
        // Buat judul kategori
        const categoryTitle = document.createElement('h3');
        categoryTitle.className = 'category-title';
        categoryTitle.textContent = kategori.name;
        categoryDiv.appendChild(categoryTitle);
        
        // Buat container untuk tabel
        const tableContainer = document.createElement('div');
        tableContainer.className = 'table-container';
        
        // Buat tabel
        const table = document.createElement('table');
        
        // Buat header tabel
        const thead = document.createElement('thead');
        const headerRow = document.createElement('tr');
        
        const jobHeader = document.createElement('th');
        jobHeader.textContent = 'Pekerjaan';
        
        const rankHeader = document.createElement('th');
        rankHeader.textContent = 'Peringkat (1-12)';
        
        headerRow.appendChild(jobHeader);
        headerRow.appendChild(rankHeader);
        thead.appendChild(headerRow);
        table.appendChild(thead);
        
        // Buat body tabel
        const tbody = document.createElement('tbody');
        
        kategori.jobs.forEach(job => {
          const row = document.createElement('tr');
          
          const jobCell = document.createElement('td');
          jobCell.textContent = job;
          
          const rankCell = document.createElement('td');
          const input = document.createElement('input');
          input.type = 'number';
          input.min = '1';
          input.max = '12';
          input.placeholder = '1-12';
          input.value = peringkat[kategori.name][job] || '';
          
          input.addEventListener('change', (e) => {
            const value = e.target.value ? parseInt(e.target.value) : null;
            peringkat[kategori.name][job] = value;
            
            // Tampilkan/sembunyikan pesan error
            const errorMessage = categoryDiv.querySelector('.error-message');
            if (!cekPeringkatUnik(kategori.name)) {
              errorMessage.classList.add('visible');
            } else {
              errorMessage.classList.remove('visible');
            }
          });
          
          rankCell.appendChild(input);
          row.appendChild(jobCell);
          row.appendChild(rankCell);
          tbody.appendChild(row);
        });
        
        table.appendChild(tbody);
        tableContainer.appendChild(table);
        categoryDiv.appendChild(tableContainer);
        
        // Buat pesan error
        const errorMessage = document.createElement('p');
        errorMessage.className = 'error-message';
        errorMessage.textContent = 'Pastikan setiap pekerjaan memiliki peringkat unik (1-12)';
        categoryDiv.appendChild(errorMessage);
        
        categoriesContainer.appendChild(categoryDiv);
      });
    }

    // Inisialisasi UI
    buildUI();

    // Event listener untuk tombol submit
    document.getElementById('submit-button').addEventListener('click', () => {
      // Cek validitas semua kategori
      let valid = true;
      
      kategoriRMIB.forEach(kategori => {
        if (!cekPeringkatUnik(kategori.name)) {
          valid = false;
          // Tampilkan semua pesan error
          const errorMessages = document.querySelectorAll('.error-message');
          errorMessages.forEach(msg => {
            if (!cekPeringkatUnik(kategori.name)) {
              msg.classList.add('visible');
            }
          });
        }
      });
      
      if (valid) {
        alert('Terima kasih! Hasil tes Anda telah dikirim.');
        console.log('Data peringkat:', peringkat);
        // Di sini Anda dapat menambahkan kode untuk mengirim data ke server
      } else {
        alert('Mohon periksa kembali input Anda. Pastikan setiap pekerjaan memiliki peringkat unik (1-12).');
      }
    });
  </script>
</body>
</html>