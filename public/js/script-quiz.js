document.addEventListener("DOMContentLoaded", function () {
    const submitButton = document.getElementById("submit-button");
    const quizForm = document.getElementById("quizForm");
    const totalQuestions = 38;

    submitButton.addEventListener("click", function (e) {
        e.preventDefault();

        let unansweredQuestions = [];
        const allQuestions = document.querySelectorAll(".question");

        allQuestions.forEach((question) => {
            const number = question.getAttribute("id").replace("question", "");
            const selected = document.querySelector(
                `input[name="question${number}"]:checked`
            );
            if (!selected) {
                unansweredQuestions.push(number);
                question.classList.add("highlight-question");
            } else {
                question.classList.remove("highlight-question");
            }
        });

        if (unansweredQuestions.length > 0) {
            Swal.fire({
                title: "Oops!",
                html: `Harap isi semua pertanyaan sebelum mengirim!<br><br><strong>Belum dijawab No:</strong> ${unansweredQuestions.join(
                    ", "
                )}`,
                icon: "warning",
                confirmButtonText: "OK",
                timer: 4000,
            }).then(() => {
                const firstUnanswered = document.getElementById(
                    `question${unansweredQuestions[0]}`
                );
                if (firstUnanswered) {
                    firstUnanswered.scrollIntoView({
                        behavior: "smooth",
                        block: "center",
                    });
                }
            });
        } else {
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
                    quizForm.submit();
                }
            });
        }
    });

    // indikator status soal
    const questionGrid = document.getElementById("question-grid");

    for (let i = 1; i <= totalQuestions; i++) {
        const box = document.createElement("div");
        box.className = "question-box";
        box.dataset.question = i;
        box.innerHTML = `
            <span>${i}</span>
            <span class="status">❌</span>
        `;
        box.style.cursor = "pointer";

        box.addEventListener("click", () => {
            const target = document.getElementById(`question${i}`);
            if (target) {
                target.scrollIntoView({ behavior: "smooth", block: "center" });
            }
        });

        questionGrid.appendChild(box);
    }

    function updateStatus() {
        for (let i = 1; i <= totalQuestions; i++) {
            const answered = document.querySelector(
                `input[name="question${i}"]:checked`
            );
            const box = document.querySelector(
                `.question-box[data-question="${i}"] .status`
            );
            if (box) {
                if (answered) {
                    box.textContent = "✅";
                    box.style.color = "green";
                } else {
                    box.textContent = "❌";
                    box.style.color = "red";
                }
            }
        }
    }

    updateStatus();

    const radios = document.querySelectorAll("input[type='radio']");
    radios.forEach((radio) => {
        radio.addEventListener("change", updateStatus);
    });

    // 👇 TOMBOL TOGGLE DAFTAR SOAL
    const toggleButton = document.getElementById("toggle-sidebar");
    const questionStatusContainer = document.getElementById(
        "question-status-container"
    );

    toggleButton.addEventListener("click", function () {
        questionStatusContainer.classList.toggle("collapsed");
    });
});
