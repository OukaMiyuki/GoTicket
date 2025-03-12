<script>
import Pusher from 'pusher-js';

export default {
    props: {
        invoiceId: {
            type: [Number, String],
            required: true
        }
    },
    mounted() {
        this.setupPusher();
    },
    methods: {
        setupPusher() {
            const pusher = new Pusher('a0f8e18348832696e61b', {
                cluster: 'ap1',
                forceTLS: true
            });

            const channel = pusher.subscribe('payment-channel');

            channel.bind('App\\Events\\PaymentUpdated', (data) => {
                if (data.invoiceId == this.invoiceId) {
                    this.handlePaymentSuccess();
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
            window.location.href = url;
        }
  }
};
</script>
