import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/js/scanner.js",
                "resources/js/test-scanner.js",
            ],
            refresh: true,
        }),
        tailwindcss(),
        vue(),
    ],
});
