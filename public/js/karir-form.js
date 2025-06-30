// Data kategori dan pekerjaan
const kategoriRMIB = [
    {
        name: "Kelompok A",
        jobs: [
            "Petani",
            "Insinyur Sipil",
            "Akuntan",
            "Ilmuwan",
            "Manager Penjualan",
            "Seniman",
            "Wartawan",
            "Pianis Konser",
            "Guru SD",
            "Manager Bank",
            "Tukang Kayu",
            "Dokter",
        ],
    },
    {
        name: "Kelompok B",
        jobs: [
            "Ahhli Pembuat Alat",
            "Ahli Statistik",
            "Insinyur Kimia Industri",
            "Penyiar Radio",
            "Artis Profesional",
            "Pengarang",
            "Dirigen Orkestra",
            "Psikolog Pendidikan",
            "Sekretaris Perusahaan",
            "Ahli Bangunan",
            "Ahli Bedah",
            "Ahli Kehutanan",
        ],
    },
    {
        name: "Kelompok C",
        jobs: [
            "Auditor",
            "Ahli Meteorologi",
            "Salesman",
            "Arsitek",
            "Penulis Drama",
            "Komponis",
            "Kepala Sekolah",
            "Pegawai Kotapraja (PNS)",
            "Ahli Meubel/ Furniture",
            "Dokter Hewan",
            "Juru Ukur Tanah",
            "Tukang Bubut/ Lemer",
        ],
    },
    {
        name: "Kelompok D",
        jobs: [
            "Ahli Biologi",
            "Agen Biro Periklanan",
            "Dekorator Interior",
            "Ahli Sejarah",
            "Kritikus Musik",
            "Pekerja Sosial",
            "Pegawai Asuransi",
            "Tukang Cat",
            "Apoteker",
            "Penjelajah",
            "Tukang Listrik",
            "Penilai Pajak Pendapatan",
        ],
    },
    {
        name: "Kelompok E",
        jobs: [
            "Petugas Wawancara",
            "Perancang Perhiasan",
            "Ahli Perpustakaan",
            "Guru Musik",
            "Pembina Rohani",
            "Petugas Arsip",
            "Tukang Batu",
            "Dokter Gigi",
            "Prospektor (🔍)",
            "Montir",
            "Guru Ilmu Pasti",
            "Ahli Pertanian",
        ],
    },
    {
        name: "Kelompok F",
        jobs: [
            "Fotografer",
            "Penulis Majalah",
            "Pemain Orgen Tunggal (🔍)",
            "Organisasi Pramuka",
            "Petugas Pengiriman Barang",
            "Petugas Mesin Perkayuan",
            "Ahli Kacamata",
            "Ahli Sortir Kulit",
            "Instalator",
            "Asisten Kasir Bank",
            "Ahli Botani",
            "Pedagang Keliling",
        ],
    },
    {
        name: "Kelompok G",
        jobs: [
            "Kritikus Buku",
            "Ahli Pustaka Musik",
            "Pengurus Karang Taruna (🔍)",
            "Pegawai Kantor",
            "Tukang Plester Tembok",
            "Ahli Rontgent",
            "Nelayan",
            "Pembuat Arloji",
            "Kasir",
            "Astronomi",
            "Juru Lelang",
            "Penata Panggung",
        ],
    },
    {
        name: "Kelompok H",
        jobs: [
            "Pemain Musik Band",
            "Ahli Penyuluh Jabatan",
            "Pegawai Pos",
            "Tukang Ledeng/ Pipa Air",
            "Ahli Fisioterapi",
            "Sopir Angkutan Umum",
            "Montir Radio",
            "Juru Bayar",
            "Ahli Geologi",
            "Petugas Hubungan Masyarakat",
            "Penata Etalase",
            "Penulis Sandiawara Radio",
        ],
    },
    {
        name: "Kelompok I",
        jobs: [
            "Petugas Kesejahteraan Sosial",
            "Petugas Ekspedisi Surat",
            "Tukang Sepatu",
            "Paramedik/Mantri Kesehatan",
            "Petani Tanaman Hias",
            "Tukang Las",
            "Petugas Pajak",
            "Asisten Laboratorium",
            "Salesman Asuransi",
            "Perancang Motif Tekstil",
            "Penyair",
            "Pramuniaga Toko Musik",
        ],
    },
];

// Objek untuk menyimpan peringkat
const peringkat = {};

// Inisialisasi peringkat
kategoriRMIB.forEach((kategori) => {
    peringkat[kategori.name] = {};
    kategori.jobs.forEach((job) => {
        peringkat[kategori.name][job] = null;
    });
});

// Fungsi untuk mengecek peringkat unik
function cekPeringkatUnik(namaKategori) {
    const peringkatKategori = Object.values(peringkat[namaKategori]);
    const peringkatValid = peringkatKategori.filter(
        (r) => r !== null && r !== "" && !isNaN(r) && r >= 1 && r <= 12
    );
    const peringkatUnik = new Set(peringkatValid);
    return peringkatUnik.size === peringkatValid.length;
}

// Fungsi untuk mengecek semua peringkat terisi
function cekSemuaPeringkatTerisi(namaKategori) {
    const peringkatKategori = Object.values(peringkat[namaKategori]);
    return !peringkatKategori.some((r) => r === null || r === "" || isNaN(r));
}

// Fungsi untuk membangun UI
function buildUI() {
    const categoriesContainer = document.getElementById("categories-container");

    kategoriRMIB.forEach((kategori) => {
        // Buat container untuk kategori
        const categoryDiv = document.createElement("div");
        categoryDiv.className = "category-container";
        categoryDiv.dataset.category = kategori.name;

        // Buat judul kategori
        const categoryTitle = document.createElement("h3");
        categoryTitle.className = "category-title";
        categoryTitle.textContent = kategori.name;
        categoryDiv.appendChild(categoryTitle);

        // Buat container untuk tabel
        const tableContainer = document.createElement("div");
        tableContainer.className = "table-container";

        // Buat tabel
        const table = document.createElement("table");

        // Buat header tabel
        const thead = document.createElement("thead");
        const headerRow = document.createElement("tr");

        const jobHeader = document.createElement("th");
        jobHeader.textContent = "Pekerjaan";

        const rankHeader = document.createElement("th");
        rankHeader.textContent = "Peringkat (1-12)";

        headerRow.appendChild(jobHeader);
        headerRow.appendChild(rankHeader);
        thead.appendChild(headerRow);
        table.appendChild(thead);

        // Buat body tabel
        const tbody = document.createElement("tbody");

        kategori.jobs.forEach((job) => {
            const row = document.createElement("tr");

            const jobCell = document.createElement("td");
            jobCell.textContent = job;

            const rankCell = document.createElement("td");
            const input = document.createElement("input");
            input.type = "number";
            input.min = "1";
            input.max = "12";
            input.placeholder = "1-12";
            input.value = peringkat[kategori.name][job] || "";

            input.addEventListener("input", (e) => {
                const value = e.target.value ? parseInt(e.target.value) : null;

                // Validasi nilai input
                if (value !== null && (value < 1 || value > 12)) {
                    input.classList.add("invalid");
                } else {
                    input.classList.remove("invalid");
                }

                peringkat[kategori.name][job] = value;

                // Tampilkan/sembunyikan pesan error duplikat
                const errorMessage =
                    categoryDiv.querySelector(".error-message");
                if (!cekPeringkatUnik(kategori.name)) {
                    errorMessage.classList.add("visible");
                } else {
                    errorMessage.classList.remove("visible");
                }

                // Tampilkan/sembunyikan pesan error unfilled
                const unfillMessage =
                    categoryDiv.querySelector(".unfill-message");
                if (!cekSemuaPeringkatTerisi(kategori.name)) {
                    unfillMessage.classList.add("visible");
                } else {
                    unfillMessage.classList.remove("visible");
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

        // Buat pesan error untuk duplikat
        const errorMessage = document.createElement("p");
        errorMessage.className = "error-message";
        errorMessage.textContent =
            "Pastikan setiap pekerjaan memiliki peringkat unik (1-12)";
        categoryDiv.appendChild(errorMessage);

        // Buat pesan error untuk unfilled
        const unfillMessage = document.createElement("p");
        unfillMessage.className = "error-message unfill-message";
        unfillMessage.textContent =
            "Pastikan semua pekerjaan memiliki peringkat";
        categoryDiv.appendChild(unfillMessage);

        categoriesContainer.appendChild(categoryDiv);
    });
}

// Fungsi untuk memvalidasi semua kategori
function validateAllCategories() {
    let valid = true;
    let errorDetails = {
        duplicates: [],
        unfilled: [],
    };

    kategoriRMIB.forEach((kategori) => {
        // Cek peringkat duplikat
        if (!cekPeringkatUnik(kategori.name)) {
            valid = false;
            errorDetails.duplicates.push(kategori.name);

            // Tandai kategori dengan error
            const categoryDiv = document.querySelector(
                `.category-container[data-category="${kategori.name}"]`
            );
            if (categoryDiv) {
                const errorMessage =
                    categoryDiv.querySelector(".error-message");
                if (errorMessage) {
                    errorMessage.classList.add("visible");
                }
            }
        }

        // Cek apakah ada peringkat yang belum terisi
        if (!cekSemuaPeringkatTerisi(kategori.name)) {
            valid = false;
            errorDetails.unfilled.push(kategori.name);

            // Tandai kategori dengan error unfilled
            const categoryDiv = document.querySelector(
                `.category-container[data-category="${kategori.name}"]`
            );
            if (categoryDiv) {
                const unfillMessage =
                    categoryDiv.querySelector(".unfill-message");
                if (unfillMessage) {
                    unfillMessage.classList.add("visible");
                }
            }
        }
    });

    return { valid, errorDetails };
}

// Buat komponen modal error
function createErrorModal() {
    // Buat elemen modal jika belum ada
    if (!document.getElementById("error-modal-overlay")) {
        // Buat overlay
        const modalOverlay = document.createElement("div");
        modalOverlay.id = "error-modal-overlay";
        modalOverlay.className = "modal-overlay";

        // Buat modal
        const errorModal = document.createElement("div");
        errorModal.className = "error-modal";

        // Header modal
        const modalHeader = document.createElement("div");
        modalHeader.className = "error-modal-header";

        const errorIcon = document.createElement("span");
        errorIcon.className = "error-icon";
        errorIcon.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-1.72 6.97a.75.75 0 10-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 101.06 1.06L12 13.06l1.72 1.72a.75.75 0 101.06-1.06L13.06 12l1.72-1.72a.75.75 0 10-1.06-1.06L12 10.94l-1.72-1.72z" clip-rule="evenodd" />
            </svg>
        `;

        const modalTitle = document.createElement("h3");
        modalTitle.className = "error-modal-title";
        modalTitle.textContent = "Mohon Periksa Kembali";

        modalHeader.appendChild(errorIcon);
        modalHeader.appendChild(modalTitle);

        // Body modal
        const modalBody = document.createElement("div");
        modalBody.className = "error-modal-body";

        const modalBodyText = document.createElement("p");
        modalBodyText.textContent =
            "Terdapat beberapa masalah yang perlu diperbaiki:";

        const errorList = document.createElement("div");
        errorList.className = "error-list";
        errorList.id = "error-list";

        modalBody.appendChild(modalBodyText);
        modalBody.appendChild(errorList);

        // Footer modal
        const modalFooter = document.createElement("div");
        modalFooter.className = "error-modal-footer";

        const closeButton = document.createElement("button");
        closeButton.className = "error-close-button";
        closeButton.textContent = "Kembali";
        closeButton.addEventListener("click", closeErrorModal);

        modalFooter.appendChild(closeButton);

        // Susun struktur modal
        errorModal.appendChild(modalHeader);
        errorModal.appendChild(modalBody);
        errorModal.appendChild(modalFooter);

        modalOverlay.appendChild(errorModal);
        document.body.appendChild(modalOverlay);

        // Tutup modal ketika klik di luar modal
        modalOverlay.addEventListener("click", function (e) {
            if (e.target === modalOverlay) {
                closeErrorModal();
            }
        });
    }
}

// Tampilkan modal error
function showErrorModal(errorDetails) {
    createErrorModal();

    const errorList = document.getElementById("error-list");
    errorList.innerHTML = "";

    // Tambahkan error unfilled jika ada
    if (errorDetails.unfilled.length > 0) {
        const unfilledCategory = document.createElement("div");
        unfilledCategory.className = "error-category";

        const unfilledTitle = document.createElement("div");
        unfilledTitle.className = "error-category-title";
        unfilledTitle.textContent =
            "Beberapa pekerjaan belum diberi peringkat pada:";

        const unfilledList = document.createElement("ul");
        unfilledList.className = "error-items";

        errorDetails.unfilled.forEach((kategori) => {
            const item = document.createElement("li");
            item.textContent = kategori;
            item.addEventListener("click", () => {
                const categoryElement = document.querySelector(
                    `.category-container[data-category="${kategori}"]`
                );
                if (categoryElement) {
                    closeErrorModal();
                    setTimeout(() => {
                        categoryElement.scrollIntoView({
                            behavior: "smooth",
                            block: "center",
                        });
                    }, 300);
                }
            });
            item.style.cursor = "pointer";
            unfilledList.appendChild(item);
        });

        unfilledCategory.appendChild(unfilledTitle);
        unfilledCategory.appendChild(unfilledList);
        errorList.appendChild(unfilledCategory);
    }

    // Tambahkan error duplikat jika ada
    if (errorDetails.duplicates.length > 0) {
        const duplicateCategory = document.createElement("div");
        duplicateCategory.className = "error-category";

        const duplicateTitle = document.createElement("div");
        duplicateTitle.className = "error-category-title";
        duplicateTitle.textContent = "Terdapat peringkat duplikat pada:";

        const duplicateList = document.createElement("ul");
        duplicateList.className = "error-items";

        errorDetails.duplicates.forEach((kategori) => {
            const item = document.createElement("li");
            item.textContent = kategori;
            item.addEventListener("click", () => {
                const categoryElement = document.querySelector(
                    `.category-container[data-category="${kategori}"]`
                );
                if (categoryElement) {
                    closeErrorModal();
                    setTimeout(() => {
                        categoryElement.scrollIntoView({
                            behavior: "smooth",
                            block: "center",
                        });
                    }, 300);
                }
            });
            item.style.cursor = "pointer";
            duplicateList.appendChild(item);
        });

        duplicateCategory.appendChild(duplicateTitle);
        duplicateCategory.appendChild(duplicateList);
        errorList.appendChild(duplicateCategory);
    }

    // Tampilkan modal
    const modalOverlay = document.getElementById("error-modal-overlay");
    modalOverlay.classList.add("visible");

    // Nonaktifkan scroll pada body
    document.body.style.overflow = "hidden";
}

// Tutup modal error
function closeErrorModal() {
    const modalOverlay = document.getElementById("error-modal-overlay");
    modalOverlay.classList.remove("visible");

    // Aktifkan kembali scroll pada body
    document.body.style.overflow = "";
}

// Inisialisasi UI
document.addEventListener("DOMContentLoaded", function () {
    buildUI();

    // Event listener untuk tombol submit
    document.getElementById("submit-button").addEventListener("click", () => {
        // Validasi semua kategori
        const validation = validateAllCategories();

        if (validation.valid) {
            // Simpan data ke localStorage
            localStorage.setItem("rmibResults", JSON.stringify(peringkat));

            // Redirect ke halaman hasil
            alert("Terima kasih! Hasil tes Anda telah diproses.");
            window.location.href = "/karir-interpretasi";
        } else {
            // Tampilkan modal error
            showErrorModal(validation.errorDetails);
        }
    });
});
