// Toggle (menampilkan/menyembunyikan) search box
function toggleSearchInput() {
    const searchBox = document.getElementById("searchBox");
    searchBox.classList.toggle("active");
}

// Optional: sembunyikan search box kalau klik di luar
document.addEventListener("click", (e) => {
    if (!searchBox.contains(e.target)) {
        searchBox.classList.remove("active");
    }
});

const hamburger = document.getElementById("hamburger");
const sidebar = document.getElementById("sidebar");

// Toggle sidebar ketika tombol hamburger diklik
hamburger.addEventListener("click", () => {
    sidebar.classList.toggle("active");
});

// Optional: klik di luar sidebar → sidebar menutup
document.addEventListener("click", (e) => {
    if (!sidebar.contains(e.target) && !hamburger.contains(e.target)) {
        sidebar.classList.remove("active");
    }
});

// Generate PDF berisi seluruh hasil assessment dalam tabel
async function generatePDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Judul di tengah
    doc.setFontSize(16);
    doc.text("Daftar Hasil Assessment Online Mental Health", 105, 20, {
        align: "center",
    });

    // Ambil data dari tabel HTML
    const table = document.getElementById("assessmentTable");
    const rows = table.querySelectorAll("tbody tr");

    const data = Array.from(rows).map((row) => {
        const cells = row.querySelectorAll("td");
        return Array.from(cells).map((cell) => cell.innerText.trim());
    });

    // Header tabel
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

    // Tambahkan tabel ke PDF
    doc.autoTable({
        head: headers,
        body: data,
        startY: 30,
        styles: { halign: "center" },
    });

    doc.save("hasil_assessment.pdf");
}

// Generate PDF hanya untuk satu baris (satu peserta) → dipanggil via tombol "Print"
function printPDF(button) {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Ambil baris data sesuai tombol
    var row = button.closest("tr");
    var cells = row.querySelectorAll("td");

    // Susun data
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

    // Buat tabel ke PDF
    doc.autoTable({
        head: [data[0]],
        body: [data[1]],
        theme: "grid",
        headStyles: { fillColor: [52, 58, 64] }, // warna header
    });

    // Nama file berdasarkan NIM
    doc.save("hasil-kuesioner-" + cells[0].innerText + ".pdf");
}

// Konfirmasi sebelum menghapus data
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

// Pencarian data via fetch (Ajax)
function toggleSearchInput() {
    const input = document.getElementById("searchInput");
    if (input.value.trim() !== "") {
        fetch(`/search-data?query=${input.value}`)
            .then((response) => response.json())
            .then((data) => {
                console.log("Hasil Pencarian:", data);
                // update tabel/HTML sesuai hasil pencarian
            });
    }
}

// Handle klik icon search di layar kecil (mobile)
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

// Buka modal
function openModal(id) {
    document.getElementById(id).classList.add("show");
}

// Tutup modal
function closeModal(id) {
    document.getElementById(id).classList.remove("show");
}

// Animasi angka score (counting effect)
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

            // Tambah efek animasi
            counter.classList.add("animated");
            setTimeout(() => {
                counter.classList.remove("animated");
            }, 300);
        }, 20);
    });
});

// Animasi naiknya tinggi bar chart (kategori)
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
        }, 700);
    });
});

// Animasi munculnya pie chart segment
window.addEventListener("load", function () {
    const segments = document.querySelectorAll(".pie-segment");
    segments.forEach((seg, idx) => {
        seg.style.transform = "rotate(0deg)";
        seg.style.opacity = 0;

        setTimeout(() => {
            seg.style.transform = `rotate(${seg.style.getPropertyValue(
                "--start"
            )})`;
            seg.style.opacity = 1;
        }, 500 + idx * 300);
    });
});

// Responsif: sesuaikan tinggi bar chart program studi saat resize
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

// Tutup modal bila klik backdrop (area luar konten modal)
function backdropClose(e, modalId) {
    if (e.target.classList.contains("custom-modal")) {
        closeModal(modalId);
    }
}

// Modul chart per program studi
(function chartProdiModule() {
    document.addEventListener("DOMContentLoaded", function () {
        const dropdown = document.getElementById("fakultasDropdown");
        dropdown.addEventListener("change", function () {
            tampilkanChart(this.value);
        });

        const initialFakultas = dropdown.value;
        tampilkanChart(initialFakultas);
    });

    // Animasi bar chart horizontal secara berurutan
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

    // Menampilkan chart sesuai fakultas yang dipilih
    function tampilkanChart(fakultas) {
        const allCharts = document.querySelectorAll(".horizontal-bar-chart");
        const container = document.getElementById("chartProdiContainer");

        allCharts.forEach((chart) => (chart.style.display = "none"));

        const targetChart = document.querySelector(".fakultas-" + fakultas);
        if (targetChart) {
            targetChart.style.display = "block";

            const jumlahProdi = parseInt(targetChart.dataset.jumlah) || 0;

            // Sesuaikan tinggi container chart
            if (window.innerWidth <= 768) {
                container.style.minHeight = "300px";
            } else {
                const chartHeight = jumlahProdi * 46 + 60;
                container.style.minHeight = chartHeight + "px";
            }

            // Hitung skala bar agar proporsional
            const bars = targetChart.querySelectorAll(".bar-fill-prodi");
            let maxValue = 1;
            bars.forEach((bar) => {
                const val = parseInt(bar.dataset.raw) || 0;
                if (val > maxValue) maxValue = val;
            });

            // Tentukan lebar maksimal bar sesuai layar
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

// Modul chart per provinsi
(function chartProvinsiModule() {
    document.addEventListener("DOMContentLoaded", function () {
        tampilkanChartProvinsi();
    });

    // Animasi bar chart horizontal provinsi
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

    // Menampilkan chart provinsi
    function tampilkanChartProvinsi() {
        const container = document.getElementById("chartProvinsiContainer");
        const targetChart = container.querySelector(
            ".horizontal-bar-chart-provinsi"
        );

        if (targetChart) {
            const jumlahProvinsi = parseInt(targetChart.dataset.jumlah) || 0;

            if (window.innerWidth <= 768) {
                container.style.minHeight = "300px";
            } else {
                const chartHeight = jumlahProvinsi * 46 + 60;
                container.style.minHeight = chartHeight + "px";
            }

            const bars = targetChart.querySelectorAll(".bar-fill-provinsi");

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

// Dropdown toggle menu (sidebar/nav)
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".dropdown-toggle").forEach(function (toggle) {
        toggle.addEventListener("click", function (e) {
            e.preventDefault();
            let parent = this.parentElement;
            parent.classList.toggle("open");
        });
    });
});

// Switch tampilan: chart prodi, chart provinsi, atau tabel
document.addEventListener("DOMContentLoaded", function () {
    const menuProdi = document.querySelector(
        'a[href="/admin/mental-health/program-studi"]'
    );
    const menuProvinsi = document.querySelector(
        'a[href="/admin/mental-health/provinsi"]'
    );

    const chartProdi = document.querySelector(".chart-prodi");
    const chartProvinsi = document.querySelector(".chart-provinsi");
    const tableWrapper = document.querySelector(".tables");

    function showOnly(target) {
        if (chartProdi) chartProdi.style.display = "none";
        if (chartProvinsi) chartProvinsi.style.display = "none";
        if (tableWrapper) tableWrapper.style.display = "none";

        if (target) target.style.display = "block";
    }

    if (menuProdi) {
        menuProdi.addEventListener("click", function (e) {
            e.preventDefault();
            showOnly(chartProdi);
        });
    }

    if (menuProvinsi) {
        menuProvinsi.addEventListener("click", function (e) {
            e.preventDefault();
            showOnly(chartProvinsi);
        });
    }
});

// Animasi donat chart (asal sekolah)
document.addEventListener("DOMContentLoaded", function () {
    const donut = document.querySelector("#donutAsalSekolah");
    if (!donut) return;

    const segs = donut.querySelectorAll(".donut-seg");

    // Terapkan dasharray & offset agar animasi stroke terlihat
    requestAnimationFrame(() => {
        segs.forEach((seg) => {
            const dash = parseFloat(seg.dataset.dash || "0");
            const gap = parseFloat(seg.dataset.gap || "0");
            const offset = parseFloat(seg.dataset.offset || "0");

            seg.style.strokeDasharray = `${dash} ${gap}`;
            seg.style.strokeDashoffset = offset * -1;
        });
    });
});

function resetFilters() {
    let url = new URL(window.location.href);
    url.search = ""; // hapus semua query string
    window.location.href = url.toString();
}
// Menjalankan skrip setelah seluruh konten halaman dimuat
document.addEventListener("DOMContentLoaded", function () {
    // 1. Ambil parameter URL saat ini
    const currentParams = new URLSearchParams(window.location.search);

    // 2. Cek apakah parameter 'limit' ada di URL
    if (currentParams.has("limit")) {
        // 3. Hapus parameter 'limit' dari objek parameter
        currentParams.delete("limit");

        // 4. Buat URL baru tanpa parameter 'limit'
        // window.location.pathname adalah bagian URL setelah domain (cth: /admin/mental-health)
        const newPath =
            window.location.pathname + "?" + currentParams.toString();

        // 5. Ganti URL di address bar tanpa me-reload halaman
        // Argumen pertama adalah state object (bisa null), kedua adalah title (kosongkan), ketiga adalah URL baru
        window.history.replaceState(null, "", newPath);
    }
});
