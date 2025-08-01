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

window.addEventListener("load", function () {
    const counters = document.querySelectorAll(".score-value");

    counters.forEach((counter) => {
        const finalValue = parseInt(counter.textContent) || 0;
        let currentValue = 0;
        const increment = finalValue / 100;

        const timer = setInterval(() => {
            currentValue += increment;
            if (currentValue >= finalValue) {
                currentValue = finalValue;
                clearInterval(timer);
            }
            counter.textContent = Math.floor(currentValue);

            // tambahkan efek animasi
            counter.classList.add("animated");
            setTimeout(() => {
                counter.classList.remove("animated");
            }, 300); // match durasi .3s
        }, 20);
    });
});

window.addEventListener("load", function () {
    const bars = document.querySelectorAll(".bar-fill");
    const barValues = document.querySelectorAll(".bar-value");

    bars.forEach((bar, idx) => {
        const targetHeight = bar.style.height;
        bar.style.height = "0px";

        setTimeout(() => {
            bar.style.height = targetHeight;
            if (barValues[idx]) {
                barValues[idx].style.opacity = 1;
            }
        }, 700); // lebih lambat muncul
    });
});

window.addEventListener("load", function () {
    const segments = document.querySelectorAll(".pie-segment");
    segments.forEach((seg, idx) => {
        // reset transform dulu
        seg.style.transform = "rotate(0deg)";
        // reset opacity
        seg.style.opacity = 0;

        setTimeout(() => {
            seg.style.transform = `rotate(${seg.style.getPropertyValue(
                "--start"
            )})`;
            seg.style.opacity = 1;
        }, 500 + idx * 300); // muncul satu per satu
    });
});
window.addEventListener("resize", function () {
    const bars = document.querySelectorAll(".bar-fill-prodi");
    bars.forEach((bar) => {
        const origHeight = bar.dataset.height;
        if (window.innerWidth <= 768) {
            bar.style.height = Math.min(origHeight, 100) + "px";
        } else {
            bar.style.height = origHeight + "px";
        }
    });
});
function backdropClose(e, modalId) {
    // close hanya bila yang diklik persis backdrop, BUKAN kontennya
    if (e.target.classList.contains("custom-modal")) {
        closeModal(modalId);
    }
}

(function chartProdiModule() {
    document.addEventListener("DOMContentLoaded", function () {
        const dropdown = document.getElementById("fakultasDropdown");
        dropdown.addEventListener("change", function () {
            tampilkanChart(this.value);
        });

        const initialFakultas = dropdown.value;
        tampilkanChart(initialFakultas);
    });

    function animateBarsSequentially(bars, scaleFactor) {
        bars.forEach((bar, idx) => {
            const rawValue = parseInt(bar.dataset.raw) || 0;
            const targetWidth = rawValue * scaleFactor;
            bar.style.width = "0px";
            bar.style.opacity = 0;
            setTimeout(() => {
                bar.style.transition = "width 0.6s ease, opacity 0.6s ease";
                bar.style.width = targetWidth + "px";
                bar.style.opacity = 1;
            }, idx * 100);
        });
    }

    function tampilkanChart(fakultas) {
        const allCharts = document.querySelectorAll(".horizontal-bar-chart");
        const container = document.getElementById("chartProdiContainer");

        allCharts.forEach((chart) => (chart.style.display = "none"));

        const targetChart = document.querySelector(".fakultas-" + fakultas);
        if (targetChart) {
            targetChart.style.display = "block";

            const jumlahProdi = parseInt(targetChart.dataset.jumlah) || 0;

            // Min-height: 500px for mobile, dynamic for desktop
            if (window.innerWidth <= 768) {
                container.style.minHeight = "300px";
            } else {
                const chartHeight = jumlahProdi * 46 + 60;
                container.style.minHeight = chartHeight + "px";
            }

            const bars = targetChart.querySelectorAll(".bar-fill-prodi");
            let maxValue = 1;
            bars.forEach((bar) => {
                const val = parseInt(bar.dataset.raw) || 0;
                if (val > maxValue) maxValue = val;
            });

            let maxBarWidth = 740;
            if (window.innerWidth <= 400) maxBarWidth = 190;
            else if (window.innerWidth <= 480) maxBarWidth = 240;
            else if (window.innerWidth <= 768) maxBarWidth = 280;
            else if (window.innerWidth <= 992) maxBarWidth = 345;
            else if (window.innerWidth <= 1024) maxBarWidth = 245;
            else if (window.innerWidth <= 1280) maxBarWidth = 500;

            const scaleFactor = maxBarWidth / maxValue;
            animateBarsSequentially(bars, scaleFactor);
        }
    }
})();
