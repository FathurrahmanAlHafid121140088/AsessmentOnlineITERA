// Mobile menu toggle
const menuToggle = document.getElementById("menuToggle");
const sidebar = document.getElementById("sidebar");
const overlay = document.getElementById("overlay");

if (menuToggle && sidebar && overlay) {
    menuToggle.addEventListener("click", () => {
        sidebar.classList.toggle("active");
        overlay.classList.toggle("active");

        // Ubah ikon hamburger menjadi X
        const icon = menuToggle.querySelector("i");
        icon.classList.toggle("fa-bars");
        icon.classList.toggle("fa-times");
    });

    overlay.addEventListener("click", () => {
        sidebar.classList.remove("active");
        overlay.classList.remove("active");

        // Pastikan ikon kembali ke hamburger
        const icon = menuToggle.querySelector("i");
        icon.classList.remove("fa-times");
        icon.classList.add("fa-bars");
    });
}

// Menu item interaction
const menuItems = document.querySelectorAll(".menu-item");
menuItems.forEach((item) => {
    item.addEventListener("click", (e) => {
        e.preventDefault();
        menuItems.forEach((mi) => mi.classList.remove("active"));
        item.classList.add("active");

        // Auto-close sidebar on mobile after clicking a menu item
        if (window.innerWidth <= 768) {
            sidebar.classList.remove("active");
            overlay.classList.remove("active");

            const icon = menuToggle.querySelector("i");
            icon.classList.remove("fa-times");
            icon.classList.add("fa-bars");
        }
    });
});

// Action button interactions
const actionBtns = document.querySelectorAll(".action-btn");
actionBtns.forEach((btn) => {
    btn.addEventListener("click", (e) => {
        const action = btn.textContent.trim();
        if (action.includes("Lihat")) {
            alert("Menampilkan detail hasil tes...");
        } else if (action.includes("Lanjut")) {
            alert("Melanjutkan tes yang tertunda...");
        }
    });
});

// Add smooth animations on scroll
window.addEventListener("scroll", () => {
    const cards = document.querySelectorAll(".card");
    cards.forEach((card) => {
        const cardTop = card.getBoundingClientRect().top;
        const cardVisible = 150;

        if (cardTop < window.innerHeight - cardVisible) {
            card.style.opacity = "1";
            card.style.transform = "translateY(0)";
        }
    });
});

// Initialize card animations
document.addEventListener("DOMContentLoaded", () => {
    const cards = document.querySelectorAll(".card");
    cards.forEach((card, index) => {
        card.style.opacity = "0";
        card.style.transform = "translateY(20px)";
        card.style.transition = "opacity 0.6s ease, transform 0.6s ease";

        setTimeout(() => {
            card.style.opacity = "1";
            card.style.transform = "translateY(0)";
        }, index * 100);
    });
});
document.addEventListener("DOMContentLoaded", function () {
    const toggle = document.getElementById("accountToggle");
    const dropdown = document.getElementById("accountDropdown");

    document.addEventListener("click", function (e) {
        const isClickInside =
            toggle.contains(e.target) || dropdown.contains(e.target);

        if (isClickInside) {
            dropdown.classList.toggle("show");
            toggle.classList.toggle("open");
        } else {
            dropdown.classList.remove("show");
            toggle.classList.remove("open");
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("diagnosaModal");
    const content = document.getElementById("diagnosaContent");
    const closeBtn = document.querySelector(".close");

    // Fungsi untuk ambil konten diagnosa berdasarkan kategori
    function getDiagnosaHTML(kategori) {
        switch (kategori) {
            case "Perlu Dukungan Intensif":
                return `
          <h3>🩺 Perlu Dukungan Intensif (Distres Berat)</h3>
          <p>
            Hasil tes menunjukkan bahwa kondisi kesehatan mental Anda berada pada tingkat <strong>distres berat</strong>. 
            Hal ini berarti tekanan psikologis yang Anda alami sudah memengaruhi <strong>aktivitas sehari-hari</strong> secara signifikan, 
            baik dalam pekerjaan, hubungan sosial, maupun fungsi emosional. 
            Dukungan profesional <strong>sangat disarankan</strong> dalam kondisi ini.
          </p>
          <h4>📌 Rekomendasi Penanganan:</h4>
          <ul>
            <li><i class="fas fa-user-md"></i> Segera konsultasikan kondisi Anda dengan psikolog atau psikiater profesional.</li>
            <li><i class="fas fa-bed"></i> Perbaiki pola tidur (tidur minimal 7–8 jam setiap malam).</li>
            <li><i class="fas fa-utensils"></i> Konsumsi makanan bernutrisi tinggi dan hindari kafein/alkohol berlebihan.</li>
            <li><i class="fas fa-heart"></i> Jangan hadapi semua masalah sendiri — mintalah dukungan dari orang terdekat.</li>
          </ul>
        `;

            case "Perlu Dukungan":
                return `
          <h3>🤝 Perlu Dukungan (Distres Sedang)</h3>
          <p>
            Kondisi Anda menunjukkan adanya tekanan psikologis pada tingkat <strong>sedang</strong>. 
            Anda mungkin mengalami perubahan emosi, penurunan motivasi, gangguan tidur, atau kesulitan fokus. 
            Meski belum pada tahap berat, <strong>dukungan sosial dan konseling</strong> sangat dianjurkan agar kondisi tidak memburuk.
          </p>
          <h4>📌 Langkah yang Disarankan:</h4>
          <ul>
            <li><i class="fas fa-comments"></i> Ceritakan perasaan Anda kepada orang yang dipercaya.</li>
            <li><i class="fas fa-music"></i> Luangkan waktu untuk aktivitas yang Anda nikmati.</li>
            <li><i class="fas fa-walking"></i> Lakukan aktivitas fisik ringan secara rutin.</li>
            <li><i class="fas fa-user-md"></i> Pertimbangkan sesi konseling dengan profesional jika keluhan berlanjut.</li>
          </ul>
        `;

            case "Cukup Sehat":
                return `
          <h3>🙂 Cukup Sehat (Rentan)</h3>
          <p>
            Secara umum, kondisi mental Anda tergolong <strong>stabil</strong>, namun masih ada potensi kerentanan terhadap stres 
            atau tekanan emosional. Ini artinya Anda perlu tetap memperhatikan pola hidup dan menjaga keseimbangan aktivitas agar tidak jatuh ke level yang lebih berat.
          </p>
          <h4>📌 Saran Perawatan:</h4>
          <ul>
            <li><i class="fas fa-calendar-check"></i> Atur waktu kerja, istirahat, dan rekreasi secara seimbang.</li>
            <li><i class="fas fa-walking"></i> Tetap aktif secara fisik dan sosial.</li>
            <li><i class="fas fa-brain"></i> Lakukan kegiatan mindfulness atau meditasi ringan.</li>
            <li><i class="fas fa-book"></i> Pelajari teknik manajemen stres seperti journaling atau pernapasan dalam.</li>
          </ul>
        `;

            case "Sehat":
                return `
          <h3>🌿 Sehat (Sehat Mental)</h3>
          <p>
            Selamat! Kondisi mental Anda berada pada kategori <strong>sehat</strong>. 
            Anda mampu mengelola stres, menjaga hubungan sosial yang positif, dan tetap produktif dalam keseharian. 
            Fokus utama Anda sekarang adalah <strong>mempertahankan kondisi ini</strong>.
          </p>
          <h4>📌 Tips untuk Dipertahankan:</h4>
          <ul>
            <li><i class="fas fa-seedling"></i> Lanjutkan rutinitas sehat yang sudah dijalani.</li>
            <li><i class="fas fa-users"></i> Perluas jaringan sosial dan tetap jalin komunikasi baik dengan orang terdekat.</li>
            <li><i class="fas fa-dumbbell"></i> Terus jaga keseimbangan antara pekerjaan, istirahat, dan hiburan.</li>
            <li><i class="fas fa-brain"></i> Tantang diri Anda dengan hal-hal baru untuk menjaga mental tetap aktif.</li>
          </ul>
        `;

            case "Sangat Sehat":
                return `
          <h3>💪 Sangat Sehat (Sejahtera Mental)</h3>
          <p>
            Luar biasa! Anda berada pada tingkat <strong>kesejahteraan psikologis yang sangat optimal</strong>. 
            Artinya, Anda tidak hanya bebas dari gangguan mental, tetapi juga memiliki kualitas hidup yang tinggi secara emosional, sosial, dan psikologis.
          </p>
          <h4>📌 Saran untuk Terus Berkembang:</h4>
          <ul>
            <li><i class="fas fa-lightbulb"></i> Tantang diri dengan proyek atau tujuan baru yang bermakna.</li>
            <li><i class="fas fa-dumbbell"></i> Pertahankan gaya hidup sehat secara konsisten.</li>
            <li><i class="fas fa-heart"></i> Teruslah berkontribusi pada lingkungan sosial Anda.</li>
            <li><i class="fas fa-graduation-cap"></i> Gunakan kondisi mental ini untuk mengembangkan potensi diri semaksimal mungkin.</li>
          </ul>
        `;

            default:
                return `<p>Data diagnosa tidak tersedia.</p>`;
        }
    }

    // Klik tombol modal
    document.querySelectorAll(".open-modal").forEach((button) => {
        button.addEventListener("click", (e) => {
            const kategori = e.target.getAttribute("data-kategori");
            content.innerHTML = getDiagnosaHTML(kategori);
            modal.style.display = "flex";
        });
    });

    // Tutup modal
    closeBtn.addEventListener("click", () => (modal.style.display = "none"));
    window.addEventListener("click", (e) => {
        if (e.target === modal) modal.style.display = "none";
    });
});
