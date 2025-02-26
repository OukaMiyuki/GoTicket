import './bootstrap';
import { createApp } from 'vue';
import PacketList from './components/operator/PacketList.vue';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

const app = createApp({});
app.component('packet-list', PacketList);
app.mount('#app');
