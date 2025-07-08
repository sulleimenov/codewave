/** @type {import('tailwindcss').Config} */
export default {
	purge: ['./resources/views/app.blade.php', './resources/js/src/**/*.{vue,js,ts,jsx,tsx}'],
  content: [],
  theme: {
    extend: {
			boxShadow: {
				1: '0 0px 0px 0px rgb(217 217 217 / 0.4), 0 0px 21px 0px rgb(217 217 217 / 0.4)'
			}
		}
  },
  plugins: []
}

