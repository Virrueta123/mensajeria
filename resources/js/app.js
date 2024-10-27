import './bootstrap';


import {
    createApp
} from 'vue';


// inicializando la vue
const app = createApp({});

app.component('principal', require('./components/principal.vue').default);

app.mount('#principal');