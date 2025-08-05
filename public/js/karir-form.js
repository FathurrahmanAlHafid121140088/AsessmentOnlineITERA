document.addEventListener("DOMContentLoaded", function () {
    // Cek apakah data tersedia dari server
    const pekerjaanData = window.pekerjaanData || {};
    const categoriesContainer = document.getElementById("categories-container");
    const submitButton = document.getElementById("submit-button");

    // Object untuk menyimpan peringkat
    let peringkatData = {};

    // Inisialisasi peringkat data structure
    function initializePeringkatData() {
        Object.keys(pekerjaanData).forEach((kategori) => {
            peringkatData[kategori] = {};
            pekerjaanData[kategori].forEach((job) => {
                peringkatData[kategori][job] = null;
            });
        });
    }

    // Render form berdasarkan data pekerjaan
    function renderCategories() {
        categoriesContainer.innerHTML = "";

        Object.keys(pekerjaanData).forEach((kategori) => {
            const jobs = pekerjaanData[kategori];

            // Container untuk setiap kategori
            const categoryDiv = document.createElement("div");
            categoryDiv.className = "category-section";
            categoryDiv.setAttribute("data-category", kategori);

            // Header kategori
            const headerDiv = document.createElement("div");
            headerDiv.className = "category-header";
            headerDiv.innerHTML = `<h3>${kategori}</h3>`;

            // Error messages container
            const errorContainer = document.createElement("div");
            errorContainer.className = "error-container";
            errorContainer.innerHTML = `
                <div class="error-message">⚠️ Terdapat peringkat duplikat pada kategori ini</div>
                <div class="unfill-message">⚠️ Masih ada pekerjaan yang belum diberi peringkat</div>
            `;

            // Container untuk pekerjaan dalam kategori
            const jobsDiv = document.createElement("div");
            jobsDiv.className = "jobs-container";

            jobs.forEach((job) => {
                const jobDiv = document.createElement("div");
                jobDiv.className = "job-item";

                jobDiv.innerHTML = `
                    <div class="job-info">
                        <label class="job-label">${job}</label>
                    </div>
                    <div class="job-ranking">
                        <select name="peringkat[${kategori}][${job}]" class="ranking-select" data-kategori="${kategori}" data-job="${job}" required>
                            <option value="">Pilih Peringkat</option>
                            ${Array.from({ length: 12 }, (_, i) => i + 1)
                                .map(
                                    (num) =>
                                        `<option value="${num}">${num}</option>`
                                )
                                .join("")}
                        </select>
                    </div>
                `;

                jobsDiv.appendChild(jobDiv);
            });

            categoryDiv.appendChild(headerDiv);
            categoryDiv.appendChild(errorContainer);
            categoryDiv.appendChild(jobsDiv);
            categoriesContainer.appendChild(categoryDiv);
        });

        // Add event listeners untuk validasi peringkat
        addRankingValidation();
    }

    // Validasi peringkat dalam setiap kategori
    function addRankingValidation() {
        const selects = document.querySelectorAll(".ranking-select");

        selects.forEach((select) => {
            select.addEventListener("change", function () {
                const kategori = this.dataset.kategori;
                const job = this.dataset.job;
                const peringkat = parseInt(this.value);

                if (!peringkatData[kategori]) {
                    peringkatData[kategori] = {};
                }

                // Hapus peringkat lama jika ada
                Object.keys(peringkatData[kategori]).forEach((oldJob) => {
                    if (
                        peringkatData[kategori][oldJob] === peringkat &&
                        oldJob !== job
                    ) {
                        delete peringkatData[kategori][oldJob];
                        // Reset select yang conflict
                        const conflictSelect = document.querySelector(
                            `[data-kategori="${kategori}"][data-job="${oldJob}"]`
                        );
                        if (conflictSelect) {
                            conflictSelect.value = "";
                        }
                    }
                });

                peringkatData[kategori][job] = peringkat;

                // Update visual feedback
                updateCategoryProgress(kategori);

                // Hide error messages jika sudah valid
                hideErrorMessages(kategori);
            });
        });
    }

    // Update progress visual untuk setiap kategori
    function updateCategoryProgress(kategori) {
        const categorySection = document.querySelector(
            `[data-category="${kategori}"]`
        );
        const totalJobs = pekerjaanData[kategori].length;
        const completedJobs = Object.values(
            peringkatData[kategori] || {}
        ).filter(
            (value) =>
                value !== null &&
                value !== "" &&
                !isNaN(value) &&
                value >= 1 &&
                value <= 12
        ).length;

        // Add atau update progress indicator
        let progressDiv = categorySection.querySelector(".category-progress");
        if (!progressDiv) {
            progressDiv = document.createElement("div");
            progressDiv.className = "category-progress";
            categorySection
                .querySelector(".category-header")
                .appendChild(progressDiv);
        }

        progressDiv.innerHTML = `<span class="progress-text">${completedJobs}/${totalJobs} selesai</span>`;

        if (completedJobs === totalJobs) {
            categorySection.classList.add("completed");
        } else {
            categorySection.classList.remove("completed");
        }
    }

    // Hide error messages untuk kategori tertentu
    function hideErrorMessages(kategori) {
        const categorySection = document.querySelector(
            `[data-category="${kategori}"]`
        );
        const errorMessages = categorySection.querySelectorAll(
            ".error-message, .unfill-message"
        );
        errorMessages.forEach((msg) => {
            msg.classList.remove("visible");
        });
    }

    // Cek peringkat unik dalam kategori
    function cekPeringkatUnik(kategori) {
        const peringkatKategori = Object.values(peringkatData[kategori] || {});
        const peringkatValid = peringkatKategori.filter(
            (r) => r !== null && r !== "" && !isNaN(r) && r >= 1 && r <= 12
        );
        const peringkatUnik = new Set(peringkatValid);
        return peringkatUnik.size === peringkatValid.length;
    }

    // Cek semua peringkat terisi dalam kategori
    function cekSemuaPeringkatTerisi(kategori) {
        const totalJobs = pekerjaanData[kategori].length;
        const filledJobs = Object.values(peringkatData[kategori] || {}).filter(
            (r) => r !== null && r !== "" && !isNaN(r)
        ).length;
        return filledJobs === totalJobs;
    }

    // Validasi form sebelum submit
    function validateForm() {
        let valid = true;
        let errorDetails = {
            duplicates: [],
            unfilled: [],
        };

        Object.keys(pekerjaanData).forEach((kategori) => {
            // Cek peringkat duplikat
            if (!cekPeringkatUnik(kategori)) {
                valid = false;
                errorDetails.duplicates.push(kategori);

                // Tampilkan error message
                const categorySection = document.querySelector(
                    `[data-category="${kategori}"]`
                );
                const errorMessage =
                    categorySection.querySelector(".error-message");
                if (errorMessage) {
                    errorMessage.classList.add("visible");
                }
            }

            // Cek apakah ada peringkat yang belum terisi
            if (!cekSemuaPeringkatTerisi(kategori)) {
                valid = false;
                errorDetails.unfilled.push(kategori);

                // Tampilkan unfill message
                const categorySection = document.querySelector(
                    `[data-category="${kategori}"]`
                );
                const unfillMessage =
                    categorySection.querySelector(".unfill-message");
                if (unfillMessage) {
                    unfillMessage.classList.add("visible");
                }
            }
        });

        if (!valid) {
            // Scroll ke kategori pertama yang error
            const firstErrorCategory =
                errorDetails.unfilled[0] || errorDetails.duplicates[0];
            if (firstErrorCategory) {
                const categoryElement = document.querySelector(
                    `[data-category="${firstErrorCategory}"]`
                );
                if (categoryElement) {
                    categoryElement.scrollIntoView({
                        behavior: "smooth",
                        block: "center",
                    });
                }
            }
        }

        return { valid, errorDetails };
    }

    // Validasi top 3 choices
    function validateTopChoices() {
        const top1 = document.getElementById("top1").value;
        const top2 = document.getElementById("top2").value;
        const top3 = document.getElementById("top3").value;

        if (!top1 || !top2 || !top3) {
            alert("Harap pilih Top 3 pekerjaan yang paling Anda sukai!");
            return false;
        }

        if (top1 === top2 || top1 === top3 || top2 === top3) {
            alert("Top 3 pekerjaan tidak boleh sama!");
            return false;
        }

        return true;
    }

    // Event listener untuk submit
    if (submitButton) {
        submitButton.addEventListener("click", function (e) {
            e.preventDefault();

            const validation = validateForm();
            if (!validation.valid) {
                return false;
            }

            if (!validateTopChoices()) {
                return false;
            }

            // Tampilkan konfirmasi dengan SweetAlert jika tersedia
            if (typeof Swal !== "undefined") {
                Swal.fire({
                    title: "Konfirmasi Pengiriman",
                    text: "Apakah Anda yakin dengan jawaban yang telah diisi?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, Kirim!",
                    cancelButtonText: "Batal",
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Siapkan data untuk dikirim
                        const form = document.querySelector("form");
                        const hiddenInput = document.createElement("input");
                        hiddenInput.type = "hidden";
                        hiddenInput.name = "peringkat";
                        hiddenInput.value = JSON.stringify(peringkatData);
                        form.appendChild(hiddenInput);

                        // Submit form
                        form.submit();
                    }
                });
            } else {
                // Fallback tanpa SweetAlert
                if (
                    confirm(
                        "Apakah Anda yakin dengan jawaban yang telah diisi?"
                    )
                ) {
                    const form = document.querySelector("form");
                    const hiddenInput = document.createElement("input");
                    hiddenInput.type = "hidden";
                    hiddenInput.name = "peringkat";
                    hiddenInput.value = JSON.stringify(peringkatData);
                    form.appendChild(hiddenInput);

                    form.submit();
                }
            }
        });
    }

    // Initialize form
    if (Object.keys(pekerjaanData).length > 0) {
        initializePeringkatData();
        renderCategories();
    } else {
        categoriesContainer.innerHTML =
            '<p class="error">Data pekerjaan tidak ditemukan. Pastikan file JSON tersedia.</p>';
    }
});
