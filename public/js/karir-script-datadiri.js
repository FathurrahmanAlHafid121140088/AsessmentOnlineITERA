// ========================= FORM VALIDATION & SUBMIT =========================

// Event listener untuk tombol submit
document
    .getElementById("submit-button")
    .addEventListener("click", function (event) {
        event.preventDefault(); // cegah form terkirim langsung (default behavior)

        // Daftar field wajib diisi (input text, number, textarea, select)
        const requiredFields = [
            { id: "nama", label: "Nama" },
            { id: "alamat", label: "Alamat" },
            { id: "usia", label: "Usia" },
            { id: "program_studi", label: "Program Studi" },
            { id: "email", label: "Email" },
            { id: "asal_sekolah", label: "Asal Sekolah" },
            { id: "provinsi", label: "Provinsi" },
        ];

        let missingFields = [];

        // ✅ Cek apakah input text/number/textarea/select kosong
        requiredFields.forEach((field) => {
            const el = document.getElementById(field.id);
            if (!el || el.value.trim() === "" || el.value === null) {
                missingFields.push(field.label);
            }
        });

        // Daftar radio group wajib diisi
        const radioGroups = [
            { name: "jenis_kelamin", label: "Jenis Kelamin" },
            { name: "fakultas", label: "Fakultas" },
            { name: "prodi_sesuai_keinginan", label: "Prodi Sesuai Keinginan" },
            { name: "status_tinggal", label: "Status Tinggal" },
        ];

        // ✅ Cek apakah radio group sudah dipilih
        radioGroups.forEach((group) => {
            const selected = document.querySelector(
                `input[name="${group.name}"]:checked`
            );
            if (!selected) {
                missingFields.push(group.label);
            }
        });

        // Jika ada data yang belum diisi → tampilkan alert error
        if (missingFields.length > 0) {
            Swal.fire({
                icon: "error",
                title: "Data Belum Lengkap!",
                html: `<div style="text-align: left;">
                    <strong style="color: #dc2626;">⚠️ Mohon lengkapi field berikut:</strong><br><br>
                    <ul style="margin: 0; padding-left: 20px; line-height: 1.8;">
                        ${missingFields.map((field, index) =>
                            `<li style="color: #374151; font-weight: 500;">
                                <span style="color: #dc2626; font-weight: bold;">${index + 1}.</span> ${field}
                            </li>`
                        ).join('')}
                    </ul>
                    <br><p style="color: #6b7280; font-size: 0.9em; margin-top: 10px;">
                        💡 <em>Semua field bertanda <span style="color: red;">*</span> wajib diisi</em>
                    </p>
                </div>`,
                confirmButtonText: "OK, Saya Mengerti",
                confirmButtonColor: "#ea580c",
                width: "500px"
            });
            return;
        }

        // Jika semua sudah terisi → konfirmasi sebelum submit
        Swal.fire({
            title: "Apakah Anda yakin?",
            text: "Pastikan semua data diri Anda sudah diisi dengan benar.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, kirim!",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "Terkirim!",
                    text: "Data Diri Anda telah berhasil dikirim.",
                    icon: "success",
                    timer: 2000,
                    showConfirmButton: false,
                }).then(() => {
                    document.getElementById("form").submit();
                });
            }
        });
    });

// ========================= OPSI PROGRAM STUDI BERDASARKAN FAKULTAS =========================

// Data: daftar fakultas dan program studi terkait
const prodiOptions = {
    FS: [
        "Fisika",
        "Matematika",
        "Biologi",
        "Kimia",
        "Farmasi",
        "Sains Data",
        "Sains Aktuaria",
        "Sains Lingkungan Kelautan",
        "Sains Atmosfer dan Keplanetan",
        "Magister Fisika",
    ],
    FTIK: [
        "Perencanaan Wilayah dan Kota",
        "Teknik Geomatika",
        "Teknik Sipil",
        "Arsitektur",
        "Teknik Lingkungan",
        "Teknik Kelautan",
        "Desain Komunikasi Visual",
        "Arsitektur Lanskap",
        "Teknik Perkeretaapian",
        "Rekayasa Tata Kelola Air Terpadu",
        "Pariwisata",
    ],
    FTI: [
        "Teknik Elektro",
        "Teknik Fisika",
        "Teknik Informatika",
        "Teknik Geologi",
        "Teknik Geofisika",
        "Teknik Mesin",
        "Teknik Kimia",
        "Teknik Material",
        "Teknik Sistem Energi",
        "Teknik Industri",
        "Teknik Telekomunikasi",
        "Teknik Biomedis",
        "Teknik Biosistem",
        "Teknik Pertambangan",
        "Teknologi Industri Pertanian",
        "Teknologi Pangan",
        "Rekayasa Kehutanan",
        "Rekayasa Kosmetik",
        "Rekayasa Minyak dan Gas",
        "Rekayasa Instrumentasi dan Automasi",
        "Rekayasa Keolahragaan",
    ],
};

// Function: update daftar program studi sesuai fakultas yang dipilih
function updateProdi() {
    const selectedFakultas = document.querySelector(
        'input[name="fakultas"]:checked'
    );
    const prodiSelect = document.getElementById("program_studi");

    if (!selectedFakultas) return; // Jika belum ada fakultas dipilih → tidak update

    const fakultas = selectedFakultas.value;
    const prodis = prodiOptions[fakultas] || [];

    // Kosongkan opsi sebelumnya & tambahkan opsi default
    prodiSelect.innerHTML =
        '<option value="" disabled selected>Pilih program studi</option>';

    // Tambahkan opsi baru sesuai fakultas
    prodis.forEach((prodi) => {
        const option = document.createElement("option");
        option.value = prodi;
        option.textContent = prodi;
        prodiSelect.appendChild(option);
    });
}
