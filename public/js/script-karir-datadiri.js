document
    .getElementById("submit-button")
    .addEventListener("click", function (event) {
        event.preventDefault(); // Mencegah form langsung terkirim

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
                // Jika pengguna menekan "Ya, kirim!", tampilkan alert sukses
                Swal.fire({
                    title: "Terkirim!",
                    text: "Data Diri Anda telah berhasil dikirim.",
                    icon: "success",
                    timer: 2000, // Alert otomatis tertutup dalam 2 detik
                    showConfirmButton: false,
                }).then(() => {
                    // Arahkan ke halaman /mental-health-kuesioner setelah alert sukses ditutup
                    document.querySelector("form").submit();
                });
            }
        });
    });

const prodiOptions = {
    "Fakultas Sains": [
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
    "Fakultas Teknologi Infrastruktur dan Kewilayahan": [
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
    "Fakultas Teknologi Industri": [
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

function updateProdi() {
    const selectedFakultas = document.querySelector(
        'input[name="fakultas"]:checked'
    );
    const prodiSelect = document.getElementById("program_studi");

    if (!selectedFakultas) return;

    const fakultas = selectedFakultas.value;
    const prodis = prodiOptions[fakultas] || [];

    // Kosongkan opsi dan tambahkan opsi default
    prodiSelect.innerHTML =
        '<option value="" disabled selected>Pilih program studi</option>';

    prodis.forEach((prodi) => {
        const option = document.createElement("option");
        option.value = prodi;
        option.textContent = prodi;
        prodiSelect.appendChild(option);
    });
}
