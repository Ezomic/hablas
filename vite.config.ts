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
                icons: [],
            },
            workbox: {
                // Precache only the built static assets (JS/CSS/fonts) so the
                // app shell can boot offline — server-rendered page HTML is
                // handled separately via runtime caching below, since each
                // route's HTML embeds page-specific Inertia props rather than
                // being a single static index.html a typical SPA would have.
                globPatterns: ['**/*.{js,css,woff,woff2}'],
                // generateSW assumes a single-page app by default and installs
                // a NavigationRoute that intercepts every navigation FIRST,
                // trying to serve a precached "index.html" — this app has no
                // such file (every route gets its own server-rendered HTML),
                // so that default route always fails and, worse, runs ahead
                // of the runtimeCaching rule below, since Workbox matches
                // routes in registration order. Disabling it lets our own
                // NetworkFirst rule actually handle navigation requests.
                navigateFallback: null,
                runtimeCaching: [
                    {
                        // Cache the last-seen render of each visited page so
                        // it can be revisited offline, falling back to the
                        // network first since page data should stay fresh
                        // whenever connectivity is available.
                        urlPattern: ({ request }) => request.mode === 'navigate',
                        handler: 'NetworkFirst',
                        options: {
                            cacheName: 'pages',
                            networkTimeoutSeconds: 3,
                        },
                    },
                ],
            },
        }),
    ],
});
