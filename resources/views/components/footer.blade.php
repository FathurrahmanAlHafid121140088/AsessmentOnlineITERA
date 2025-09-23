<footer class="footer">
    <div class="container-footer" style="max-width: 1200px; margin: auto; min-height: auto; padding: 0 1rem;">
        <div class="footer-grid"
            style="
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        justify-items: start;
        text-align: left;
     ">
            <div class="mb-8">
                <h3>PPSDM ITERA</h3>
                <p>“Smart, Friendly, and Forest Campus”.</p>
                <div class="social-icons" style="display: flex; margin: 1rem 0;">
                    <a href="#" aria-label="Twitter"
                        style="color: #ffffff; text-decoration: none; position: relative; overflow: hidden; margin-left: 0; margin-right: 8px;">
                        <span
                            style="position: absolute; inset: 0; background: linear-gradient(45deg, #4361ee, #4cc9f0); opacity: 0; transition: opacity 0.3s ease; z-index: 1;"></span>
                        <i class="fab fa-twitter" style="position: relative; z-index: 2;"></i>
                    </a>
                    <a href="#" aria-label="Facebook"
                        style="color: #ffffff; text-decoration: none; position: relative; overflow: hidden; margin: 0 8px;">
                        <span
                            style="position: absolute; inset: 0; background: linear-gradient(45deg, #4361ee, #4cc9f0); opacity: 0; transition: opacity 0.3s ease; z-index: 1;"></span>
                        <i class="fab fa-facebook-f" style="position: relative; z-index: 2;"></i>
                    </a>
                    <a href="#" aria-label="Instagram"
                        style="color: #ffffff; text-decoration: none; position: relative; overflow: hidden; margin: 0 8px;">
                        <span
                            style="position: absolute; inset: 0; background: linear-gradient(45deg, #4361ee, #4cc9f0); opacity: 0; transition: opacity 0.3s ease; z-index: 1;"></span>
                        <i class="fab fa-instagram" style="position: relative; z-index: 2;"></i>
                    </a>
                    <a href="#" aria-label="LinkedIn"
                        style="color: #ffffff; text-decoration: none; position: relative; overflow: hidden; margin: 0 8px;">
                        <span
                            style="position: absolute; inset: 0; background: linear-gradient(45deg, #4361ee, #4cc9f0); opacity: 0; transition: opacity 0.3s ease; z-index: 1;"></span>
                        <i class="fab fa-linkedin-in" style="position: relative; z-index: 2;"></i>
                    </a>
                    <a href="#" aria-label="Youtube"
                        style="color: #ffffff; text-decoration: none; position: relative; overflow: hidden; margin: 0 8px;">
                        <span
                            style="position: absolute; inset: 0; background: linear-gradient(45deg, #4361ee, #4cc9f0); opacity: 0; transition: opacity 0.3s ease; z-index: 1;"></span>
                        <i class="fab fa-youtube" style="position: relative; z-index: 2;"></i>
                    </a>
                </div>

            </div>
            <div class="mb-8">
                <h4>Menu Utama</h4>
                <ul style="list-style: none; padding: 0;">
                    <li>
                        <a href="/home" class="animated-link">Home</a>
                    </li>
                    <li>
                        <a href="/mental-health" class="animated-link">Mental Health</a>
                    </li>
                    <li>
                        <a href="/karir-home" class="animated-link">Peminatan Karir</a>
                    </li>
                </ul>
            </div>


            <div class="mb-8">
                <h4>Contact Info</h4>
                <ul style="list-style: none; padding: 0;">
                    <li class="flex" style="align-items: center;">
                        <i class="fas fa-map-marker-alt" style="margin-right: 8px; color: #4cc9f0;"></i>
                        <span>Jl. Terusan Ryacudu, Jati Agung, Lampung Selatan.</span>
                        <span>Gedung GKU-1, Ruang 205 B</span>
                    </li>
                    <li class="flex" style="align-items: center;">
                        <i class="fas fa-phone-alt" style="margin-right: 8px; color: #4cc9f0;"></i>
                        <span>0851-5087-6464</span>
                    </li>
                    <li class="flex" style="align-items: center;">
                        <i class="fas fa-envelope" style="margin-right: 8px; color: #4cc9f0;"></i>
                        <span>ppsdm@itera.ac.id</span>
                    </li>
                </ul>
            </div>
        </div>

        <div
            style="border-top: 1px solid #4b4b4b; margin-top: 2rem; padding-top: 1rem; display: flex; flex-direction: column; align-items: center;">
            <p style="margin: 0; opacity: 0.7;">&copy; 2025 PPSDM ITERA. All rights reserved.</p>
            <div class="developer-container">
                <span class="developed-by">Developed By:</span>
                <div style="display: flex"> <a href="https://www.linkedin.com/in/fathurrahman-al-hafid-a21a7a246/"
                        class="developer-badge">
                        Fathurrahman Al Hafid
                    </a>
                    <a href="#" class="developer-badge">
                        Riksan Cahyowadi
                    </a>
                </div>

            </div>
        </div>
    </div>
</footer>

<script>
    // Simple animation on scroll
    document.addEventListener('DOMContentLoaded', function() {
        const socialIcons = document.querySelectorAll('.social-icons a');

        socialIcons.forEach((icon, index) => {
            // Initial animation
            setTimeout(() => {
                icon.style.transform = 'translateY(10px)';
                icon.style.opacity = '0';
                icon.animate(
                    [{
                            transform: 'translateY(10px)',
                            opacity: 0
                        },
                        {
                            transform: 'translateY(0)',
                            opacity: 1
                        }
                    ], {
                        duration: 500,
                        delay: index * 100,
                        fill: 'forwards',
                        easing: 'ease-out'
                    }
                );
            }, 300);

            // Hover effect enhancement
            icon.addEventListener('mouseenter', function() {
                this.animate(
                    [{
                            transform: 'translateY(0)'
                        },
                        {
                            transform: 'translateY(-5px)'
                        }
                    ], {
                        duration: 200,
                        fill: 'forwards'
                    }
                );
            });

            icon.addEventListener('mouseleave', function() {
                this.animate(
                    [{
                            transform: 'translateY(-5px)'
                        },
                        {
                            transform: 'translateY(0)'
                        }
                    ], {
                        duration: 200,
                        fill: 'forwards'
                    }
                );
            });
        });
    });
    window.addEventListener("load", updateSidebarHeight);
    window.addEventListener("resize", updateSidebarHeight);

    function updateSidebarHeight() {
        const footer = document.getElementById("main-footer");
        const sidebar = document.querySelector(".sidebar");

        if (footer && sidebar) {
            const footerHeight = footer.offsetHeight;
            sidebar.style.height = `calc(100vh - ${footerHeight}px)`;
        }
    }
</script>
