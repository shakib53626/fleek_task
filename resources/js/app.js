import "./bootstrap";
import { createApp } from 'vue'
import App from './App.vue'
import { createPinia } from 'pinia'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
import router from './router'

const app = createApp(App);
const pinia = createPinia()
app.use(pinia);
pinia.use(piniaPluginPersistedstate)

app.use(router);
app.mount('#app')
