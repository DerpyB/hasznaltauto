import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'

const app = createApp(App)
app.use(createPinia())
app.use(router)
app.mount('#app')

import "./assets/css/bootstrap.min.css"
import "./assets/css/style.css"
import "./assets/css/animate.css"
import "./assets/css/owl.carousel.min.css"

createApp(App).use(router).mount("#app")
