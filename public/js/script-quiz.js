// Progress bar update
const radios = document.querySelectorAll('input[type="radio"]');
const progressBar = document.getElementById("progress-bar");
const progressText = document.getElementById("progress-text");
let questionsAnswered = 0;
const totalQuestions = 38; // Total pertanyaan

// Form submission
const quizForm = document.getElementById("quizForm");
const resultsContainer = document.getElementById("results-container");

function submitForm() {
    // Periksa apakah semua pertanyaan telah dijawab
    const unansweredQuestions = [];
    for (let i = 1; i <= totalQuestions; i++) {
        if (!document.querySelector(`input[name="question${i}"]:checked`)) {
            unansweredQuestions.push(i);
        }
    }

    if (unansweredQuestions.length > 0) {
        alert(
            `Mohon jawab pertanyaan nomor: ${unansweredQuestions.join(", ")}`
        );
        return;
    }

    // Jawaban benar (sesuaikan dengan kunci jawaban yang benar)
    const correctAnswers = {
        question1: "a",
        question2: "a",
        question3: "b",
        question4: "a",
        question5: "b",
        question6: "a",
        question7: "b",
        question8: "c",
        question9: "a",
        question10: "b",
        question11: "c",
        question12: "a",
        question13: "b",
        question14: "c",
        question15: "a",
        question16: "b",
        question17: "c",
        question18: "a",
        question19: "b",
        question20: "c",
        question21: "a",
        question22: "b",
        question23: "c",
        question24: "a",
        question25: "b",
        question26: "c",
        question27: "a",
        question28: "b",
        question29: "c",
        question30: "a",
        question31: "b",
        question32: "c",
        question33: "a",
        question34: "b",
        question35: "c",
        question36: "a",
        question37: "b",
        question38: "c",
    };

    // Hitung skor
    let score = 0;
    for (let i = 1; i <= totalQuestions; i++) {
        const selectedAnswer = document.querySelector(
            `input[name="question${i}"]:checked`
        ).value;
        if (selectedAnswer === correctAnswers[`question${i}`]) {
            score++;
        }
    }

    // Tampilkan hasil
    resultsContainer.innerHTML = `
        <div class="text-center mb-4">
            <h3>Hasil Quiz</h3>
            <p class="fs-1 fw-bold">${score}/${totalQuestions}</p>
            <p class="fs-4">${Math.round((score / totalQuestions) * 100)}%</p>
            <div class="progress mt-3 mb-3" style="height: 20px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: ${
                    (score / totalQuestions) * 100
                }%" aria-valuenow="${
        (score / totalQuestions) * 100
    }" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
        <div class="text-center">
            <button class="btn btn-outline-primary me-2" onclick="location.reload();">
                <i class="fas fa-redo me-2"></i>Coba Lagi
            </button>
            <button class="btn btn-outline-success" onclick="showAnswers();">
                <i class="fas fa-check-circle me-2"></i>Lihat Jawaban
            </button>
        </div>
    `;
    resultsContainer.style.display = "block";

    // Sembunyikan tombol submit
    document.getElementById("submit-button").style.display = "none";

    // Nonaktifkan semua input
    document.querySelectorAll("input").forEach((input) => {
        input.disabled = true;
    });
}

quizForm.addEventListener("submit", function (event) {
    event.preventDefault();
    submitForm();
});

// Fungsi untuk menampilkan jawaban benar
function showAnswers() {
    const correctAnswers = {
        question1: "a",
        question2: "a",
        question3: "b",
        question4: "a",
        question5: "b",
        question6: "a",
        question7: "b",
        question8: "c",
        question9: "a",
        question10: "b",
        question11: "c",
        question12: "a",
        question13: "b",
        question14: "c",
        question15: "a",
        question16: "b",
        question17: "c",
        question18: "a",
        question19: "b",
        question20: "c",
        question21: "a",
        question22: "b",
        question23: "c",
        question24: "a",
        question25: "b",
        question26: "c",
        question27: "a",
        question28: "b",
        question29: "c",
        question30: "a",
        question31: "b",
        question32: "c",
        question33: "a",
        question34: "b",
        question35: "c",
        question36: "a",
        question37: "b",
        question38: "c",
    };

    for (let i = 1; i <= totalQuestions; i++) {
        const correctOption = document.querySelector(
            `#q${i}${correctAnswers[`question${i}`]}`
        );
        if (!correctOption) continue;

        const correctLabel = correctOption.closest(".custom-radio");
        if (correctLabel) {
            correctLabel.style.backgroundColor = "rgba(40, 167, 69, 0.2)";
            correctLabel.style.borderColor = "#28a745";
        }

        const selectedElement = document.querySelector(
            `input[name="question${i}"]:checked`
        );
        if (!selectedElement) continue;

        const selectedLabel = selectedElement.closest(".custom-radio");
        if (selectedLabel && selectedLabel !== correctLabel) {
            selectedLabel.style.backgroundColor = "rgba(220, 53, 69, 0.2)";
            selectedLabel.style.borderColor = "#dc3545";
        }
    }
}
document.addEventListener("DOMContentLoaded", function () {
    const scrollTopBtn = document.getElementById("scrollToTopBtn");
    const scrollBottomBtn = document.getElementById("scrollToBottomBtn");

    window.addEventListener("scroll", () => {
        if (window.scrollY > 300) {
            scrollTopBtn.style.display = "flex";
        } else {
            scrollTopBtn.style.display = "none";
        }

        // Tampilkan tombol bawah hanya jika belum di dekat bawah
        if (
            window.innerHeight + window.scrollY <
            document.body.offsetHeight - 300
        ) {
            scrollBottomBtn.style.display = "flex";
        } else {
            scrollBottomBtn.style.display = "none";
        }
    });

    scrollTopBtn.addEventListener("click", () => {
        window.scrollTo({ top: 0, behavior: "smooth" });
    });

    scrollBottomBtn.addEventListener("click", () => {
        window.scrollTo({
            top: document.body.scrollHeight,
            behavior: "smooth",
        });
    });
});

document
    .getElementById("submit-button")
    .addEventListener("click", function (event) {
        let allQuestions = document.querySelectorAll(".question"); // Ambil semua pertanyaan
        let unansweredQuestions = []; // Array untuk menyimpan elemen pertanyaan yang belum dijawab
        let unansweredNumbers = []; // Array untuk menyimpan nomor pertanyaan yang belum dijawab

        // Loop untuk mencari pertanyaan yang belum dijawab
        allQuestions.forEach((question) => {
            let questionNumber = question
                .getAttribute("id")
                .replace("question", ""); // Ambil nomor pertanyaan
            let inputs = document.querySelectorAll(
                `input[name="question${questionNumber}"]:checked`
            );

            if (inputs.length === 0) {
                unansweredQuestions.push(question);
                unansweredNumbers.push(questionNumber);
                question.classList.add("highlight-question"); // Tambahkan highlight
            } else {
                question.classList.remove("highlight-question"); // Hapus highlight jika sudah dijawab
            }
        });

        if (unansweredQuestions.length > 0) {
            // Buat string daftar nomor pertanyaan yang belum dijawab
            let unansweredList = unansweredNumbers.join(", ");

            // Tampilkan SweetAlert dengan daftar nomor soal yang belum dijawab
            Swal.fire({
                title: "Oops!",
                html: `Harap isi semua pertanyaan sebelum mengirim!<br><br><strong>Belum dijawab No:</strong> ${unansweredList}`,
                icon: "warning",
                confirmButtonText: "OK",
                timer: 4000,
            }).then(() => {
                setTimeout(() => {
                    unansweredQuestions[0].scrollIntoView({
                        behavior: "smooth",
                        block: "center",
                    });
                }, 100);
            });
        } else {
            // Jika semua pertanyaan sudah dijawab, konfirmasi pengiriman
            Swal.fire({
                title: "Yakin ingin mengirim?",
                text: "Pastikan semua jawaban sudah benar!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Kirim!",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Berhasil!",
                        text: "Jawaban Anda telah dikirim.",
                        icon: "success",
                        timer: 2000,
                        showConfirmButton: false,
                    });

                    // Submit form setelah delay
                    setTimeout(() => {
                        document.querySelector("form").submit();
                    }, 2000);
                }
            });
        }
    });

document.addEventListener("DOMContentLoaded", function () {
    const allQuestions = Array.from(document.querySelectorAll(".question"))
        .map((el) => {
            const id = el.getAttribute("id");
            const number = parseInt(id.replace("question", ""));
            return { el, number };
        })
        .sort((a, b) => a.number - b.number); // ✅ Urutkan berdasarkan nomor soal

    const statusContainer = document.getElementById("question-grid");

    const totalQuestions = allQuestions.length;
    const maxRows = 12;
    const totalCols = Math.ceil(totalQuestions / maxRows);

    // Buat array kolom
    const columns = Array.from({ length: totalCols }, () => []);

    // Susun soal secara vertikal per kolom
    allQuestions.forEach(({ number }, index) => {
        const colIndex = index % totalCols;
        columns[colIndex].push(number);
    });

    // Render: iterasi baris dulu agar hasil vertikal per kolom
    for (let row = 0; row < maxRows; row++) {
        for (let col = 0; col < totalCols; col++) {
            const number = columns[col][row];
            if (!number) continue;

            const statusItem = document.createElement("div");
            statusItem.className = "status-item";
            statusItem.setAttribute("data-question-number", number);
            statusItem.setAttribute("title", `Soal nomor ${number}`);
            statusItem.innerHTML = `<span>${number}</span><span class="icon text-red-500">❌</span>`;

            statusItem.addEventListener("click", () => {
                const targetQuestion = document.getElementById(
                    `question${number}`
                );
                if (targetQuestion) {
                    targetQuestion.scrollIntoView({
                        behavior: "smooth",
                        block: "center",
                    });
                }
            });

            statusContainer.appendChild(statusItem);
        }
    }

    // Update status soal saat dijawab
    function updateQuestionStatus() {
        allQuestions.forEach(({ number }) => {
            const inputs = document.querySelectorAll(
                `input[name="question${number}"]:checked`
            );
            const icon = document.querySelector(
                `.status-item[data-question-number="${number}"] .icon`
            );
            if (icon) {
                if (inputs.length > 0) {
                    icon.textContent = "✔️";
                    icon.classList.remove("text-red-500");
                    icon.classList.add("text-green-500");
                } else {
                    icon.textContent = "❌";
                    icon.classList.remove("text-green-500");
                    icon.classList.add("text-red-500");
                }
            }
        });
    }

    updateQuestionStatus();

    allQuestions.forEach(({ number }) => {
        const inputs = document.querySelectorAll(
            `input[name="question${number}"]`
        );
        inputs.forEach((input) => {
            input.addEventListener("change", updateQuestionStatus);
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const toggleArrow = document.getElementById("toggle-sidebar");
    const showArrow = document.getElementById("show-sidebar");
    const statusContainer = document.getElementById(
        "question-status-container"
    );

    // Fungsi sembunyikan sidebar
    toggleArrow.addEventListener("click", () => {
        statusContainer.classList.add("hidden-sidebar");
        toggleArrow.style.display = "none";
        showArrow.style.display = "flex";
    });

    // Fungsi tampilkan sidebar
    showArrow.addEventListener("click", () => {
        statusContainer.classList.remove("hidden-sidebar");
        toggleArrow.style.display = "flex";
        showArrow.style.display = "none";
    });

    // Inisialisasi tombol
    if (statusContainer.classList.contains("hidden-sidebar")) {
        toggleArrow.style.display = "none";
        showArrow.style.display = "flex";
    } else {
        toggleArrow.style.display = "flex";
        showArrow.style.display = "none";
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const toggleArrow = document.getElementById("toggle-sidebar");
    const showArrow = document.getElementById("show-sidebar");
    const statusContainer = document.getElementById(
        "question-status-container"
    );

    // Toggle sidebar
    toggleArrow?.addEventListener("click", () => {
        statusContainer?.classList.add("hidden-sidebar");
        toggleArrow.style.display = "none";
        showArrow.style.display = "flex";
    });

    showArrow?.addEventListener("click", () => {
        statusContainer?.classList.remove("hidden-sidebar");
        toggleArrow.style.display = "flex";
        showArrow.style.display = "none";
    });

    if (statusContainer?.classList.contains("hidden-sidebar")) {
        toggleArrow.style.display = "none";
        showArrow.style.display = "flex";
    } else {
        toggleArrow.style.display = "flex";
        showArrow.style.display = "none";
    }

    // =============================
    // Indikator soal
    // =============================

    const questionIndicator = document.getElementById("question-indicator");
    if (!questionIndicator) return;

    const totalQuestions = parseInt(questionIndicator.dataset.totalQuestions);

    for (let i = 1; i <= totalQuestions; i++) {
        const inputs = document.querySelectorAll(
            `#question${i} input[type="radio"], 
       #question${i} input[type="checkbox"], 
       #question${i} textarea`
        );

        // Cek status saat load
        updateStatus(i);

        inputs.forEach((input) => {
            input.addEventListener("change", () => updateStatus(i));
        });

        // Klik untuk scroll
        const box = document.querySelector(
            `.question-box[data-question="${i}"]`
        );
        if (box) {
            box.addEventListener("click", () => {
                const target = document.getElementById(`question${i}`);
                if (target) {
                    target.scrollIntoView({
                        behavior: "smooth",
                        block: "start",
                    });
                }
            });
        }
    }

    function updateStatus(i) {
        const inputs = document.querySelectorAll(
            `#question${i} input[type="radio"], 
       #question${i} input[type="checkbox"], 
       #question${i} textarea`
        );

        const answered = Array.from(inputs).some((input) => {
            if (input.type === "radio" || input.type === "checkbox")
                return input.checked;
            if (input.tagName.toLowerCase() === "textarea")
                return input.value.trim() !== "";
            return false;
        });

        const box = document.querySelector(
            `.question-box[data-question="${i}"]`
        );
        if (box) {
            const statusEl = box.querySelector(".status");
            if (answered) {
                box.classList.add("answered");
                statusEl.textContent = "✅";
            } else {
                box.classList.remove("answered");
                statusEl.textContent = "❌";
            }
        }
    }
});
