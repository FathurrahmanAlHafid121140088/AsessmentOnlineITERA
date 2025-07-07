// Create floating particles
function createParticles() {
    const particlesContainer = document.getElementById("particles");
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

// Animate counters with easing
function animateCounter(elementId, targetValue, duration = 2500) {
    const element = document.getElementById(elementId);
    const startValue = 0;
    const startTime = performance.now();

    function updateCounter(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);

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

// Enhanced card click handler
function handleCardClick(cardType) {
    const card = document.querySelector(`.${cardType}`);
    const button = card.querySelector(".card-button");

    button.style.transform = "scale(0.95)";

    setTimeout(() => {
        button.style.transform = "translateY(-5px) scale(1.03)";

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

// Hover glow
document.querySelectorAll(".admin-card").forEach((card) => {
    card.addEventListener("mouseenter", () => {
        const icon = card.querySelector(".card-icon");
        icon.style.animation = "none";
        void icon.offsetWidth; // reset
        icon.style.animation = "pulse-glow 1s infinite";
    });
    card.addEventListener("mouseleave", () => {
        const icon = card.querySelector(".card-icon");
        icon.style.animation = "none";
    });
});

// Parallax scroll
window.addEventListener("scroll", () => {
    const scrolled = window.pageYOffset;
    document.querySelectorAll(".orb").forEach((orb, index) => {
        const speed = (index + 1) * 0.05;
        orb.style.transform = `translateY(${scrolled * speed}px)`;
    });
});
document.addEventListener("DOMContentLoaded", () => {
    const counters = document.querySelectorAll(".stat-number");

    counters.forEach((counter) => {
        const target = +counter.dataset.target;
        const duration = 3000; // durasi 3 detik
        const step = Math.ceil(target / (duration / 20));
        let count = 0;

        function updateCounter() {
            count += step;
            if (count < target) {
                counter.innerText = count;
                setTimeout(updateCounter, 20);
            } else {
                counter.innerText = target;
            }
        }
        updateCounter();
    });
});
