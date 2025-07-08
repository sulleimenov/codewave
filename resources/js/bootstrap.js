import axios from 'axios'
window.axios = axios

window.axios.defaults.withCredentials = true
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

let token = document.head.querySelector('meta[name="csrf-token"]')
if (token) {
	window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content
} else {
	console.error('CSRF token not found')
}

window.axios.interceptors.request.use(
	(config) => {
		const authToken = localStorage.getItem('authToken')
		if (authToken) {
			config.headers.Authorization = `Bearer ${authToken}`
		}
		return config
	},
	(error) => {
		return Promise.reject(error)
	}
)
