import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import router from '../src/routes/index'
import '@fortawesome/fontawesome-free/css/all.css';
import '@fortawesome/fontawesome-free/js/all.js';
import store from '../src/store'; 


const app = createApp(App)
app.use(router)
.use(store) // Use the store

.mount('#app')
