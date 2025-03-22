<template>
    <div></div>
</template>

<script>
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

export default {
    props: {
        invoiceId: {
            type: [Number, String],
            required: true
        }
    },
    mounted() {
        console.log("Payment Update Vue component is mounted test");
        this.setupPusher();
    },
    methods: {
        setupPusher() {
            const echo = new Echo({
                broadcaster: 'pusher',
                key: 'a0f8e18348832696e61b',
                cluster: 'ap1',
                forceTLS: true,
            });

            echo.connector.pusher.connection.bind('connected', () => {
                console.log('Connected to Pusher!');
            });

            echo.channel('payment-channel')
                .listen('App\\Events\\PaymentUpdated', (data) => {
                    console.log("Received event data:", data);
                    if (String(data.invoiceId) === String(this.invoiceId)) {
                        console.log("Invoice ID matched! Triggering success.");
                        this.handlePaymentSuccess();
                    } else {
                        console.log("Invoice ID did not match.");
                    }
                });
                // .listen('App.Events.PaymentUpdated', (data) => {
                //     console.log("Received event data:", data);
                //     if (data.invoiceId == this.invoiceId) {
                //         console.log("Invoice ID matched! Triggering success.");
                //         this.handlePaymentSuccess();
                //     } else {
                //         console.log("Invoice ID did not match.");
                //     }
                // });
                // .listen('.App.Events.PaymentUpdated', (data) => {
                //     console.log("Received event data:", data);
                //     if (data.invoiceId == this.invoiceId) {
                //         console.log("Invoice ID matched! Triggering success.");
                //         this.handlePaymentSuccess();
                //     } else {
                //         console.log("Invoice ID did not match.");
                //     }
                // });
        },
        handlePaymentSuccess() {
            Swal.fire({
                title: 'Payment Success',
                text: 'Your payment has been processed successfully.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                this.redirectUser();
            });
        },
        redirectUser() {
            const url = `/operator/transaction/invoice/ticket/${this.invoiceId}`;
            console.log("Redirecting to:", url);
            window.location.href = url;
        }
    }
};
</script>
