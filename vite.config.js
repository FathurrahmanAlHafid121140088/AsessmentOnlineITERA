import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    server: {
        host: "127.0.0.1",
    },
    plugins: [
        laravel({
            // Multiple Entry Points - Each page type has its own CSS bundle
            // This prevents class name conflicts between different sections
            input: [
                // ==========================================
                // CSS BUNDLES - Consolidated by Page Type
                // ==========================================

                // 1. Public Pages (home, karir-home)
                "resources/css/app-public.css",

                // 2. Mental Health Pages
                "resources/css/app-mh-home.css", // mental-health.blade.php (landing)
                "resources/css/app-mental-health.css", // isi-data-diri.blade.php
                "resources/css/app-mh-hasil.css", // hasil.blade.php
                "resources/css/app-mh-quiz.css", // kuesioner.blade.php

                // 3. Admin Pages
                "resources/css/app-admin.css", // admin.blade.php (login)
                "resources/css/app-admin-dashboard.css", // admin-home.blade.php

                // 4. User Dashboard (user-mental-health)
                "resources/css/app-user-dashboard.css",

                // 5. Authentication Pages (login, register, lupa-password)
                "resources/css/app-auth.css",

                // 6. Karir/Career Pages (karir-*)
                "resources/css/app-karir.css",

                // ==========================================
                // JAVASCRIPT FILES
                // ==========================================
                // Note: Hanya include file JS yang ada di resources/js/
                // File JS lainnya sudah inline di blade atau di public/js/
                "resources/js/app.js",
            ],
            refresh: true,
        }),
    ],
});
