// Simple animation on scroll
document.addEventListener("DOMContentLoaded", function () {
    const socialIcons = document.querySelectorAll(".social-icons a");

    socialIcons.forEach((icon, index) => {
        // Initial animation
        setTimeout(() => {
            icon.style.transform = "translateY(10px)";
            icon.style.opacity = "0";
            icon.animate(
                [
                    { transform: "translateY(10px)", opacity: 0 },
                    { transform: "translateY(0)", opacity: 1 },
                ],
                {
                    duration: 500,
                    delay: index * 100,
                    fill: "forwards",
                    easing: "ease-out",
                }
            );
        }, 300);

        // Hover effect enhancement
        icon.addEventListener("mouseenter", function () {
            this.animate(
                [
                    { transform: "translateY(0)" },
                    { transform: "translateY(-5px)" },
                ],
                {
                    duration: 200,
                    fill: "forwards",
                }
            );
        });

        icon.addEventListener("mouseleave", function () {
            this.animate(
                [
                    { transform: "translateY(-5px)" },
                    { transform: "translateY(0)" },
                ],
                {
                    duration: 200,
                    fill: "forwards",
                }
            );
        });
    });
});
