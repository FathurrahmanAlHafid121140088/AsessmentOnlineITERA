document.addEventListener("DOMContentLoaded", function () {
    const submitButton = document.getElementById("submit-button");
    const quizForm = document.getElementById("quizForm");
    const totalQuestions = 38;

    const toggleButton = document.getElementById("toggle-sidebar");
    const questionStatusContainer = document.getElementById(
        "question-status-container"
    );
    const showSidebarButton = document.getElementById("show-sidebar");

    // ===== Hide sidebar on load without animation =====
    if (questionStatusContainer && showSidebarButton) {
        questionStatusContainer.classList.add("collapsed", "no-transition");
        showSidebarButton.style.display = "block";

        // Aktifkan kembali transisi setelah beberapa milidetik
        setTimeout(() => {
            questionStatusContainer.classList.remove("no-transition");
        }, 100);
    }

    // ===== Validasi Submit =====
    if (submitButton && quizForm) {
        submitButton.addEventListener("click", function (e) {
            e.preventDefault();
            let unansweredQuestions = [];
            const allQuestions = document.querySelectorAll(".question");

            allQuestions.forEach((question) => {
                const number = question
                    .getAttribute("id")
                    .replace("question", "");
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
    }

    // ===== Buat Indikator Soal =====
    const questionGrid = document.getElementById("question-grid");
    for (let i = 1; i <= totalQuestions; i++) {
        const box = document.createElement("button");
        box.className = "question-btn";
        box.dataset.question = i;
        box.innerHTML = `<span>${i}</span><span class="status">❌</span>`;
        box.style.cursor = "pointer";

        box.addEventListener("click", () => {
            const target = document.getElementById(`question${i}`);
            if (target) {
                target.scrollIntoView({ behavior: "smooth", block: "center" });
            }
        });

        questionGrid.appendChild(box);
    }

    // ===== Update status indikator soal =====
    function updateStatus() {
        for (let i = 1; i <= totalQuestions; i++) {
            const answered = document.querySelector(
                `input[name="question${i}"]:checked`
            );
            const btn = document.querySelector(
                `.question-btn[data-question="${i}"]`
            );
            const status = btn?.querySelector(".status");

            if (btn && status) {
                if (answered) {
                    status.textContent = "✅";
                    status.style.color = "white";
                    btn.classList.add("answered");
                } else {
                    status.textContent = "❌";
                    status.style.color = "red";
                    btn.classList.remove("answered");
                }
            }
        }
    }

    updateStatus();
    document.querySelectorAll("input[type='radio']").forEach((radio) => {
        radio.addEventListener("change", updateStatus);
    });

    // ===== Sidebar toggle =====
    if (toggleButton && questionStatusContainer) {
        toggleButton.addEventListener("click", function () {
            questionStatusContainer.classList.add("collapsed");
            showSidebarButton.style.display = "block";
        });
    }

    if (showSidebarButton && questionStatusContainer) {
        showSidebarButton.addEventListener("click", function () {
            questionStatusContainer.classList.remove("collapsed");
            showSidebarButton.style.display = "none";
        });
    }

    // ===== Klik luar sidebar untuk menyembunyikan =====
    document.addEventListener("click", function (event) {
        const isInsideSidebar = questionStatusContainer.contains(event.target);
        const isToggleButton = toggleButton?.contains(event.target);
        const isShowButton = showSidebarButton?.contains(event.target);

        if (
            !isInsideSidebar &&
            !isToggleButton &&
            !isShowButton &&
            !questionStatusContainer.classList.contains("collapsed")
        ) {
            questionStatusContainer.classList.add("collapsed");
            showSidebarButton.style.display = "block";
        }
    });
});
document.addEventListener("DOMContentLoaded", function () {
    const btnUp = document.getElementById("scroll-up");
    const btnDown = document.getElementById("scroll-down");

    // Scroll atas
    btnUp?.addEventListener("click", function () {
        window.scrollTo({ top: 0, behavior: "smooth" });
    });

    // Scroll bawah
    btnDown?.addEventListener("click", function () {
        window.scrollTo({
            top: document.body.scrollHeight,
            behavior: "smooth",
        });
    });

    // Tampilkan tombol berdasarkan posisi scroll
    window.addEventListener("scroll", () => {
        const scrollY = window.scrollY;
        const windowHeight = window.innerHeight;
        const fullHeight = document.documentElement.scrollHeight;

        // Tampilkan tombol atas jika sudah scroll > 200px
        if (scrollY > 200) {
            btnUp?.classList.add("visible");
        } else {
            btnUp?.classList.remove("visible");
        }

        // Tampilkan tombol bawah jika belum mencapai paling bawah
        if (scrollY + windowHeight < fullHeight - 100) {
            btnDown?.classList.add("visible");
        } else {
            btnDown?.classList.remove("visible");
        }
    });
});
