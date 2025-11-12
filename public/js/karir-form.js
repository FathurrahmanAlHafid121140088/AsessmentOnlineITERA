document.querySelectorAll(".job-ranking-group").forEach((group) => {
    const inputs = group.querySelectorAll(".job-rank-input");
    group.closest("form").addEventListener("submit", function (event) {
        const values = new Set();
        let hasDuplicate = false;
        let isInvalidRange = false;
        inputs.forEach((input) => {
            const value = parseInt(input.value);
            if (isNaN(value) || value < 1 || value > 12) {
                isInvalidRange = true;
            }
            if (values.has(value)) {
                hasDuplicate = true;
            }
            values.add(value);
        });

        if (isInvalidRange) {
            event.preventDefault();
            alert(
                `Error di ${
                    group.querySelector("h4").textContent
                }: Harap isi semua pekerjaan dengan angka 1-12.`
            );
        } else if (hasDuplicate) {
            event.preventDefault();
            alert(
                `Error di ${
                    group.querySelector("h4").textContent
                }: Setiap angka 1-12 hanya boleh digunakan satu kali.`
            );
        }
    });
});

// Inisialisasi tooltip Bootstrap (diperlukan sekali saja)
const tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
);
const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});

// ========================================
// FILTER DROPDOWN TOP 3 BERDASARKAN RANK 1
// ========================================

/**
 * Update dropdown top 1/2/3 agar hanya menampilkan pekerjaan yang diberi rank 1
 */
function updateTop3Dropdowns() {
    // Array untuk menyimpan pekerjaan dengan rank 1
    const rank1Jobs = [];

    // Loop semua kelompok (A-I)
    document.querySelectorAll(".job-ranking-group").forEach((group) => {
        const kelompokTitle = group.querySelector("h4").textContent.trim(); // "Kelompok A", "Kelompok B", etc.
        const inputs = group.querySelectorAll(".job-rank-input");

        // Cari pekerjaan yang diberi rank 1 di kelompok ini
        inputs.forEach((input) => {
            if (input.value === "1") {
                const label = group.querySelector(`label[for="${input.id}"]`);
                if (label) {
                    const jobName = label.textContent.trim();
                    rank1Jobs.push({
                        kelompok: kelompokTitle,
                        nama: jobName,
                    });
                }
            }
        });
    });

    // Update ketiga dropdown (top1, top2, top3)
    ["top1", "top2", "top3"].forEach((dropdownId) => {
        const dropdown = document.getElementById(dropdownId);
        if (!dropdown) return;

        // Simpan nilai yang sedang dipilih
        const currentValue = dropdown.value;

        // Hapus semua option kecuali placeholder
        dropdown.innerHTML =
            '<option value="" disabled selected>-- Pilih Pekerjaan --</option>';

        // Kelompokkan pekerjaan rank 1 berdasarkan kelompok
        const groupedJobs = {};
        rank1Jobs.forEach((job) => {
            if (!groupedJobs[job.kelompok]) {
                groupedJobs[job.kelompok] = [];
            }
            groupedJobs[job.kelompok].push(job.nama);
        });

        // Tambahkan optgroup dan option untuk setiap kelompok
        Object.keys(groupedJobs)
            .sort()
            .forEach((kelompok) => {
                const optgroup = document.createElement("optgroup");
                optgroup.label = kelompok;

                groupedJobs[kelompok].forEach((jobName) => {
                    const option = document.createElement("option");
                    option.value = jobName;
                    option.textContent = jobName;
                    optgroup.appendChild(option);
                });

                dropdown.appendChild(optgroup);
            });

        // Restore nilai yang dipilih jika masih ada dalam list rank 1
        if (
            currentValue &&
            rank1Jobs.some((job) => job.nama === currentValue)
        ) {
            dropdown.value = currentValue;
        }
    });

    // Tampilkan pesan jika belum ada pekerjaan dengan rank 1
    if (rank1Jobs.length === 0) {
        ["top1", "top2", "top3"].forEach((dropdownId) => {
            const dropdown = document.getElementById(dropdownId);
            if (dropdown && dropdown.options.length === 1) {
                // Hanya ada placeholder
                dropdown.innerHTML =
                    '<option value="" disabled selected>-- Berikan rank 1 pada pekerjaan favorit Anda di atas --</option>';
            }
        });
    }
}

// Validasi Angka Unik per Kelompok (saat input berubah & kehilangan fokus)
document.querySelectorAll(".job-ranking-group").forEach((group) => {
    const inputs = group.querySelectorAll(".job-rank-input");

    inputs.forEach((input) => {
        // Hapus tooltip saat input mendapatkan fokus lagi
        input.addEventListener("focus", function () {
            const tooltip = bootstrap.Tooltip.getInstance(this);
            if (tooltip) {
                tooltip.dispose();
            }
            this.removeAttribute("data-bs-toggle");
            this.removeAttribute("data-bs-placement");
            this.removeAttribute("data-bs-original-title");
            this.removeAttribute("title");
            this.classList.remove("is-invalid"); // Hapus border merah
        });

        // *** PERUBAHAN UTAMA: Gunakan event 'change' bukan 'input' ***
        input.addEventListener("change", function () {
            const currentValue = this.value;
            const tooltipInstance = bootstrap.Tooltip.getInstance(this);

            // Hapus atribut tooltip lama (jika masih ada)
            this.removeAttribute("data-bs-toggle");
            this.removeAttribute("data-bs-placement");
            this.removeAttribute("data-bs-original-title");
            this.removeAttribute("title");
            this.classList.remove("is-invalid"); // Hapus border merah

            // Jangan validasi jika input kosong
            if (currentValue === "") {
                // Update dropdown meskipun input dikosongkan
                updateTop3Dropdowns();
                return;
            }

            // Batasi nilai antara 1 dan 12 dulu
            const numericValue = parseInt(currentValue);
            if (isNaN(numericValue) || numericValue < 1 || numericValue > 12) {
                this.setAttribute("data-bs-toggle", "tooltip");
                this.setAttribute("data-bs-placement", "top");
                this.setAttribute("title", "Masukkan angka 1-12!");
                const newTooltip = new bootstrap.Tooltip(this);
                newTooltip.show();
                this.classList.add("is-invalid");
                this.value = ""; // Kosongkan jika invalid
                // Update dropdown setelah input dikosongkan
                updateTop3Dropdowns();
                return; // Hentikan validasi duplikat jika range salah
            }

            // Periksa duplikat HANYA setelah range valid
            let duplicateFound = false;
            inputs.forEach((otherInput) => {
                if (otherInput !== this && otherInput.value === currentValue) {
                    duplicateFound = true;
                }
            });

            if (duplicateFound) {
                // Tampilkan tooltip error duplikat
                this.setAttribute("data-bs-toggle", "tooltip");
                this.setAttribute("data-bs-placement", "top");
                this.setAttribute(
                    "title",
                    `Angka ${currentValue} sudah dipakai!`
                );
                const newTooltip = new bootstrap.Tooltip(this);
                newTooltip.show();
                this.classList.add("is-invalid"); // Tambah border merah
                this.value = ""; // Kosongkan input duplikat
                // Update dropdown setelah input dikosongkan
                updateTop3Dropdowns();
            } else {
                // Jika tidak duplikat dan range valid, update dropdown
                updateTop3Dropdowns();
            }
        });
    });
});

// Panggil updateTop3Dropdowns saat halaman pertama kali dimuat
// untuk handle jika ada nilai default atau setelah validasi error
document.addEventListener("DOMContentLoaded", function () {
    updateTop3Dropdowns();
});
