import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    server: {
        host: "127.0.0.1",
    },
    plugins: [
        laravel({
            // Daftarkan SEMUA file CSS dan JS Anda di sini
            // agar Vite tahu cara menanganinya.
            input: [
                // CSS Files
                "resources/css/app.css",
                "resources/css/karir-admin.css",
                "resources/css/karir-form.css",
                "resources/css/karir-hitung.css",
                "resources/css/karir-home.css",
                "resources/css/main-interpretasi.css",
                "resources/css/main-mh.css",
                "resources/css/main.css",
                "resources/css/style-admin-home.css",
                "resources/css/style-admin.css",
                "resources/css/style-footer.css",
                "resources/css/style-hasil-mh.css",
                "resources/css/style-home-mh.css",
                "resources/css/style-login.css",
                "resources/css/style-lupa-password.css",
                "resources/css/style-mental-health.css",
                "resources/css/style-register.css",
                "resources/css/style-user-mh.css",
                "resources/css/style.css",
                "resources/css/styleform.css",
                "resources/css/stylekuesioner.css",
                "resources/vendor/bootstrap/css/bootstrap.min.css",
                "resources/vendor/bootstrap-icons/bootstrap-icons.css",
                "resources/vendor/aos/aos.css",
                "resources/vendor/glightbox/css/glightbox.min.css",
                "resources/vendor/swiper/swiper-bundle.min.css",

                // JS Files
                "resources/js/app.js",
                "resources/js/karir-form.js",
                "resources/js/karir-hitung.js",
                "resources/js/script-admin-mh.js",
                "resources/js/script-admin.js",
                "resources/js/script-datadiri.js",
                "resources/js/script-footer.js",
                "resources/js/script-hasil-mh.js",
                "resources/js/script-karir-datadiri.js",
                "resources/js/script-login.js",
                "resources/js/script-lupa-password.js",
                "resources/js/script-quiz.js",
                "resources/js/script-register.js",
                "resources/js/script-user-mh.js",
                "resources/js/script.js",
            ],
            refresh: true,
        }),
    ],
});
