import './bootstrap';
import { createApp } from 'vue';
import PacketList from './components/operator/PacketList.vue';
import CartList from './components/operator/OperatorCart.vue';
import CheckOut from './components/operator/OperatorCheckout.vue';
import PaymentUpdate from './components/operator/PaymentUpdate.vue';
import Alpine from 'alpinejs';
import axios from 'axios';

window.Alpine = Alpine;
Alpine.start();

axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// const app = createApp({});
// const app1 = createApp({});

// app.component('packet-list', PacketList);
// app1.component('cart-list', CartList);

// app.mount('#operator-packet-transaction');
// app1.mount('#operator-cart');

// const currentPath = window.location.pathname;
// const app = createApp({});
// if (currentPath.startsWith('/operator/transaction')) {
//     app.component('packet-list', PacketList);
//     app.mount('#operator-packet-transaction');
// }
const userRole = window.Laravel?.role;

if(userRole === 'playground_operator' || userRole === 'visitor_member'){
    const app1 = createApp({});
    app1.component('cart-list', CartList);
    app1.mount('#operator-cart');
}

const app3 = createApp({});
app3.component('payment-update', PaymentUpdate);
app3.mount('#operator-payment-update');

document.addEventListener("DOMContentLoaded", function () {
    const currentPath = window.location.pathname;

    if (document.getElementById('operator-packet-transaction') && currentPath.startsWith('/operator/transaction')) {
        const app = createApp({});
        app.component('packet-list', PacketList);
        app.mount('#operator-packet-transaction');
    } else if(document.getElementById('operator-packet-checkout') && currentPath.startsWith('/operator/transaction/checkout')){
        const app2 = createApp({});
        app2.component('packet-checkout-list', CheckOut);
        app2.mount('#operator-packet-checkout');
    }
    // else if(document.getElementById('operator-payment-update') && currentPath.startsWith('/operator/transaction/checkout/pay')){
    //     const app3 = createApp({});
    //     app3.component('payment-update', PaymentUpdate);
    //     app3.mount('#operator-payment-update');
    // }

    // if (document.getElementById('operator-payment-update') && /^\/operator\/transaction\/checkout\/pay\/\d+$/.test(currentPath)) {
    //     console.log("Mounting PaymentUpdate component");
    //     const app3 = createApp({});
    //     app3.component('payment-update', PaymentUpdate);
    //     app3.mount('#operator-payment-update');
    // }

    // if (document.getElementById('operator-payment-update')) {
    //     console.log("Element exists!");

    //     if (currentPath.startsWith('/operator/transaction/checkout/pay')) {
    //         console.log("Starts with '/operator/transaction/checkout/pay'");
    //         if (/\d+$/.test(currentPath.split('/').pop())) {
    //             console.log("Invoice ID is found:", currentPath.split('/').pop());
    //             const app3 = createApp({});
    //             app3.component('payment-update', PaymentUpdate);
    //             app3.mount('#operator-payment-update');
    //         }
    //     }
    // }
});
