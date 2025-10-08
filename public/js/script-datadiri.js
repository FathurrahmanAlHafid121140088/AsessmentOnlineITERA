// ========================= FORM VALIDATION & SUBMIT =========================

document.addEventListener("DOMContentLoaded", () => {
    const submitButton = document.getElementById("submit-button");
    if (submitButton) {
        submitButton.addEventListener("click", function (event) {
            event.preventDefault(); // Mencegah form terkirim langsung

            // Daftar field wajib diisi
            const requiredFields = [
                { id: "nama", label: "Nama" },
                { id: "alamat", label: "Alamat" },
                { id: "usia", label: "Usia" },
                { id: "program_studi", label: "Program Studi" },
                { id: "email", label: "Email" },
                { id: "keluhan", label: "Keluhan" },
                // NIM tidak perlu divalidasi di sini karena diisi otomatis dan disabled
            ];

            let missingFields = [];

            // Cek input text/number/textarea/select
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
                { name: "status_tinggal", label: "Status Tinggal" },
                { name: "lama_keluhan", label: "Lama Keluhan" },
                { name: "pernah_tes", label: "Pernah Tes Psikologi" },
                { name: "pernah_konsul", label: "Pernah Konsultasi" },
            ];

            // Cek radio group
            radioGroups.forEach((group) => {
                const selected = document.querySelector(
                    `input[name="${group.name}"]:checked`
                );
                if (!selected) {
                    missingFields.push(group.label);
                }
            });

            // Jika ada yang kosong, tampilkan error
            if (missingFields.length > 0) {
                Swal.fire({
                    icon: "error",
                    title: "Data Belum Lengkap",
                    html: `Kolom berikut belum diisi:<br><ul style="text-align:left; list-style: none; padding-left: 0;">${missingFields
                        .map((field) => `<li>• ${field}</li>`)
                        .join("")}</ul>`,
                });
                return;
            }

            // Jika semua terisi, konfirmasi
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
                    // PERUBAHAN UTAMA: Kirim form data diri yang benar menggunakan ID-nya
                    document.getElementById("data-diri-form").submit();
                }
            });
        });
    }

    // ========================= OPSI PROGRAM STUDI BERDASARKAN FAKULTAS =========================

    // Fungsi ini tetap sama seperti sebelumnya
    const prodiData = {
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
            "Teknik Pertambangan",
            "Teknik Biosistem",
            "Teknologi Industri Pertanian",
            "Teknologi Pangan",
            "Rekayasa Kehutanan",
            "Rekayasa Kosmetik",
            "Rekayasa Minyak dan Gas",
            "Rekayasa Instrumentasi dan Automasi",
            "Rekayasa Keolahragaan",
        ],
    };

    const existingProdi =
        "{{ old('program_studi', $dataDiri->program_studi ?? '') }}";

    function updateProdi(setSelectedProdi = null) {
        const fakultasInput = document.querySelector(
            'input[name="fakultas"]:checked'
        );
        if (!fakultasInput) return;

        const fakultas = fakultasInput.value;
        const prodiSelect = document.getElementById("program_studi");
        prodiSelect.innerHTML =
            '<option value="" disabled selected>Pilih program studi</option>';

        const prodis = prodiData[fakultas] || [];

        prodis.forEach((prodi) => {
            const option = document.createElement("option");
            option.value = prodi;
            option.textContent = prodi;
            if (prodi === setSelectedProdi) {
                option.selected = true;
            }
            prodiSelect.appendChild(option);
        });
    }

    const selectedFakultasOnLoad = document.querySelector(
        'input[name="fakultas"]:checked'
    );
    if (selectedFakultasOnLoad) {
        updateProdi(existingProdi);
    }

    document.querySelectorAll('input[name="fakultas"]').forEach((radio) => {
        radio.addEventListener("change", () => updateProdi());
    });
});
