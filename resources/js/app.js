import './bootstrap';
import { createApp } from 'vue';
import PacketList from './components/operator/PacketList.vue';
import CartList from './components/operator/OperatorCart.vue';
import Alpine from 'alpinejs';
import axios from 'axios';

window.Alpine = Alpine;
Alpine.start();

axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

const app = createApp({});
const app1 = createApp({});

app.component('packet-list', PacketList);
app1.component('cart-list', CartList);

app.mount('#app');
app1.mount('#app1');
