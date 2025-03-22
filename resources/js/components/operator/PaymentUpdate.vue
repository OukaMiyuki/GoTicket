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
                // .listen('App\\Events\\PaymentUpdated', (data) => {
                //     console.log("Received event data:", data);
                //     if (String(data.invoiceId) === String(this.invoiceId)) {
                //         console.log("Invoice ID matched! Triggering success.");
                //         this.handlePaymentSuccess();
                //     } else {
                //         console.log("Invoice ID did not match.");
                //     }
                // });
                // .listen('App.Events.PaymentUpdated', (data) => {
                //     console.log("Received event data:", data);
                //     if (data.invoiceId == this.invoiceId) {
                //         console.log("Invoice ID matched! Triggering success.");
                //         this.handlePaymentSuccess();
                //     } else {
                //         console.log("Invoice ID did not match.");
                //     }
                // });
                .listen('.payment.updated', (data) => {
                    console.log("Received event data:", data);
                    if (data.invoiceId == this.invoiceId) {
                        console.log("Invoice ID matched! Triggering success.");
                        this.handlePaymentSuccess();
                    } else {
                        console.log("Invoice ID did not match.");
                    }
                });
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


<!-- <template>
    <div></div>
  </template>

  <script>
  import Echo from 'laravel-echo';
  import Pusher from 'pusher-js';
  import Swal from 'sweetalert2';

  window.Pusher = Pusher;

  export default {
    props: {
      invoiceId: {
        type: [Number, String],
        required: true
      }
    },
    mounted() {
      console.log("Payment Update Vue component mounted with invoiceId:", this.invoiceId);
      this.setupEcho();
    },
    methods: {
      setupEcho() {
        // Configure Echo using your Pusher credentials
        const echo = new Echo({
          broadcaster: 'pusher',
          key: 'a0f8e18348832696e61b', // replace with your key
          cluster: 'ap1',              // replace with your cluster
          forceTLS: true,
        });

        // Listen on the private channel and for the custom broadcast event name.
        echo.private('payment-channel')
          .listen('.payment.updated', (e) => {
            console.log("Received event data:", e);
            // Ensure we extract the invoiceId correctly; sometimes it may be nested.
            const receivedInvoiceId = e.invoiceId || (e.data && e.data.invoiceId);
            if (String(receivedInvoiceId) === String(this.invoiceId)) {
              console.log("Invoice ID matched! Triggering success.");
              this.handlePaymentSuccess();
            } else {
              console.log("Invoice ID did not match.");
            }
          });
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
  </script> -->
