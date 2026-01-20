import { createApp } from 'vue'
import App from './App.vue'
import './style.css'
import router from './router/router'
import ToastPlugin from 'vue-toast-notification'
import 'vue-toast-notification/dist/theme-sugar.css'

const app = createApp(App)

app.use(router)
app.use(ToastPlugin, {
  containerClass: 'toast-container',
})

app.mount('#app')