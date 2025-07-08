import { createApp } from 'vue'
import { createPinia } from 'pinia'
// import { autoAnimatePlugin } from '@formkit/auto-animate/vue'

import App from './src/App.vue'
import router from '@/router'

import '../css/main.css'
import IsAdmin from './src/components/ui/isAdmin'

const pinia = createPinia()
const app = createApp(App)

// app.use(autoAnimatePlugin)
app.use(pinia)
app.use(router)
app.directive('admin', IsAdmin);
app.mount('#app')
