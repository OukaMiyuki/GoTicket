<template>
    <div class="bs-stepper checkout-tab-steps">
        <!-- Wizard starts -->
        <div class="bs-stepper-header">
            <div class="step" data-target="#step-cart">
                <button type="button" class="step-trigger">
                    <span class="bs-stepper-box">
                        <i data-feather="shopping-cart" class="font-medium-3"></i>
                    </span>
                    <span class="bs-stepper-label">
                        <span class="bs-stepper-title">Cart</span>
                        <span class="bs-stepper-subtitle">Your Cart Items</span>
                    </span>
                </button>
            </div>
            <div class="line">
                <i data-feather="chevron-right" class="font-medium-2"></i>
            </div>
            <div class="step" data-target="#step-address">
                <button type="button" class="step-trigger">
                    <span class="bs-stepper-box">
                        <i data-feather="bookmark" class="font-medium-3"></i>
                    </span>
                    <span class="bs-stepper-label">
                        <span class="bs-stepper-title">Billing Information</span>
                        <span class="bs-stepper-subtitle">Enter Billing Information</span>
                    </span>
                </button>
            </div>
            <div class="line">
                <i data-feather="chevron-right" class="font-medium-2"></i>
            </div>
            <div class="step" data-target="#step-payment">
                <button type="button" class="step-trigger">
                    <span class="bs-stepper-box">
                        <i data-feather="credit-card" class="font-medium-3"></i>
                    </span>
                    <span class="bs-stepper-label">
                        <span class="bs-stepper-title">Payment</span>
                        <span class="bs-stepper-subtitle">Select Payment Method</span>
                    </span>
                </button>
            </div>
        </div>
        <!-- Wizard ends -->

        <div class="bs-stepper-content">
            <!-- Checkout Place order starts -->
            <div id="step-cart" class="content">
                <div id="place-order" class="list-view product-checkout">
                    <!-- Checkout Place Order Left starts -->
                    <div class="checkout-items">
                        <div v-if="cartItems.length > 0" v-for="item in cartItems" :key="item.id" class="card ecommerce-card">
                            <div class="item-img">
                                <a href="app-ecommerce-details.html">
                                    <img :src="`/storage/${item.packet.galleries[0]?.image || 'default.jpg'}`" alt="img-placeholder" />
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="item-name">
                                    <h6 class="mb-0"><a href="app-ecommerce-details.html" class="text-body">{{ item.packet.packet_name }}</a></h6>
                                    <span class="item-company">By <a href="javascript:void(0)" class="company-name">{{ item.location.location_name }}</a></span>
                                    <div class="item-rating">
                                        <!-- <ul class="unstyled-list list-inline">
                                            <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i></li>
                                            <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i></li>
                                            <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i></li>
                                            <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i></li>
                                            <li class="ratings-list-item"><i data-feather="star" class="unfilled-star"></i></li>
                                        </ul> -->
                                    </div>
                                </div>
                                <!-- <span class="text-success mb-1">In Stock</span> -->
                                <div class="item-quantity">
                                    <span class="quantity-title">Qty:</span>
                                    <div class="input-group quantity-counter-wrapper">
                                        <input type="text" class="quantity-counter" v-model="item.qty" @input="updateQuantity(item)" :data-item-id="item.id" :ref="'qty-input-' + item.id" />
                                    </div>
                                </div>
                                <!-- <span class="delivery-date text-muted">Delivery by, Wed Apr 25</span>
                                <span class="text-success">17% off 4 offers Available</span> -->
                            </div>
                            <div class="item-options text-center">
                                <div class="item-wrapper">
                                    <div class="item-cost">
                                        <h4 class="item-price">{{ formatIDR(item.sub_total) }}</h4>
                                        <p class="card-text shipping">
                                            <!-- <span class="badge badge-pill badge-light-success">Free Shipping</span> -->
                                        </p>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-light mt-1 remove-wishlist" @click="removeFromCart(item.id)">
                                    <span data-feather="x" class="align-middle mr-25">x</span>
                                    <span>Remove</span>
                                </button>
                                <!-- <button type="button" class="btn btn-primary btn-cart move-cart">
                                    <i data-feather="heart" class="align-middle mr-25"></i>
                                    <span class="text-truncate">Add to Wishlist</span>
                                </button> -->
                            </div>
                        </div>
                        <div v-else class="mt-1">
                            <p>Your cart is empty.</p>
                        </div>
                    </div>
                    <!-- Checkout Place Order Left ends -->

                    <!-- Checkout Place Order Right starts -->
                    <div class="checkout-options">
                        <div class="card">
                            <div class="card-body">
                                <!-- <label class="section-label mb-1">Options</label> -->
                                <!-- <div class="coupons input-group input-group-merge">
                                    <input type="text" class="form-control" placeholder="Coupons" aria-label="Coupons" aria-describedby="input-coupons" />
                                    <div class="input-group-append">
                                        <span class="input-group-text text-primary" id="input-coupons">Apply</span>
                                    </div>
                                </div> -->
                                <!-- <hr /> -->
                                <div class="price-details">
                                    <h6 class="price-title">Price Details</h6>
                                    <ul class="list-unstyled">
                                        <li class="price-detail">
                                            <div class="detail-title">Sub Total</div>
                                            <div class="detail-amt">{{ formatIDR(totalPrice) }}</div>
                                        </li>
                                        <li class="price-detail">
                                            <div class="detail-title">Diskon</div>
                                            <div class="detail-amt text-success"><strong>-</strong></div>
                                        </li>
                                        <li class="price-detail">
                                            <div class="detail-title">Nominal Diskon</div>
                                            <div class="detail-amt discount-amt text-success">{{ formatIDR(0) }}</div>
                                        </li>
                                        <li class="price-detail">
                                            <div class="detail-title">Pajak PPN</div>
                                            <a href="javascript:void(0)" class="detail-amt text-primary"><strong>{{ tax_value }}%</strong></a>
                                        </li>
                                        <li class="price-detail">
                                            <div class="detail-title">Nominal Pajak</div>
                                            <div class="detail-amt discount-amt">{{ formatIDR(countTax(totalPrice, tax_value)) }}</div>
                                        </li>
                                    </ul>
                                    <hr />
                                    <ul class="list-unstyled">
                                        <li class="price-detail">
                                            <div class="detail-title detail-total">Total</div>
                                            <div class="detail-amt font-weight-bolder">{{ countTotal(totalPrice) }}</div>
                                        </li>
                                    </ul>
                                    <button type="button" class="btn btn-primary btn-block btn-next place-order" :disabled="totalItems === 0">Proses Checkout</button>
                                </div>
                            </div>
                        </div>
                        <!-- Checkout Place Order Right ends -->
                    </div>
                </div>
                <!-- Checkout Place order Ends -->
            </div>
            <!-- Checkout Customer Address Starts -->
            <div id="step-address" class="content">
                <form id="checkout-address" class="list-view product-checkout">
                    <!-- Checkout Customer Address Left starts -->
                    <div class="card">
                        <div class="card-header flex-column align-items-start">
                            <h4 class="card-title">Bill Information</h4>
                            <p class="card-text text-muted mt-25">Be sure to check "Deliver to this address" when you have finished</p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group mb-2">
                                        <label for="checkout-name">Full Name:<span class="text-danger"><strong>*</strong></span></label>
                                        <input type="text" id="checkout-name" class="form-control" :class="{ 'is-invalid': errors.fullName }" name="fname" v-model="formData.fullName" placeholder="John Doe" required />
                                        <div class="invalid-feedback">{{ errors.fullName }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group mb-2">
                                        <label for="checkout-number">Mobile Number (Opsional):</label>
                                        <input type="number" id="checkout-number" class="form-control" :class="{ 'is-invalid': errors.mobileNumber }" name="mnumber" v-model="formData.mobileNumber" placeholder="08XXXXXXXXXXX" />
                                        <div class="invalid-feedback">{{ errors.mobileNumber }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group mb-2">
                                        <label for="email">Email (Opsional):</label>
                                        <input type="email" id="email" class="form-control" :class="{ 'is-invalid': errors.email }" name="email" v-model="formData.email" placeholder="johndoe@example.com" />
                                        <div class="invalid-feedback">{{ errors.email }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group mb-2">
                                        <label for="address">Address (Opsional):</label>
                                        <input type="text" id="address" class="form-control" :class="{ 'is-invalid': errors.address }" name="address" v-model="formData.address" placeholder="Input address" />
                                        <div class="invalid-feedback">{{ errors.address }}</div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group mb-2">
                                        <label for="note">Bill Note (Opsional):</label>
                                        <textarea class="form-control"  :class="{ 'is-invalid': errors.note }" id="note" name="note" rows="3" v-model="formData.note" placeholder="Enter bill note"></textarea>
                                        <div class="invalid-feedback">{{ errors.note }}</div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="button" class="btn btn-primary btn-next delivery-address" :disabled="isButtonDisabled">Simpan & Lanjutkan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="customer-card">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Syarat dan Ketentuan</h4>
                            </div>
                            <div class="card-body actions">
                                <p class="card-text mb-0" style="font-size: .9rem;">
                                    Dengan membeli tiket melalui platform ini, Anda menyetujui bahwa semua pembelian bersifat final dan tidak dapat dibatalkan kecuali dinyatakan lain oleh penyelenggara. Tiket hanya berlaku untuk tanggal dan tempat yang tertera, serta harus ditunjukkan saat masuk. Pembayaran harus dilakukan sesuai metode yang tersedia, dan keterlambatan dapat menyebabkan pembatalan otomatis. Kami tidak bertanggung jawab atas pembatalan akibat force majeure. Semua data pribadi akan dijaga sesuai kebijakan privasi. Dengan menyelesaikan pembelian, Anda menyetujui seluruh syarat dan ketentuan yang berlaku.
                                </p>
                                <button type="button" class="btn btn-primary btn-block btn-next delivery-address mt-2">
                                    Baca Syarat dan Ketentuan lebih lanjut
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div id="step-payment" class="content">
                <form id="checkout-payment" class="list-view product-checkout" onsubmit="return false;" @submit.prevent="submitPayment">
                    <div class="payment-type">
                        <div class="card">
                            <div class="card-header flex-column align-items-start">
                                <h4 class="card-title">Payment options</h4>
                                <p class="card-text text-muted mt-25">Be sure to click on correct payment option</p>
                            </div>
                            <div class="card-body">
                                <ul class="other-payment-options list-unstyled">
                                    <li class="py-50">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="tunai" name="paymentMethod" value="tunai" v-model="formData.paymentMethod" class="custom-control-input" />
                                            <i class="fa-solid fa-money-bill"></i>&nbsp;
                                            <label class="custom-control-label" for="tunai"> Pembayaran Tunai (Operator Only) </label>
                                        </div>
                                    </li>
                                    <li class="py-50">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="qris" name="paymentMethod" value="qris" v-model="formData.paymentMethod" class="custom-control-input" />
                                            <i class="fa-solid fa-qrcode"></i>&nbsp;
                                            <label class="custom-control-label" for="qris"> Online Qris </label>
                                        </div>
                                    </li>
                                    <li class="py-50">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="va_nobu" name="paymentMethod" value="va_nobu" v-model="formData.paymentMethod" class="custom-control-input" />
                                            <i class="fa-solid fa-credit-card"></i>&nbsp;
                                            <label class="custom-control-label" for="va_nobu"> Transfer Virtual Account Bank NOBU </label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="amount-payable checkout-options">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Price Details</h4>
                            </div>
                            <div class="card-body actions">
                                <p class="card-text">Harap cek kembali pesanan anda sebelum melakukan pembayaran!</p>
                                <hr />
                                <ul class="list-unstyled price-details">
                                    <li class="price-detail">
                                        <div class="details-title">Total Item</div>
                                        <div class="detail-amt">
                                            <strong>{{ totalItems }}</strong>
                                        </div>
                                    </li>
                                    <li class="price-detail">
                                        <div class="details-title">Total Qty</div>
                                        <div class="detail-amt discount-amt text-success">{{ totalQuantity }}</div>
                                    </li>
                                </ul>
                                <hr />
                                <ul class="list-unstyled price-details">
                                    <li class="price-detail">
                                        <div class="details-title">Sub Total</div>
                                        <div class="detail-amt">
                                            <strong>{{ totalItems }}</strong>
                                        </div>
                                    </li>
                                    <li class="price-detail">
                                        <div class="details-title">Diskon</div>
                                        <div class="detail-amt discount-amt">-</div>
                                    </li>
                                    <li class="price-detail">
                                        <div class="details-title">Nominal Diskon</div>
                                        <div class="detail-amt discount-amt text-success">{{ formatIDR(0) }}</div>
                                    </li>
                                    <li class="price-detail">
                                        <div class="details-title">Pajak PPN</div>
                                        <div class="detail-amt discount-amt"><strong>{{ tax_value }}%</strong></div>
                                    </li>
                                    <li class="price-detail">
                                        <div class="details-title">Nominal Pajak</div>
                                        <div class="detail-amt discount-amt">{{ formatIDR(countTax(totalPrice, tax_value)) }}</div>
                                    </li>
                                </ul>
                                <hr />
                                <ul class="list-unstyled price-details">
                                    <li class="price-detail">
                                        <div class="details-title">Total Tagihan</div>
                                        <div class="detail-amt font-weight-bolder">{{ countTotal(totalPrice) }}</div>
                                    </li>
                                </ul>
                                <button type="submit" class="btn btn-primary btn-block btn-next delivery-address mt-2" :disabled="totalItems === 0">
                                    Proses Pembayaran
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { eventBus } from '../../eventBus';

export default {
    data() {
        return {
            // currentStep: 1,
            cartItems: [],
            address: {
                name: '',
                phone: '',
            },
            paymentMethod: '',
            tax_value: '',
            loading: false,
            formData: {
                fullName: '',
                mobileNumber: '',
                email: '',
                address: '',
                note: '',
                paymentMethod: '',
            },
            errors: {},
            formSubmitted: false,
        };
    },
    mounted() {
        this.fetchCartItems();
        this.initializeTouchSpin();
    },
    watch: {
        'eventBus.cartUpdated': function(newValue, oldValue) {
            this.fetchCartItems();
        }
    },
    computed: {
        totalPrice() {
            return this.cartItems.reduce((acc, item) => acc + (item.price * item.qty), 0);
        },
        totalItems() {
            return this.cartItems.length;
        },
        totalQuantity() {
            return this.cartItems.reduce((acc, item) => acc + item.qty, 0);
        },
        isButtonDisabled() {
            return !this.formData.fullName || this.totalItems === 0;
        },
    },
    methods: {
        fetchCartItems() {
            this.loading = true;
            axios.get('/operator/cart-item').then((response) => {
                this.cartItems = response.data.cartItems;
                this.tax_value = response.data.tax_value;
                this.loading = false;
                this.$nextTick(this.initializeTouchSpin);
                this.initializeTouchSpin();
            }).catch((error) => {
                console.error("Error fetching cart items:", error);
                this.loading = false;
            });
        },

        initializeTouchSpin() {
            if (typeof $ !== 'undefined' && $.fn.TouchSpin) {
                $('.quantity-counter').TouchSpin({
                    min: 1,
                    max: 100,
                    step: 1,
                    boostat: 5,
                    maxboostedstep: 10,
                }).on('change', (event) => {
                    const itemId = $(event.target).data('item-id');
                    const qty = event.target.value;
                    const item = this.cartItems.find(i => i.id === itemId);
                    if (item) {
                        item.qty = qty;
                        this.updateQuantity(item);
                    }
                });
            } else {
                console.error('TouchSpin is not defined');
            }
        },

        // nextStep() {
        //     if (!this.formData.fullName) {
        //         alert("Full Name is required!");
        //         return;
        //     }

        //     this.currentStep = 2;
        // },

        // previousStep() {
        //     this.currentStep = 1;
        // },

        submitPayment() {
            // if (!this.formData.paymentMethod) {
            //     Swal.fire({
            //         icon: 'warning',
            //         title: 'Attention',
            //         text: 'Please select a payment method!',
            //         customClass: {
            //             confirmButton: 'btn btn-primary'
            //         },
            //         buttonsStyling: false
            //     });
            //     return;
            // }

            if (!this.formData.paymentMethod) {
                toastr.warning("Please select a payment method!", "Warning", {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-right",
                    timeOut: 2000
                });
                return;
            }

            if (this.formData.paymentMethod === 'tunai') {
                Swal.fire({
                    title: 'Confirm Payment',
                    text: "You have selected cash payment. Do you want to proceed?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, proceed!',
                    cancelButtonText: 'No, cancel!',
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.processPayment();
                    }
                });
            } else {
                this.processPayment();
            }
        },

        processPayment() {
            // if (!this.formData.paymentMethod) {
            //     toastr.warning("Please select a payment method!", "Warning", {
            //         closeButton: true,
            //         progressBar: true,
            //         positionClass: "toast-top-right",
            //         timeOut: 2000
            //     });
            //     return;
            // }

            const paymentData = {
                fullName: this.formData.fullName,
                mobileNumber: this.formData.mobileNumber,
                email: this.formData.email,
                address: this.formData.address,
                note: this.formData.note,
                paymentMethod: this.formData.paymentMethod,
 
                // totalItems: this.$root.totalItems,
                // totalQuantity: this.$root.totalQuantity,
                // totalPrice: this.$root.totalPrice,
                // taxValue: this.$root.taxValue,
            };

            axios.post('/operator/transaction/checkout/process', paymentData)
                .then(response => {
                    toastr.success("Payment processed successfully!", "Success", {
                        closeButton: true,
                        progressBar: true,
                        positionClass: "toast-top-right",
                        timeOut: 2000
                    });
                    this.formData.fullName = '';
                    this.formData.mobileNumber = '';
                    this.formData.email = '';
                    this.formData.address = '';
                    this.formData.note = '';
                    this.formData.paymentMethod = '';
                })
                .catch(error => {
                    if (error.response && error.response.status === 422) {
                        let errorMessage = Object.values(error.response.data.errors).map(e => e.join(', ')).join('\n');
                        toastr.error(errorMessage, "Validation Error", {
                            closeButton: true,
                            progressBar: true,
                            positionClass: "toast-top-right",
                            timeOut: 5000
                        });
                    } else {
                        toastr.error("An unexpected error occurred. Please try again.", "Error", {
                            closeButton: true,
                            progressBar: true,
                            positionClass: "toast-top-right",
                            timeOut: 2000
                        });
                    }
                });
        },

        updateQuantity(item) {
            console.log('Updating quantity for item with ID:', item.id, 'to:', item.qty);
            axios.put(`/operator/cart/update/${item.id}`, { qty: item.qty })
                .then((response) => {
                    console.log('Item quantity updated successfully:', response.data);
                    eventBus.updateCart();
                    this.fetchCartItems();
                })
                .catch((error) => {
                    console.error("Error updating item quantity:", error);
                });
        },

        removeFromCart(itemId) {
            console.log('Removing item from cart with ID:', itemId);
            axios.delete(`/operator/cart/delete/${itemId}`)
                .then(() => {
                    console.log('Item removed successfully');
                    eventBus.updateCart();
                    this.fetchCartItems();
                })
                .catch((error) => {
                    console.error("Error removing item from cart:", error);
                });
        },

        submitAddressForm() {
            console.log("Address submitted:", this.address);
        },

        formatIDR(value) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value);
        },

        countTax(subTotal, taxRate) {
            let tax = (taxRate / 100) * subTotal;
            return tax;
        },

        countTotal(subTotal) {
            let taxAmount = this.countTax(subTotal, this.tax_value);
            let total = taxAmount + subTotal;
            return this.formatIDR(total);
        }
    },
};
</script>

<style scoped>
    /* Add custom styles for the stepper and other elements here */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.7);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .cart-item-remove-btn {
        background-color: transparent;
        color: #ff0000;
        border: none;
        cursor: pointer;
        font-size: 1.2rem;
    }

    /* Additional styling for the cart item, address, and payment section */
    .checkout-items {
        margin-bottom: 20px;
    }
</style>
