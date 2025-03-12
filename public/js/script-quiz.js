// Progress bar update
const radios = document.querySelectorAll('input[type="radio"]');
const progressBar = document.getElementById("progress-bar");
const progressText = document.getElementById("progress-text");
let questionsAnswered = 0;
const totalQuestions = 38; // Total pertanyaan

// Set untuk menyimpan pertanyaan yang telah dijawab
const answeredQuestions = new Set();

radios.forEach((radio) => {
    radio.addEventListener("change", function () {
        const name = this.name; // Mendapatkan nama input (misal: question1, question2, dst.)

        // Jika ini adalah pertama kali pertanyaan ini dijawab
        if (!answeredQuestions.has(name)) {
            answeredQuestions.add(name);
            updateProgress();
        }

        // Reset highlight hanya untuk pertanyaan yang sedang dijawab
        document.querySelectorAll(`input[name="${name}"]`).forEach((option) => {
            const optionLabel = option.closest(".custom-radio");
            if (optionLabel) {
                optionLabel.style.backgroundColor = "";
                optionLabel.style.borderColor = "transparent";
            }
        });

        // Highlight jawaban yang dipilih
        const selectedLabel = this.closest(".custom-radio");
        if (selectedLabel) {
            selectedLabel.style.backgroundColor = "rgba(67, 97, 238, 0.1)";
            selectedLabel.style.borderColor = "#4361ee";
        }
    });
});

function updateProgress() {
    // Hitung jumlah pertanyaan unik yang sudah dijawab
    questionsAnswered = answeredQuestions.size;
    const percentage = (questionsAnswered / totalQuestions) * 100;

    progressBar.style.width = percentage + "%";
    progressBar.setAttribute("aria-valuenow", percentage);
    progressText.textContent = `${questionsAnswered}/${totalQuestions} Pertanyaan Dijawab`;
}

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
    let scrollToTopBtn = document.getElementById("scrollToTopBtn");

    // Tampilkan tombol saat pengguna scroll ke bawah
    window.addEventListener("scroll", function () {
        if (window.scrollY > 300) {
            scrollToTopBtn.classList.add("show");
        } else {
            scrollToTopBtn.classList.remove("show");
        }
    });

    // Fungsi klik untuk kembali ke atas
    scrollToTopBtn.addEventListener("click", function () {
        window.scrollTo({
            top: 0,
            behavior: "smooth",
        });
    });
});
document
    .getElementById("submit-button")
    .addEventListener("click", function (event) {
        let allQuestions = document.querySelectorAll(".question"); // Ambil semua pertanyaan
        let unansweredQuestions = []; // Array untuk menyimpan pertanyaan yang belum dijawab

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
                question.classList.add("highlight-question"); // Tambahkan highlight
            } else {
                question.classList.remove("highlight-question"); // Hapus highlight jika sudah dijawab
            }
        });

        if (unansweredQuestions.length > 0) {
            // Jika ada yang belum dijawab, munculkan SweetAlert dan arahkan ke pertanyaan pertama yang belum dijawab
            Swal.fire({
                title: "Oops!",
                text: "Harap isi semua pertanyaan sebelum mengirim!",
                icon: "warning",
                confirmButtonText: "OK",
                timer: 3000,
            }).then(() => {
                setTimeout(() => {
                    unansweredQuestions[0].scrollIntoView({
                        behavior: "smooth",
                        block: "center",
                    }); // Scroll ke pertanyaan yang belum dijawab berikutnya
                }, 100); // Tambahkan delay kecil agar SweetAlert selesai dulu
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
