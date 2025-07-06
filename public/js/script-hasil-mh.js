// Add interactive effects
document.querySelectorAll(".range-item").forEach((item) => {
    item.addEventListener("mouseenter", function () {
        this.style.transform = "translateY(-6px) scale(1.02)";
    });

    item.addEventListener("mouseleave", function () {
        if (!this.classList.contains("active")) {
            this.style.transform = "";
        }
    });
});

// Category hover effects
document.querySelectorAll(".score-category").forEach((category) => {
    category.addEventListener("mouseenter", function () {
        this.style.transform = "scale(1.05)";
        this.style.boxShadow = "0 8px 16px rgba(0, 0, 0, 0.1)";
    });

    category.addEventListener("mouseleave", function () {
        this.style.transform = "scale(1)";
        this.style.boxShadow = "";
    });
});

// Animate score value on load
window.addEventListener("load", function () {
    const scoreValue = document.querySelector(".score-value");
    const finalValue = parseInt(scoreValue.textContent);
    let currentValue = 0;
    const increment = finalValue / 100;

    const timer = setInterval(() => {
        currentValue += increment;
        if (currentValue >= finalValue) {
            currentValue = finalValue;
            clearInterval(timer);
        }
        scoreValue.textContent = Math.floor(currentValue);
    }, 20);
});
