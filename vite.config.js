import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            vue: "vue/dist/vue.esm-bundler.js",
        },
    },
    server: {
        proxy: {
          '/pusher': {
            target: 'http://localhost:6001',
            changeOrigin: true,
            ws: true,
          },
        },
    },
    build: {
        outDir: 'public/build',
        assetsDir: '',
        publicPath: '/build',
    },
});
