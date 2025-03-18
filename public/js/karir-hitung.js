document.addEventListener("DOMContentLoaded", function () {
    // Simulasi data dari local storage (pada implementasi sebenarnya, ini akan diambil dari hasil form)
    const savedData = localStorage.getItem("rmibResults");
    const peringkat = savedData ? JSON.parse(savedData) : null;

    if (!peringkat) {
        alert(
            "Tidak ada data hasil tes yang tersimpan. Silakan lakukan tes terlebih dahulu."
        );
        window.location.href = "karir-form.html";
        return;
    }

    processRMIBResults(peringkat);

    // Event listener untuk tombol
    document
        .getElementById("print-button")
        .addEventListener("click", function () {
            window.print();
        });

    document
        .getElementById("back-button")
        .addEventListener("click", function () {
            window.location.href = "karir-form.html";
        });
});

function processRMIBResults(peringkat) {
    // Definisi kategori minat RMIB
    const kategoriMinat = [
        { id: "OUT", name: "1. OUT", description: "Outdoor" },
        { id: "ME", name: "2. ME", description: "Mechanical" },
        { id: "COMP", name: "3. COMP", description: "Computational" },
        { id: "SCI", name: "4. SCI", description: "Scientific" },
        { id: "PERS", name: "5. PERS", description: "Personal Contact" },
        { id: "AESTH", name: "6. AESTH", description: "Aesthetic" },
        { id: "LIT", name: "7. LIT", description: "Literary" },
        { id: "MUS", name: "8. MUS", description: "Musical" },
        { id: "SS", name: "9. S.S", description: "Social Service" },
        { id: "CLER", name: "10. CLER", description: "Clerical" },
        { id: "PRAC", name: "11. PRAC", description: "Practical" },
        { id: "MED", name: "12. MED", description: "Medical" },
    ];

    // Mapping kolom grup ke kategori (berdasarkan Excel yang diberikan)
    const groupMapping = {
        "Kelompok A": { column: "A", categories: {} },
        "Kelompok B": { column: "B", categories: {} },
        "Kelompok C": { column: "C", categories: {} },
        "Kelompok D": { column: "D", categories: {} },
        "Kelompok E": { column: "E", categories: {} },
        "Kelompok F": { column: "F", categories: {} },
        "Kelompok G": { column: "G", categories: {} },
        "Kelompok H": { column: "H", categories: {} },
        "Kelompok I": { column: "I", categories: {} },
    };

    // Matriks untuk menyimpan nilai di setiap sel
    const resultMatrix = {};
    kategoriMinat.forEach((category) => {
        resultMatrix[category.id] = {};
        Object.keys(groupMapping).forEach((group) => {
            resultMatrix[category.id][groupMapping[group].column] = null;
        });
    });

    // Proses data peringkat untuk setiap kelompok
    Object.keys(peringkat).forEach((kelompok) => {
        const columnId = groupMapping[kelompok].column;
        const jobRankings = peringkat[kelompok];

        // Konversi data peringkat ke format yang dibutuhkan
        // (Mengaitkan jenis pekerjaan dengan kategori minat)
        // Ini adalah mapping yang perlu disesuaikan dengan data RMIB sebenarnya
        // Berikut adalah contoh untuk ilustrasi:
        const jobCategoryMapping = {
            // Kelompok A
            Petani: "OUT",
            "Insinyur Sipil": "ME",
            Akuntan: "COMP",
            Ilmuwan: "SCI",
            "Manager Penjualan": "PERS",
            Seniman: "AESTH",
            Wartawan: "LIT",
            "Pianis Konser": "MUS",
            "Guru SD": "SS",
            "Manager Bank": "CLER",
            "Tukang Kayu": "PRAC",
            Dokter: "MED",

            // Kelompok B
            "Ahhli Pembuat Alat": "OUT",
            "Ahli Statistik": "ME",
            "Insinyur Kimia Industri": "COMP",
            "Penyiar Radio": "SCI",
            "Artis Profesional": "PERS",
            Pengarang: "AESTH",
            "Dirigen Orkestra": "LIT",
            "Psikolog Pendidikan": "MUS",
            "Sekretaris Perusahaan": "SS",
            "Ahli Bangunan": "CLER",
            "Ahli Bedah": "PRAC",
            "Ahli Kehutanan": "MED",

            // Kelompok C sampai I perlu ditambahkan
            // ... Tambahkan semua pemetaan pekerjaan ke kategori minat untuk kelompok lainnya
        };

        // Memasukkan peringkat ke dalam matriks hasil
        Object.keys(jobRankings).forEach((job) => {
            const rank = jobRankings[job];
            if (rank !== null && jobCategoryMapping[job]) {
                const categoryId = jobCategoryMapping[job];
                resultMatrix[categoryId][columnId] = rank;
            }
        });
    });

    // Menghitung total skor untuk setiap kategori minat
    const categoryScores = {};
    kategoriMinat.forEach((category) => {
        const scores = Object.values(resultMatrix[category.id]).filter(
            (score) => score !== null
        );
        const sum = scores.reduce((total, score) => total + score, 0);
        categoryScores[category.id] = sum;
    });

    // Menentukan peringkat kategori (1 = skor terendah = paling diminati)
    const sortedCategories = [...kategoriMinat].sort((a, b) => {
        return categoryScores[a.id] - categoryScores[b.id];
    });

    const categoryRanks = {};
    sortedCategories.forEach((category, index) => {
        categoryRanks[category.id] = index + 1;
    });

    // Menghitung persentase (optional)
    const maxPossibleScore = 9 * 12; // 9 kelompok * peringkat maksimum 12
    const categoryPercentages = {};
    kategoriMinat.forEach((category) => {
        const score = categoryScores[category.id] || 0;
        const percentage = (
            ((maxPossibleScore - score) / maxPossibleScore) *
            100
        ).toFixed(1);
        categoryPercentages[category.id] = percentage;
    });

    // Populasi tabel hasil
    const table = document.getElementById("rmib-result-table");
    const tbody = table.querySelector("tbody");

    // Reset tabel
    tbody.innerHTML = "";

    // Populasi baris dalam tabel
    kategoriMinat.forEach((category) => {
        const row = document.createElement("tr");

        // Sel kategori
        const categoryCell = document.createElement("td");
        categoryCell.textContent = category.name;
        row.appendChild(categoryCell);

        // Sel untuk setiap kolom (A-I)
        Object.keys(groupMapping).forEach((group) => {
            const column = groupMapping[group].column;
            const cell = document.createElement("td");

            const value = resultMatrix[category.id][column];
            if (value !== null) {
                cell.textContent = value;
            }
            row.appendChild(cell);
        });

        // Sel untuk SUM
        const sumCell = document.createElement("td");
        sumCell.textContent = categoryScores[category.id] || "";
        row.appendChild(sumCell);

        // Sel untuk RANK
        const rankCell = document.createElement("td");
        rankCell.textContent = categoryRanks[category.id] || "";
        rankCell.classList.add("rank-value");
        row.appendChild(rankCell);

        // Sel untuk %
        const percentCell = document.createElement("td");
        percentCell.textContent = categoryPercentages[category.id] || "";
        row.appendChild(percentCell);

        tbody.appendChild(row);
    });
}
