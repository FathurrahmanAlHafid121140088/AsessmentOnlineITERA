/**
 * RMIB AUTOFILL SCRIPT - FOR BROWSER CONSOLE
 *
 * Cara Penggunaan:
 * 1. Buka halaman form RMIB di browser
 * 2. Buka Console Browser (F12 atau Ctrl+Shift+J)
 * 3. Copy-paste script ini ke console
 * 4. Ubah variabel `minatKategori` sesuai preferensi Anda
 * 5. Jalankan function: autoFillRMIB()
 *
 * Contoh:
 * - Jika Anda sangat tertarik pada Scientific, letakkan di urutan pertama
 * - Kategori di awal list akan mendapat ranking lebih rendah (lebih disukai)
 * - Kategori di akhir list akan mendapat ranking lebih tinggi (kurang disukai)
 */

// ========================================
// KONFIGURASI MINAT KATEGORI
// ========================================
// Urutkan dari yang PALING DIMINATI ke yang KURANG DIMINATI
// Format: Gunakan singkatan kategori yang ada di sistem
const minatKategori = [
    'Scientific',      // Paling diminati
    'Computational',
    'Literary',
    'Personal Contact',
    'Social Service',
    'Aesthetic',
    'Mechanical',
    'Clerical',
    'Musical',
    'Outdoor',
    'Practical',
    'Medical'         // Kurang diminati
];

// ========================================
// MAPPING PEKERJAAN KE KATEGORI
// ========================================
const mappingPria = {
    'A': {
        'Petani': 'Outdoor',
        'Insinyur Sipil': 'Mechanical',
        'Akuntan': 'Computational',
        'Ilmuwan': 'Scientific',
        'Manager Penjualan': 'Personal Contact',
        'Seniman': 'Aesthetic',
        'Wartawan': 'Literary',
        'Pianis Konser': 'Musical',
        'Guru SD': 'Social Service',
        'Manager Bank': 'Clerical',
        'Tukang Kayu': 'Practical',
        'Dokter': 'Medical'
    },
    'B': {
        'Ahli Pembuat Alat': 'Mechanical',
        'Ahli Statistik': 'Computational',
        'Insinyur Kimia Industri': 'Scientific',
        'Penyiar Radio': 'Personal Contact',
        'Artis Profesional': 'Aesthetic',
        'Pengarang': 'Literary',
        'Dirigen Orkestra': 'Musical',
        'Psikolog Pendidikan': 'Social Service',
        'Sekretaris Perusahaan': 'Clerical',
        'Ahli Bangunan': 'Practical',
        'Ahli Bedah': 'Medical',
        'Ahli Kehutanan': 'Outdoor'
    },
    'C': {
        'Auditor': 'Computational',
        'Ahli Meteorologi': 'Scientific',
        'Salesman': 'Personal Contact',
        'Arsitek': 'Aesthetic',
        'Penulis Drama': 'Literary',
        'Komponis': 'Musical',
        'Kepala Sekolah': 'Social Service',
        'Pegawai Kecamatan': 'Clerical',
        'Ahli Meubel/ Furniture': 'Practical',
        'Dokter Hewan': 'Medical',
        'Juru Ukur Tanah': 'Outdoor',
        'Tukang Bubut/ Lemer': 'Mechanical'
    },
    'D': {
        'Ahli Biologi': 'Scientific',
        'Agen Biro Periklanan': 'Personal Contact',
        'Dekorator Interior': 'Aesthetic',
        'Ahli Sejarah': 'Literary',
        'Kritikus Musik': 'Musical',
        'Pekerja Sosial': 'Social Service',
        'Pegawai Asuransi': 'Clerical',
        'Tukang Cat': 'Practical',
        'Apoteker': 'Medical',
        'Penjelajah': 'Outdoor',
        'Tukang Listrik': 'Mechanical',
        'Penilai Pajak Pendapatan': 'Computational'
    },
    'E': {
        'Petugas Wawancara': 'Personal Contact',
        'Perancang Perhiasan': 'Aesthetic',
        'Ahli Perpustakaan': 'Literary',
        'Guru Musik': 'Musical',
        'Pembina Rohani': 'Social Service',
        'Petugas Arsip': 'Clerical',
        'Tukang Batu': 'Practical',
        'Dokter Gigi': 'Medical',
        'Prospektor': 'Outdoor',
        'Montir': 'Mechanical',
        'Guru Ilmu Pasti': 'Computational',
        'Ahli Pertanian': 'Scientific'
    },
    'F': {
        'Fotografer': 'Aesthetic',
        'Penulis Majalah': 'Literary',
        'Pemain Orgen Tunggal': 'Musical',
        'Organisator Kepramukaan': 'Social Service',
        'Petugas Pengirim Barang': 'Clerical',
        'Operator Mesin Perkayuan': 'Practical',
        'Ahli Kacamata': 'Medical',
        'Ahli Sortir Kulit': 'Outdoor',
        'Instalator': 'Mechanical',
        'Asisten Kasir Bank': 'Computational',
        'Ahli Botani': 'Scientific',
        'Pedagang Keliling': 'Personal Contact'
    },
    'G': {
        'Kritikus Buku': 'Literary',
        'Ahli Pustaka Musik': 'Musical',
        'Pengurus Karang Taruna': 'Social Service',
        'Pegawai Kantor': 'Clerical',
        'Tukang Plester Tembok': 'Practical',
        'Ahli Rontgent': 'Medical',
        'Nelayan': 'Outdoor',
        'Pembuat Arloji': 'Mechanical',
        'Kasir': 'Computational',
        'Ahli Astronomi': 'Scientific',
        'Juru Lelang': 'Personal Contact',
        'Penata Panggung': 'Aesthetic'
    },
    'H': {
        'Pemain Musik Band': 'Musical',
        'Ahli Penyuluh Jabatan': 'Social Service',
        'Pegawai Kantor Pos': 'Clerical',
        'Tukang Ledeng/ Pipa Air': 'Practical',
        'Ahli Fisioterapi': 'Medical',
        'Sopir Angkutan Umum': 'Outdoor',
        'Montir Radio': 'Mechanical',
        'Juru Bayar': 'Computational',
        'Ahli Geologi': 'Scientific',
        'Petugas Hubungan Masyarakat': 'Personal Contact',
        'Penata Etalase': 'Aesthetic',
        'Penulis Sandiwara Radio': 'Literary'
    },
    'I': {
        'Petugas Kesejahteraan Sosial': 'Social Service',
        'Petugas Ekspedisi Surat': 'Clerical',
        'Tukang Sepatu': 'Practical',
        'Paramedik/Mantri Kesehatan': 'Medical',
        'Petani Tanaman Hias': 'Outdoor',
        'Tukang Las': 'Mechanical',
        'Petugas Pajak': 'Computational',
        'Asisten Laboratorium': 'Scientific',
        'Salesman Asuransi': 'Personal Contact',
        'Perancang Motif Tekstil': 'Aesthetic',
        'Penyair': 'Literary',
        'Pramuniaga Toko Musik': 'Musical'
    }
};

const mappingWanita = {
    'A': {
        'Pekerjaan Pertanian': 'Outdoor',
        'Pengemudi Kendaraan Militer': 'Mechanical',
        'Akuntan': 'Computational',
        'Ilmuwan': 'Scientific',
        'Penjual produk fashion': 'Personal Contact',
        'Seniawati/ Seniman Wanita': 'Aesthetic',
        'Wartawati/ Wartawan Wanita': 'Literary',
        'Pianis Konser': 'Musical',
        'Guru SD': 'Social Service',
        'Sekretaris Pribadi': 'Clerical',
        'Modiste/ Penjahit Baju Khusus Wanita': 'Practical',
        'Dokter': 'Medical'
    },
    'B': {
        'Petugas Assembling/ Perakitan Alat': 'Mechanical',
        'Pegawai urusan gaji': 'Computational',
        'Insinyur Kimia Industri': 'Scientific',
        'Penyiar Radio': 'Personal Contact',
        'Artis Profesional': 'Aesthetic',
        'Pengarang': 'Literary',
        'Pemain Musik Orkestra': 'Musical',
        'Psikolog Pendidikan': 'Social Service',
        'Juru TIK': 'Clerical',
        'Pembuat Pot Keramik': 'Practical',
        'Ahli Bedah': 'Medical',
        'Guru Pendidikan Jasmani': 'Outdoor'
    },
    'C': {
        'Auditor': 'Computational',
        'Ahli Meteorologi': 'Scientific',
        'Salesgirl': 'Personal Contact',
        'Guru Kesenian': 'Aesthetic',
        'Penulis Naskah Drama': 'Literary',
        'Komponis': 'Musical',
        'Kepala Yayasan Sosial': 'Social Service',
        'Resepsionis': 'Clerical',
        'Penata Rambut': 'Practical',
        'Dokter Hewan': 'Medical',
        'Pramugari': 'Outdoor',
        'Operator Mesin Rajut': 'Mechanical'
    },
    'D': {
        'Ahli Biologi': 'Scientific',
        'Agen Biro Periklanan': 'Personal Contact',
        'Dekorator Interior': 'Aesthetic',
        'Ahli Sejarah': 'Literary',
        'Kritikus Musik': 'Musical',
        'Pekerja Sosial': 'Social Service',
        'Penulis Steno': 'Clerical',
        'Penjilid Buku': 'Practical',
        'Apoteker': 'Medical',
        'Ahli Pertamanan': 'Outdoor',
        'Petugas Pompa Bensin': 'Mechanical',
        'Petugas Mesin Hitung': 'Computational'
    },
    'E': {
        'Petugas Wawancara': 'Personal Contact',
        'Perancang Pakaian': 'Aesthetic',
        'Ahli Perpustakaan': 'Literary',
        'Guru Musik': 'Musical',
        'Pembina Agama': 'Social Service',
        'Petugas Arsip': 'Clerical',
        'Tukang Bungkus Cokelat': 'Practical',
        'Pelatih Rehabilitasi Pasien': 'Medical',
        'Pembina Keolahragaan': 'Outdoor',
        'Ahli Reparasi Jam': 'Mechanical',
        'Guru Ilmu Pasti': 'Computational',
        'Ahli Pertanian': 'Scientific'
    },
    'F': {
        'Fotografer': 'Aesthetic',
        'Penulis Majalah': 'Literary',
        'Pemain Orgen Tunggal': 'Musical',
        'Petugas Palang Merah': 'Social Service',
        'Pegawai Bank': 'Clerical',
        'Pengurus Kerumahtanggan': 'Practical',
        'Perawat': 'Medical',
        'Peternak': 'Outdoor',
        'Ahli Gosok Lensa/ Lens Grinder': 'Mechanical',
        'Kasir': 'Computational',
        'Ahli Botani': 'Scientific',
        'Pedagang Keliling': 'Personal Contact'
    },
    'G': {
        'Kritikus Buku': 'Literary',
        'Ahli Pustaka Musik': 'Musical',
        'Pejabat Klub Remaja': 'Social Service',
        'Pegawai Kantor': 'Clerical',
        'Tukang Binatu/ Laundry': 'Practical',
        'Ahli Rontgen': 'Medical',
        'Petani Bunga': 'Outdoor',
        'Operator Mesin Sulam': 'Mechanical',
        'Ahli Tata Buku': 'Computational',
        'Ahli Astronomi': 'Scientific',
        'Peraga Alat Kosmetika': 'Personal Contact',
        'Penata Panggung': 'Aesthetic'
    },
    'H': {
        'Pemain Musik Band': 'Musical',
        'Ahli Penyuluh Jabatan': 'Social Service',
        'Pegawai Kantor Pos': 'Clerical',
        'Penjahit': 'Practical',
        'Ahli Fisioterapi': 'Medical',
        'Peternak Ayam': 'Outdoor',
        'Ahli Reparasi Permata': 'Mechanical',
        'Juru Bayar': 'Computational',
        'Ahli Geologi': 'Scientific',
        'Petugas Hubungan Masyarakat': 'Personal Contact',
        'Penata Etalase': 'Aesthetic',
        'Penulis Sandiwara Radio': 'Literary'
    },
    'I': {
        'Petugas Kesejahteraan Sosial': 'Social Service',
        'Penyusun Arsip': 'Clerical',
        'Juru Masak': 'Practical',
        'Perawat Orang Tua': 'Medical',
        'Tukang Kebun': 'Outdoor',
        'Petugas Mesin Kaos': 'Mechanical',
        'Pegawai Pajak': 'Computational',
        'Asisten Laboratorium': 'Scientific',
        'Peraga Barang-barang/ Bahan': 'Personal Contact',
        'Perancang Motif Tekstil': 'Aesthetic',
        'Penyair': 'Literary',
        'Pramuniaga Toko Musik': 'Musical'
    }
};

// ========================================
// FUNGSI UTILITAS
// ========================================

/**
 * Deteksi gender dari form (L/P)
 */
function detectGender() {
    const genderText = document.querySelector('.card-header p')?.textContent || '';
    if (genderText.includes('Laki-laki')) return 'L';
    if (genderText.includes('Perempuan')) return 'P';
    return 'L'; // Default
}

/**
 * Dapatkan mapping pekerjaan berdasarkan gender
 */
function getMapping() {
    const gender = detectGender();
    return gender === 'P' ? mappingWanita : mappingPria;
}

/**
 * Hitung skor prioritas kategori
 * Semakin kecil skor = semakin diminati
 */
function getKategoriPrioritas(kategori) {
    const index = minatKategori.indexOf(kategori);
    return index === -1 ? 999 : index;
}

/**
 * Assign ranking ke pekerjaan berdasarkan kategori
 */
function assignRanking(pekerjaanList) {
    // Buat array pekerjaan dengan prioritasnya
    const pekerjaanWithPriority = Object.entries(pekerjaanList).map(([nama, kategori]) => ({
        nama,
        kategori,
        prioritas: getKategoriPrioritas(kategori)
    }));

    // Urutkan berdasarkan prioritas (ascending = yang paling diminati di awal)
    pekerjaanWithPriority.sort((a, b) => a.prioritas - b.prioritas);

    // Assign ranking 1-12
    const result = {};
    pekerjaanWithPriority.forEach((item, index) => {
        result[item.nama] = index + 1;
    });

    return result;
}

// ========================================
// FUNGSI AUTOFILL UTAMA
// ========================================

function autoFillRMIB() {
    console.log('🚀 Memulai AutoFill RMIB...');
    console.log('📊 Minat Kategori (Prioritas):');
    minatKategori.forEach((kat, idx) => {
        console.log(`   ${idx + 1}. ${kat}`);
    });

    const mapping = getMapping();
    const gender = detectGender();
    console.log(`\n👤 Gender Terdeteksi: ${gender === 'L' ? 'Laki-laki' : 'Perempuan'}`);

    let totalFilled = 0;

    // Loop untuk setiap kelompok (A-I)
    Object.keys(mapping).forEach(kelompok => {
        const pekerjaanDiKelompok = mapping[kelompok];
        const rankingMap = assignRanking(pekerjaanDiKelompok);

        console.log(`\n📝 Mengisi Kelompok ${kelompok}:`);

        // Isi setiap input di kelompok ini
        Object.entries(rankingMap).forEach(([namaPekerjaan, ranking]) => {
            // Cari input berdasarkan name attribute
            const input = document.querySelector(`input[name="jawaban[${kelompok}][${namaPekerjaan}]"]`);

            if (input) {
                input.value = ranking;
                // Trigger event untuk validasi
                input.dispatchEvent(new Event('input', { bubbles: true }));
                input.dispatchEvent(new Event('change', { bubbles: true }));
                totalFilled++;
                console.log(`   ✓ ${namaPekerjaan} (${pekerjaanDiKelompok[namaPekerjaan]}) → Ranking ${ranking}`);
            } else {
                console.warn(`   ⚠ Input tidak ditemukan: ${namaPekerjaan}`);
            }
        });
    });

    console.log(`\n✅ Autofill selesai! Total ${totalFilled} input terisi.`);

    // Autofill Top 3 (pilih 3 pekerjaan dari kategori favorit)
    autoFillTop3(mapping);
}

/**
 * Autofill dropdown Top 3 dengan pekerjaan dari kategori favorit
 */
function autoFillTop3(mapping) {
    console.log('\n🏆 Mengisi Top 3 Pilihan...');

    // Ambil 3 kategori favorit
    const top3Kategori = minatKategori.slice(0, 3);
    const selectedJobs = [];

    // Cari pekerjaan yang sesuai dengan top 3 kategori
    for (const kategori of top3Kategori) {
        for (const kelompok in mapping) {
            for (const [pekerjaan, kat] of Object.entries(mapping[kelompok])) {
                if (kat === kategori && !selectedJobs.includes(pekerjaan)) {
                    selectedJobs.push(pekerjaan);
                    break;
                }
            }
            if (selectedJobs.length >= 3) break;
        }
        if (selectedJobs.length >= 3) break;
    }

    // Isi dropdown top1, top2, top3
    ['top1', 'top2', 'top3'].forEach((id, index) => {
        const select = document.getElementById(id);
        if (select && selectedJobs[index]) {
            select.value = selectedJobs[index];
            select.dispatchEvent(new Event('change', { bubbles: true }));
            console.log(`   ✓ ${id}: ${selectedJobs[index]}`);
        }
    });

    console.log('\n🎉 Autofill Top 3 selesai!');
}

// ========================================
// FUNGSI TAMBAHAN: CUSTOM KATEGORI
// ========================================

/**
 * Set minat kategori secara custom
 * Contoh: setMinatKategori(['Scientific', 'Literary', 'Computational', ...])
 */
function setMinatKategori(arrayKategori) {
    if (!Array.isArray(arrayKategori) || arrayKategori.length !== 12) {
        console.error('❌ Error: Array harus berisi 12 kategori!');
        console.log('Kategori yang tersedia:');
        console.log(['Outdoor', 'Mechanical', 'Computational', 'Scientific', 'Personal Contact',
                     'Aesthetic', 'Literary', 'Musical', 'Social Service', 'Clerical', 'Practical', 'Medical']);
        return;
    }

    // Update minatKategori
    minatKategori.length = 0;
    minatKategori.push(...arrayKategori);

    console.log('✅ Minat kategori berhasil diubah!');
    console.log('Urutan baru (dari paling diminati):');
    minatKategori.forEach((kat, idx) => {
        console.log(`   ${idx + 1}. ${kat}`);
    });
}

// ========================================
// INSTRUKSI PENGGUNAAN
// ========================================

console.log(`
╔════════════════════════════════════════════════════════════════╗
║           RMIB AUTOFILL SCRIPT - LOADED ✅                     ║
╚════════════════════════════════════════════════════════════════╝

📖 CARA PENGGUNAAN:

1. QUICK START (Gunakan preferensi default):
   autoFillRMIB()

2. CUSTOM KATEGORI (Ubah urutan preferensi dulu):
   setMinatKategori([
       'Scientific',      // Paling diminati
       'Computational',
       'Literary',
       'Personal Contact',
       'Social Service',
       'Aesthetic',
       'Mechanical',
       'Clerical',
       'Musical',
       'Outdoor',
       'Practical',
       'Medical'         // Kurang diminati
   ])

   Lalu jalankan:
   autoFillRMIB()

📌 KATEGORI YANG TERSEDIA:
   - Outdoor, Mechanical, Computational, Scientific
   - Personal Contact, Aesthetic, Literary, Musical
   - Social Service, Clerical, Practical, Medical

💡 TIPS:
   - Kategori di awal list = ranking lebih rendah (lebih disukai)
   - Kategori di akhir list = ranking lebih tinggi (kurang disukai)
   - Setelah autofill, Anda bisa edit manual jika perlu

═══════════════════════════════════════════════════════════════════
`);
