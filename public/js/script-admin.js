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

function printPDF(button) {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Ambil data baris
    var row = button.closest("tr");
    var cells = row.querySelectorAll("td");

    // Ambil text dari setiap cell kecuali kolom aksi terakhir
    var data = [
        [
            "NIM",
            "Nama",
            "Program Studi",
            "Jenis Tes",
            "Kategori",
            "Tanggal Pengerjaan",
        ],
        [
            cells[0].innerText,
            cells[1].innerText,
            cells[2].innerText,
            cells[3].innerText,
            cells[4].innerText,
            cells[5].innerText,
        ],
    ];

    // Generate tabel ke PDF
    doc.autoTable({
        head: [data[0]],
        body: [data[1]],
        theme: "grid",
        headStyles: { fillColor: [52, 58, 64] }, // dark header
    });

    doc.save("hasil-kuesioner-" + cells[0].innerText + ".pdf"); // Nama file: hasil-kuesioner-NIM.pdf
}
function confirmDelete(id) {
    Swal.fire({
        title: "Yakin ingin menghapus data ini?",
        text: "Data yang dihapus tidak bisa dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Ya, Hapus!",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById("delete-form-" + id).submit();
        }
    });
}
function toggleSearchInput() {
    const input = document.getElementById("searchInput");
    if (input.value.trim() !== "") {
        fetch(`/search-data?query=${input.value}`)
            .then((response) => response.json())
            .then((data) => {
                console.log("Hasil Pencarian:", data);
                // update table/HTML here based on data
            });
    }
}
document.addEventListener("DOMContentLoaded", function () {
    const searchForm = document.querySelector(".search-form");
    const searchIcon = document.querySelector("#searchIcon");

    searchIcon.addEventListener("click", function (e) {
        if (
            window.innerWidth <= 768 &&
            !searchForm.classList.contains("active")
        ) {
            e.preventDefault(); // cegah submit form
            searchForm.classList.add("active");
            searchForm.querySelector("input").focus();
        }
    });
});
function openModal(id) {
    document.getElementById(id).classList.add("show");
}

function closeModal(id) {
    document.getElementById(id).classList.remove("show");
}
