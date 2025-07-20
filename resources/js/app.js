import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './src/App.vue'
import router from '@/router'
import '../css/main.css'
import IsAdmin from './src/components/ui/isAdmin'
import Markdown from 'vue3-markdown-it'
import axios from 'axios'

const pinia = createPinia()

axios.defaults.baseURL = '/'
const app = createApp(App)
app.use(pinia)
app.use(router)
app.use(Markdown)
app.directive('admin', IsAdmin)
app.mount('#app')
