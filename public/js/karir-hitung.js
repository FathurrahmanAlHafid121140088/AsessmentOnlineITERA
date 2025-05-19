document.addEventListener("DOMContentLoaded", function () {
    const savedData = localStorage.getItem("rmibResults");
    const peringkat = savedData ? JSON.parse(savedData) : null;

    if (!peringkat) {
        alert(
            "Tidak ada data hasil tes yang tersimpan. Silakan lakukan tes terlebih dahulu."
        );
        window.location.href = "/karir-form";
        return;
    }

    processRMIBResults(peringkat);

    document
        .getElementById("print-button")
        .addEventListener("click", function () {
            window.print();
        });

    document
        .getElementById("back-button")
        .addEventListener("click", function () {
            window.location.href = "/karir-home";
        });
});

function processRMIBResults(peringkat) {
    // Urutan kategori sesuai tabel hasil
    const kategoriMinat = [
        "OUT",
        "ME",
        "COMP",
        "SCI",
        "PERS",
        "AESTH",
        "LIT",
        "MUS",
        "SS",
        "CLER",
        "PRAC",
        "MED",
    ];

    // Nama kategori untuk ditampilkan
    const kategoriDisplay = {
        OUT: "1. OUT",
        ME: "2. ME",
        COMP: "3. COMP",
        SCI: "4. SCI",
        PERS: "5. PERS",
        AESTH: "6. AESTH",
        LIT: "7. LIT",
        MUS: "8. MUS",
        SS: "9. S.S",
        CLER: "10. CLER",
        PRAC: "11. PRAC",
        MED: "12. MED",
    };

    // Definisi kategori awal untuk setiap kolom sesuai dengan tanda "x" pada tabel
    const kolom_ke_kategoriAwal = {
        A: "OUT", // Kolom A dimulai dari OUT
        B: "ME", // Kolom B dimulai dari ME
        C: "COMP", // Kolom C dimulai dari COMP
        D: "SCI", // Kolom D dimulai dari SCI
        E: "PERS", // Kolom E dimulai dari PERS
        F: "AESTH", // Kolom F dimulai dari AESTH
        G: "LIT", // Kolom G dimulai dari LIT
        H: "MUS", // Kolom H dimulai dari MUS
        I: "SS", // Kolom I dimulai dari SS
    };

    // Fungsi untuk melakukan rotasi array dari indeks tertentu
    function rotate(arr, start) {
        return arr.slice(start).concat(arr.slice(0, start));
    }

    // Fungsi untuk merotasi kategori sesuai dengan kolom
    function rotasiSesuaiKolom(kolom) {
        const indexAwal = kategoriMinat.indexOf(kolom_ke_kategoriAwal[kolom]);
        return rotate(kategoriMinat, indexAwal);
    }

    // Buat kategori yang dirotasi untuk setiap kolom
    const rotatedKategoriPerKolom = {
        A: rotasiSesuaiKolom("A"),
        B: rotasiSesuaiKolom("B"),
        C: rotasiSesuaiKolom("C"),
        D: rotasiSesuaiKolom("D"),
        E: rotasiSesuaiKolom("E"),
        F: rotasiSesuaiKolom("F"),
        G: rotasiSesuaiKolom("G"),
        H: rotasiSesuaiKolom("H"),
        I: rotasiSesuaiKolom("I"),
    };

    // Pemetaan kelompok ke kolom
    const groupMapping = {
        "Kelompok A": "A",
        "Kelompok B": "B",
        "Kelompok C": "C",
        "Kelompok D": "D",
        "Kelompok E": "E",
        "Kelompok F": "F",
        "Kelompok G": "G",
        "Kelompok H": "H",
        "Kelompok I": "I",
    };

    // Inisialisasi matriks hasil
    const resultMatrix = {};
    kategoriMinat.forEach(
        (id) =>
            (resultMatrix[id] = {
                A: null,
                B: null,
                C: null,
                D: null,
                E: null,
                F: null,
                G: null,
                H: null,
                I: null,
            })
    );

    // Proses data peringkat dari setiap kelompok
    Object.keys(peringkat).forEach((kelompok) => {
        const column = groupMapping[kelompok];
        const rotatedKategori = rotatedKategoriPerKolom[column];
        const jobRanks = Object.values(peringkat[kelompok]);

        rotatedKategori.forEach((kategori, i) => {
            resultMatrix[kategori][column] = jobRanks[i] ?? null;
        });
    });

    // Hitung skor total untuk setiap kategori
    const categoryScores = {};
    kategoriMinat.forEach((kategori) => {
        const values = Object.values(resultMatrix[kategori]).filter(
            (v) => v !== null
        );
        categoryScores[kategori] = values.reduce((a, b) => a + b, 0);
    });

    // Urutkan kategori berdasarkan skor (skor rendah = peringkat tinggi)
    const sorted = [...kategoriMinat].sort(
        (a, b) => categoryScores[a] - categoryScores[b]
    );
    const categoryRanks = {};
    sorted.forEach((kategori, i) => (categoryRanks[kategori] = i + 1));

    // Hitung persentase untuk setiap kategori
    const maxScore = 9 * 12; // 9 kolom × 12 nilai maksimal per kolom
    const percentages = {};
    kategoriMinat.forEach((k) => {
        percentages[k] = (
            ((maxScore - categoryScores[k]) / maxScore) *
            100
        ).toFixed(1);
    });

    // Tampilkan hasil di tabel
    const tbody = document
        .getElementById("rmib-result-table")
        .querySelector("tbody");
    tbody.innerHTML = "";

    kategoriMinat.forEach((kategori) => {
        const row = document.createElement("tr");
        const tdKategori = document.createElement("td");
        tdKategori.textContent = kategoriDisplay[kategori];
        row.appendChild(tdKategori);

        ["A", "B", "C", "D", "E", "F", "G", "H", "I"].forEach((col) => {
            const td = document.createElement("td");
            td.textContent = resultMatrix[kategori][col] ?? "";
            row.appendChild(td);
        });

        const tdSum = document.createElement("td");
        tdSum.textContent = categoryScores[kategori];
        row.appendChild(tdSum);

        const tdRank = document.createElement("td");
        tdRank.textContent = categoryRanks[kategori];
        tdRank.classList.add("rank-value");
        row.appendChild(tdRank);

        const tdPercent = document.createElement("td");
        tdPercent.textContent = percentages[kategori];
        row.appendChild(tdPercent);

        tbody.appendChild(row);
    });
}
