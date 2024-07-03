import { createApp } from 'vue';
import { createPinia } from 'pinia'; // Import Pinia
import App from './App.vue';
import router from './router'; // Import the router instance

import './assets/main.css'

const app = createApp(App);
const pinia = createPinia();

app.use(pinia); // Install Pinia before mounting the app
app.use(router); // Install the router instance before mounting the app
app.mount('#app');
