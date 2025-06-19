function toggleSearchInput() {
    const searchBox = document.getElementById("searchBox");
    searchBox.classList.toggle("active");
}

// Optional: Hide search when clicking outside
document.addEventListener("click", (e) => {
    if (!searchBox.contains(e.target)) {
        searchBox.classList.remove("active");
    }
});

const hamburger = document.getElementById("hamburger");
const sidebar = document.getElementById("sidebar");

hamburger.addEventListener("click", () => {
    sidebar.classList.toggle("active");
});

// Optional: klik di luar sidebar untuk nutup
document.addEventListener("click", (e) => {
    if (!sidebar.contains(e.target) && !hamburger.contains(e.target)) {
        sidebar.classList.remove("active");
    }
});

async function generatePDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Judul di tengah
    doc.setFontSize(16);
    doc.text("Daftar Hasil Assessment Online Mental Health", 105, 20, {
        align: "center",
    });

    // Ambil data tabel
    const table = document.getElementById("assessmentTable");
    const rows = table.querySelectorAll("tbody tr");

    const data = Array.from(rows).map((row) => {
        const cells = row.querySelectorAll("td");
        return Array.from(cells).map((cell) => cell.innerText.trim());
    });

    const headers = [
        [
            "NIM",
            "Nama",
            "Program Studi",
            "Jenis Tes",
            "Kategori",
            "Tanggal Pengerjaan",
        ],
    ];

    // Tambahkan tabel ke dokumen
    doc.autoTable({
        head: headers,
        body: data,
        startY: 30,
        styles: { halign: "center" },
    });

    doc.save("hasil_assessment.pdf");
}
