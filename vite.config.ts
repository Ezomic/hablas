import inertia from '@inertiajs/vite';
import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import { defineConfig } from 'vite';
import { VitePWA } from 'vite-plugin-pwa';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.ts'],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        inertia(),
        tailwindcss(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        wayfinder({
            formVariants: true,
        }),
        VitePWA({
            registerType: 'autoUpdate',
            // Laravel renders app.blade.php server-side per request rather
            // than a Vite-templated index.html, so there's no HTML for
            // 'auto' injection to modify — register the SW ourselves via
            // virtual:pwa-register/vue in app.ts instead.
            injectRegister: false,
            // Web push (THI-300) needs a `push`/`notificationclick` listener
            // in the service worker itself. generateSW's `workbox.importScripts`
            // exists but the vite-plugin-pwa maintainers explicitly steer
            // custom SW logic toward injectManifest instead — it hands over
            // authoring the SW file (resources/sw-src/sw.ts), and the plugin
            // just injects the precache manifest into it, rather than
            // generating the whole file from workbox config as before.
            strategies: 'injectManifest',
            srcDir: 'resources/sw-src',
            filename: 'sw.ts',
            // Laravel serves static files from public/, but laravel-vite-plugin
            // builds assets into public/build/ — a service worker registered
            // from there could only ever control /build/* by default (browsers
            // scope a SW's max reach to its own directory). Writing sw.js to
            // public/ directly instead lets it control the whole app. base/scope
            // must be overridden too — they otherwise default to Vite's own
            // base (/build/), which is only where the file is *served from*
            // for JS/CSS assets, not where the plugin wrote sw.js itself.
            outDir: 'public',
            base: '/',
            scope: '/',
            manifest: {
                name: 'Hablas',
                short_name: 'Hablas',
                description: 'Spanish/Portuguese learning app',
                theme_color: '#0a0a0a',
                background_color: '#0a0a0a',
                display: 'standalone',
                start_url: '/dashboard',
                // Without these the PWA install prompt has no icon at all.
                // "maskable" lets Android crop to its own shape without
                // clipping the glyph; "any" keeps the rounded square intact
                // everywhere else.
                icons: [
                    {
                        src: '/pwa-192.png',
                        sizes: '192x192',
                        type: 'image/png',
                        purpose: 'any',
                    },
                    {
                        src: '/pwa-512.png',
                        sizes: '512x512',
                        type: 'image/png',
                        purpose: 'any',
                    },
                    {
                        src: '/pwa-maskable-512.png',
                        sizes: '512x512',
                        type: 'image/png',
                        purpose: 'maskable',
                    },
                ],
            },
            injectManifest: {
                // Precache only the built static assets (JS/CSS/fonts) so the
                // app shell can boot offline — server-rendered page HTML is
                // handled separately via runtime caching in sw.ts, since each
                // route's HTML embeds page-specific Inertia props rather than
                // being a single static index.html a typical SPA would have.
                globPatterns: ['**/*.{js,css,woff,woff2}'],
            },
        }),
    ],
});
