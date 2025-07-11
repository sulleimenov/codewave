import { defineConfig } from 'vite'
import { fileURLToPath } from 'node:url'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
	server: {
		hmr: {
			host: 'localhost',
			protocol: 'ws',
			overlay: false
		},
		host: '0.0.0.0',
		port: 5173,
		// ⬇️ Добавлено для поддержки history mode
		fs: {
			strict: false
		},
		historyApiFallback: true
	},
	plugins: [
		vue({
			template: {
				transformAssetUrls: {
					base: null,
					includeAbsolute: false
				}
			}
		}),
		laravel({
			input: ['resources/css/main.css', 'resources/js/app.js'],
			refresh: true
		})
	],
	resolve: {
		alias: {
			vue: 'vue/dist/vue.esm-bundler.js',
			'@': fileURLToPath(new URL('./resources/js/src', import.meta.url))
		}
	}
})
