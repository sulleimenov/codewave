import { defineConfig } from 'vite';
import { fileURLToPath } from 'node:url'
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'

export default defineConfig({
		server: {
			hmr: {
				host: 'localhost',
			}
		},
    plugins: [
				vue(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
		resolve: {
			alias: {
					vue: "vue/dist/vue.esm-bundler.js",
					'@': fileURLToPath(new URL('./resources/js/src', import.meta.url)),
			},
		},
});
