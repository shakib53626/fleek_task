import "./bootstrap";
import App from './App.vue'
import router from './router'
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import ElementPlus from 'element-plus'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'

import 'element-plus/dist/index.css'

const app = createApp(App);
const pinia = createPinia()
app.use(pinia);
pinia.use(piniaPluginPersistedstate)

app.use(router);
app.use(ElementPlus);
app.mount('#app')
