// ==============================
// FLOATING PARTICLES
// ==============================
function createParticles() {
    const particlesContainer = document.getElementById("particles");
    if (!particlesContainer) return;

    const particleCount = 50;
    for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement("div");
        particle.className = "particle";
        particle.style.left = Math.random() * 100 + "%";
        particle.style.animationDelay = Math.random() * 8 + "s";
        particle.style.animationDuration = Math.random() * 3 + 8 + "s";
        particlesContainer.appendChild(particle);
    }
}

// ==============================
// COUNTER WITH EASING
// ==============================
function animateCounter(element, targetValue, duration = 2500) {
    const startValue = 0;
    const startTime = performance.now();

    function updateCounter(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);

        // easing (easeOutQuart)
        const easeOutQuart = 1 - Math.pow(1 - progress, 4);
        const currentValue = Math.floor(
            startValue + (targetValue - startValue) * easeOutQuart
        );

        element.textContent = currentValue.toLocaleString();

        if (progress < 1) {
            requestAnimationFrame(updateCounter);
        }
    }

    requestAnimationFrame(updateCounter);
}

// ==============================
// CARD CLICK HANDLER
// ==============================
function handleCardClick(cardType) {
    const card = document.querySelector(`.${cardType}`);
    if (!card) return;

    const button = card.querySelector(".card-button");
    if (!button) return;

    button.style.transform = "scale(0.95)";

    setTimeout(() => {
        button.style.transform = "translateY(-5px) scale(1.03)";

        // flash effect
        const flash = document.createElement("div");
        flash.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.15);
            pointer-events: none;
            z-index: 9999;
            opacity: 0;
            transition: opacity 0.2s;
        `;
        document.body.appendChild(flash);

        setTimeout(() => {
            flash.style.opacity = "1";
            setTimeout(() => {
                flash.style.opacity = "0";
                setTimeout(() => flash.remove(), 200);
            }, 100);
        }, 50);

        // redirect
        setTimeout(() => {
            if (cardType === "mental-health") {
                alert("🧠 Mengalihkan ke Dashboard Admin Mental Health...");
                // window.location.href = '/admin-mental-health';
            } else {
                alert("💼 Mengalihkan ke Dashboard Admin Peminatan Karir...");
                // window.location.href = '/admin-career';
            }
        }, 300);
    }, 150);
}

// ==============================
// HOVER GLOW ICON
// ==============================
function setupHoverGlow() {
    document.querySelectorAll(".admin-card").forEach((card) => {
        card.addEventListener("mouseenter", () => {
            const icon = card.querySelector(".card-icon");
            if (!icon) return;
            icon.style.animation = "none";
            void icon.offsetWidth; // reset
            icon.style.animation = "pulse-glow 1s infinite";
        });
        card.addEventListener("mouseleave", () => {
            const icon = card.querySelector(".card-icon");
            if (!icon) return;
            icon.style.animation = "none";
        });
    });
}

// ==============================
// PARALLAX SCROLL
// ==============================
function setupParallax() {
    window.addEventListener("scroll", () => {
        const scrolled = window.pageYOffset;
        document.querySelectorAll(".orb").forEach((orb, index) => {
            const speed = (index + 1) * 0.05;
            orb.style.transform = `translateY(${scrolled * speed}px)`;
        });
    });
}

// ==============================
// INIT
// ==============================
document.addEventListener("DOMContentLoaded", () => {
    // particles
    createParticles();

    // counters
    document.querySelectorAll(".stat-number").forEach((counter) => {
        const target = +counter.dataset.target;
        animateCounter(counter, target, 3000);
    });

    // hover glow
    setupHoverGlow();

    // parallax
    setupParallax();
});
