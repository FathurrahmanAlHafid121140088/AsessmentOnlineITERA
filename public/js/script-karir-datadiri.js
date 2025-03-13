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
                    window.location.href = "/karir-form";
                });
            }
        });
    });
