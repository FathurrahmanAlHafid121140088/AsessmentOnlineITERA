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
