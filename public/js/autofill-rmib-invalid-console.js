/**
 * RMIB AUTOFILL SCRIPT (INVALID VERSION) - FOR BROWSER CONSOLE
 *
 * TUJUAN: Script ini akan mengisi form RMIB dengan pola TIDAK KONSISTEN
 * sehingga menghasilkan SKOR KONSISTENSI RENDAH (< 7) dan VALIDITAS "TIDAK VALID"
 *
 * Cara Penggunaan:
 * 1. Buka halaman form RMIB di browser
 * 2. Buka Console Browser (F12 atau Ctrl+Shift+J)
 * 3. Copy-paste script ini ke console
 * 4. Jalankan function: autoFillRMIB_Invalid()
 *
 * CATATAN:
 * - Script ini sengaja memberikan peringkat secara ACAK/RANDOM
 * - Tidak ada pola konsisten dalam pemilihan
 * - Digunakan untuk testing validitas tes RMIB
 */

// ========================================
// FUNGSI HELPER
// ========================================

/**
 * Fungsi untuk shuffle array (Fisher-Yates Algorithm)
 */
function shuffleArray(array) {
    const arr = [...array]; // Clone array
    for (let i = arr.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [arr[i], arr[j]] = [arr[j], arr[i]];
    }
    return arr;
}

/**
 * Fungsi untuk mendapatkan angka random antara min dan max
 */
function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

/**
 * Fungsi untuk generate peringkat yang TIDAK KONSISTEN
 * Strategi: Memberikan peringkat secara random di setiap kelompok
 */
function generateInconsistentRankings() {
    // Array peringkat 1-12
    const rankings = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];

    // Shuffle secara random
    return shuffleArray(rankings);
}

// ========================================
// MAIN FUNCTION - AUTOFILL RMIB (INVALID)
// ========================================
function autoFillRMIB_Invalid() {
    console.log('%c🔴 RMIB AUTOFILL - INVALID MODE (INCONSISTENT) 🔴', 'color: red; font-size: 16px; font-weight: bold;');
    console.log('%cScript ini akan mengisi form dengan pola TIDAK KONSISTEN', 'color: orange; font-size: 12px;');
    console.log('%cHasil: Skor Konsistensi RENDAH (< 7), Validitas: TIDAK VALID', 'color: orange; font-size: 12px;');
    console.log('');

    // Ambil semua input peringkat
    const inputs = document.querySelectorAll('input[name^="jawaban"]');

    if (inputs.length === 0) {
        console.error('❌ Tidak ada input form yang ditemukan! Pastikan Anda berada di halaman form RMIB.');
        return;
    }

    // Kelompokkan input berdasarkan kelompok (A-I)
    const kelompokMap = {};

    inputs.forEach(input => {
        // Ambil name attribute: jawaban[A][Petani]
        const nameMatch = input.name.match(/jawaban\[([A-I])\]\[(.+)\]/);

        if (nameMatch) {
            const kelompok = nameMatch[1];
            const pekerjaan = nameMatch[2];

            if (!kelompokMap[kelompok]) {
                kelompokMap[kelompok] = [];
            }

            kelompokMap[kelompok].push({
                input: input,
                pekerjaan: pekerjaan
            });
        }
    });

    console.log(`📊 Ditemukan ${Object.keys(kelompokMap).length} kelompok pekerjaan`);
    console.log('');

    // Isi setiap kelompok dengan peringkat RANDOM (TIDAK KONSISTEN)
    let totalFilled = 0;

    Object.keys(kelompokMap).sort().forEach(kelompok => {
        const jobs = kelompokMap[kelompok];

        // Generate peringkat random untuk kelompok ini
        const randomRankings = generateInconsistentRankings();

        console.log(`🔴 Kelompok ${kelompok}: Mengisi dengan peringkat ACAK (tidak konsisten)`);

        jobs.forEach((job, index) => {
            const ranking = randomRankings[index];
            job.input.value = ranking;
            totalFilled++;
        });
    });

    console.log('');
    console.log(`✅ Selesai! Total ${totalFilled} pekerjaan telah diisi dengan peringkat TIDAK KONSISTEN`);
    console.log('');
    console.log('%c⚠️ PENTING: Hasil tes ini akan memiliki VALIDITAS "TIDAK VALID"', 'color: red; font-size: 14px; font-weight: bold;');
    console.log('%cSkor konsistensi akan RENDAH (< 7) karena tidak ada pola konsisten', 'color: orange; font-size: 12px;');
    console.log('');
    console.log('📝 Langkah selanjutnya:');
    console.log('1. Scroll ke bawah untuk mengisi pilihan Top 1, 2, 3');
    console.log('2. Klik tombol "Kirim Jawaban"');
    console.log('3. Hasil tes akan menunjukkan validitas "Tidak Valid"');
}

// ========================================
// VARIASI AUTOFILL - MODE SEMI-RANDOM
// ========================================
/**
 * Fungsi alternatif: Memberikan peringkat semi-random
 * (Agak konsisten tapi masih menghasilkan skor rendah)
 */
function autoFillRMIB_SemiRandom() {
    console.log('%c🟠 RMIB AUTOFILL - SEMI-RANDOM MODE 🟠', 'color: orange; font-size: 16px; font-weight: bold;');
    console.log('%cScript ini akan mengisi form dengan pola SEMI-KONSISTEN (agak random)', 'color: orange; font-size: 12px;');
    console.log('');

    const inputs = document.querySelectorAll('input[name^="jawaban"]');

    if (inputs.length === 0) {
        console.error('❌ Tidak ada input form yang ditemukan!');
        return;
    }

    const kelompokMap = {};

    inputs.forEach(input => {
        const nameMatch = input.name.match(/jawaban\[([A-I])\]\[(.+)\]/);

        if (nameMatch) {
            const kelompok = nameMatch[1];
            const pekerjaan = nameMatch[2];

            if (!kelompokMap[kelompok]) {
                kelompokMap[kelompok] = [];
            }

            kelompokMap[kelompok].push({
                input: input,
                pekerjaan: pekerjaan
            });
        }
    });

    let totalFilled = 0;

    Object.keys(kelompokMap).sort().forEach(kelompok => {
        const jobs = kelompokMap[kelompok];

        // Strategy: Berikan peringkat dengan variasi tinggi
        // Kadang prioritaskan tinggi, kadang rendah (tidak konsisten antar kelompok)
        const isHighPriority = Math.random() > 0.5;

        // Generate rankings dengan pola semi-random
        let rankings = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];

        if (isHighPriority) {
            // Shuffle sebagian (hanya 50% konsisten)
            const halfLength = Math.floor(rankings.length / 2);
            const firstHalf = shuffleArray(rankings.slice(0, halfLength));
            const secondHalf = shuffleArray(rankings.slice(halfLength));
            rankings = [...firstHalf, ...secondHalf];
        } else {
            // Shuffle seluruhnya
            rankings = shuffleArray(rankings);
        }

        console.log(`🟠 Kelompok ${kelompok}: ${isHighPriority ? 'Semi-konsisten' : 'Random'}`);

        jobs.forEach((job, index) => {
            job.input.value = rankings[index];
            totalFilled++;
        });
    });

    console.log('');
    console.log(`✅ Selesai! Total ${totalFilled} pekerjaan telah diisi dengan pola SEMI-RANDOM`);
    console.log('');
    console.log('%c⚠️ Hasil kemungkinan akan memiliki skor konsistensi RENDAH hingga SEDANG (4-7)', 'color: orange; font-size: 12px;');
}

// ========================================
// VARIASI AUTOFILL - MODE CONTRADICTORY
// ========================================
/**
 * Fungsi alternatif: Memberikan peringkat yang BERTENTANGAN
 * (Setiap kelompok berkebalikan dengan kelompok sebelumnya)
 */
function autoFillRMIB_Contradictory() {
    console.log('%c🔴 RMIB AUTOFILL - CONTRADICTORY MODE 🔴', 'color: red; font-size: 16px; font-weight: bold;');
    console.log('%cScript ini akan mengisi form dengan pola BERTENTANGAN antar kelompok', 'color: red; font-size: 12px;');
    console.log('');

    const inputs = document.querySelectorAll('input[name^="jawaban"]');

    if (inputs.length === 0) {
        console.error('❌ Tidak ada input form yang ditemukan!');
        return;
    }

    const kelompokMap = {};

    inputs.forEach(input => {
        const nameMatch = input.name.match(/jawaban\[([A-I])\]\[(.+)\]/);

        if (nameMatch) {
            const kelompok = nameMatch[1];
            const pekerjaan = nameMatch[2];

            if (!kelompokMap[kelompok]) {
                kelompokMap[kelompok] = [];
            }

            kelompokMap[kelompok].push({
                input: input,
                pekerjaan: pekerjaan
            });
        }
    });

    let totalFilled = 0;
    let isReversed = false;

    Object.keys(kelompokMap).sort().forEach(kelompok => {
        const jobs = kelompokMap[kelompok];

        // Strategy: Alternating pattern (kelompok ganjil normal, genap terbalik)
        let rankings = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];

        if (isReversed) {
            rankings = rankings.reverse();
        }

        console.log(`🔴 Kelompok ${kelompok}: ${isReversed ? 'Terbalik (12→1)' : 'Normal (1→12)'}`);

        jobs.forEach((job, index) => {
            job.input.value = rankings[index];
            totalFilled++;
        });

        // Toggle untuk kelompok berikutnya
        isReversed = !isReversed;
    });

    console.log('');
    console.log(`✅ Selesai! Total ${totalFilled} pekerjaan telah diisi dengan pola BERTENTANGAN`);
    console.log('');
    console.log('%c⚠️ Hasil akan memiliki skor konsistensi SANGAT RENDAH', 'color: red; font-size: 12px;');
}

// ========================================
// INFO & BANTUAN
// ========================================
function showHelp() {
    console.log('%c📚 BANTUAN - RMIB AUTOFILL INVALID', 'color: blue; font-size: 16px; font-weight: bold;');
    console.log('');
    console.log('Tersedia 3 mode autofill untuk menghasilkan validitas rendah:');
    console.log('');
    console.log('%c1. autoFillRMIB_Invalid()', 'color: red; font-weight: bold;');
    console.log('   → Peringkat SEPENUHNYA ACAK di setiap kelompok');
    console.log('   → Menghasilkan skor konsistensi PALING RENDAH');
    console.log('   → Rekomendasi untuk testing "Tidak Valid"');
    console.log('');
    console.log('%c2. autoFillRMIB_SemiRandom()', 'color: orange; font-weight: bold;');
    console.log('   → Peringkat SEMI-KONSISTEN (50% random)');
    console.log('   → Menghasilkan skor konsistensi RENDAH hingga SEDANG (4-7)');
    console.log('   → Kadang valid, kadang tidak valid');
    console.log('');
    console.log('%c3. autoFillRMIB_Contradictory()', 'color: red; font-weight: bold;');
    console.log('   → Peringkat BERTENTANGAN antar kelompok (alternating)');
    console.log('   → Menghasilkan skor konsistensi SANGAT RENDAH');
    console.log('   → Pola: Kelompok A (1-12), B (12-1), C (1-12), dst.');
    console.log('');
    console.log('Contoh penggunaan:');
    console.log('  autoFillRMIB_Invalid()        // Mode paling acak');
    console.log('  autoFillRMIB_SemiRandom()     // Mode semi-acak');
    console.log('  autoFillRMIB_Contradictory()  // Mode bertentangan');
    console.log('');
    console.log('Untuk melihat bantuan lagi, ketik: showHelp()');
}

// ========================================
// AUTO-RUN INFO
// ========================================
console.log('');
console.log('%c╔════════════════════════════════════════════════════════════╗', 'color: red;');
console.log('%c║  RMIB AUTOFILL - INVALID MODE (Testing Purpose)          ║', 'color: red; font-weight: bold;');
console.log('%c╚════════════════════════════════════════════════════════════╝', 'color: red;');
console.log('');
console.log('%cScript berhasil dimuat! ✅', 'color: green; font-size: 14px; font-weight: bold;');
console.log('');
console.log('%c⚠️ PERINGATAN:', 'color: red; font-size: 14px; font-weight: bold;');
console.log('Script ini sengaja dirancang untuk menghasilkan hasil tes TIDAK VALID');
console.log('Gunakan HANYA untuk keperluan testing dan debugging sistem!');
console.log('');
console.log('%cUntuk memulai, pilih salah satu mode:', 'color: blue; font-size: 12px;');
console.log('');
console.log('  %c1. autoFillRMIB_Invalid()%c        - Paling acak (recommended)', 'color: red; font-weight: bold;', 'color: black;');
console.log('  %c2. autoFillRMIB_SemiRandom()%c     - Semi-acak', 'color: orange; font-weight: bold;', 'color: black;');
console.log('  %c3. autoFillRMIB_Contradictory()%c  - Bertentangan', 'color: red; font-weight: bold;', 'color: black;');
console.log('');
console.log('Ketik %cshowHelp()%c untuk melihat penjelasan lengkap.', 'color: blue; font-weight: bold;', 'color: black;');
console.log('');
