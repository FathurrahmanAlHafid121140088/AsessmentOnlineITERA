window.addEventListener("DOMContentLoaded", (event) => {
    const navbar = document.getElementById("mainNav");
    const navbarToggler = document.querySelector(".navbar-toggler");
    const navLinks = document.querySelectorAll("#navbarResponsive .nav-link");

    function handleScroll() {
        if (window.scrollY > 0) {
            navbar.classList.add("navbar-shrink");
            navbar.classList.add("navbar-scrolled");
            navbar.classList.remove("navbar-transparent");
        } else {
            navbar.classList.remove("navbar-shrink");
            navbar.classList.remove("navbar-scrolled");
            navbar.classList.add("navbar-transparent");
        }
    }

    // on page load
    handleScroll();

    // on scroll
    window.addEventListener("scroll", handleScroll);

    // collapse hamburger menu on click
    navLinks.forEach((link) => {
        link.addEventListener("click", () => {
            if (window.getComputedStyle(navbarToggler).display !== "none") {
                navbarToggler.click();
            }
        });
    });

    // Bootstrap scrollspy
    new bootstrap.ScrollSpy(document.body, {
        target: "#mainNav",
        rootMargin: "0px 0px -40%",
    });
});
