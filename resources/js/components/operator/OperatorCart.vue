<template>
    <div v-if="cartItems.length > 0" v-for="item in cartItems" :key="item.id" class="media align-items-center">
        <img class="d-block rounded mr-1" :src="`/storage/${item.packet.galleries[0]?.image || 'default.jpg'}`" alt="donuts" width="62">
        <div class="media-body">
            <!-- <a class="ficon cart-item-remove" @click="removeFromCart(item.id)">
                <i data-feather="x"></i>
            </a> -->
            <!-- <button @click="removeFromCart(item.id)" class="cart-item-remove-btn">
                <i data-feather="x"></i>
            </button> -->
            <div class="media-heading">
                <h6 class="cart-item-title">
                    <a class="text-body" href="app-ecommerce-details.html"> {{ item.packet.packet_name }}</a>
                </h6>
                <small class="cart-item-by">{{ item.location.location_name }}</small>
            </div>
            <div class="cart-item-qty">
                <div class="input-group">
                    <input type="number" v-model="item.qty" @input="updateQuantity(item)" class="touchspin-cart" :ref="'qty-input-' + item.id" />
                </div>
            </div>
            <!-- <h5 class="cart-item-price">{{ formatIDR(item.sub_total) }}</h5> -->
            <div class="cart-item-price-container">
                <h5 class="cart-item-price">{{ formatIDR(item.sub_total) }}</h5>
                <button class="cart-item-remove-btn" @click="removeFromCart(item.id)">
                    <span>x</span>
                </button>
            </div>
        </div>
        <!-- <button class="cart-item-remove-btn" @click="removeFromCart(item.id)">
            <i data-feather="x"></i>
        </button> -->
    </div>

    <div v-else class="mt-1">
        <div class="text-center">
            <p>Your cart is empty.</p>
        </div>
    </div>
</template>

<script>
import { eventBus } from '../../eventBus';

export default {
    data() {
        return {
            cartItems: [],
            cartTotal: 0,
        };
    },
    mounted() {
        console.log('CartList Mounted');
        this.fetchCartItems();
        this.reinitializeInputs();
        // this.$nextTick(() => {
        //     // Reinitialize Feather icons after the DOM update
        //     this.reinitializeFeatherIcons();
        // });
    },
    created() {
        console.log('Component Created - Fetching cart items...');
        this.fetchCartItems();
        this.$watch(() => eventBus.cartUpdated, () => {
            this.fetchCartItems();
        });
    },
    computed: {
        totalPrice() {
            return this.cartItems.reduce((acc, item) => acc + (item.price * item.qty), 0);
        }
    },
    methods: {
        fetchCartItems() {
            console.log('Fetching cart items...');
            axios.get('/operator/cart-item')
                .then((response) => {
                    console.log('Cart items fetched successfully:', response.data);
                    this.cartItems = response.data.cartItems;
                    this.cartTotal = response.data.total;
                    this.updateTotalPrice();
                    this.reinitializeInputs();
                    this.updateCartCount();
                    // this.$nextTick(() => {
                    //     // Reinitialize Feather icons after the DOM update
                    //     this.reinitializeFeatherIcons();
                    // });
                })
                .catch((error) => {
                    console.error("Error fetching cart items:", error);
                });
        },

        // reinitializeInputs() {
        //     this.$nextTick(() => {
        //         if (window.$ && window.$.fn && $.fn.TouchSpin) {
        //             $(".touchspin-cart").TouchSpin({
        //                 min: 1,
        //                 max: 100
        //             });
        //         }
        //     });
        // },

        // reinitializeInputs() {
        //     this.$nextTick(() => {
        //         this.cartItems.forEach(item => {
        //         const inputElement = this.$refs['qty-input-' + item.id];
        //         if (inputElement) {
        //             $(inputElement).TouchSpin({
        //                 min: 1,
        //                 max: 100,
        //                 step: 1,
        //                 boostat: 5,
        //                 maxboostedstep: 10,
        //             });
        //         }
        //         });
        //     });
        // },

        reinitializeInputs() {
            this.$nextTick(() => {
                this.cartItems.forEach(item => {
                    const inputElement = this.$refs['qty-input-' + item.id];

                    if (inputElement) {
                        $(inputElement).TouchSpin({
                            min: 1,
                            max: 100,
                            step: 1,
                            boostat: 5,
                            maxboostedstep: 10,
                        }).on('change', (event) => {
                            item.qty = event.target.value;
                            this.updateQuantity(item);
                        });
                    }
                });
            });
        },

        // reinitializeFeatherIcons() {
        //     if (window.Feather) {
        //         Feather.replace(); // Reinitialize Feather icons
        //     }
        // },

        updateTotalPrice() {
            const totalPriceDiv = document.getElementById('total-price');
            if (totalPriceDiv) {
                totalPriceDiv.textContent = this.formatIDR(this.totalPrice);
            }
        },

        updateCartCount() {
            const cartItemCount = document.querySelector('.cart-item-count');
            const cartCount = document.getElementById('item-count');
            if (cartItemCount && cartCount) {
                cartItemCount.textContent = this.cartItems.length;
                cartCount.textContent = this.cartItems.length + " Items";
            }
        },

        formatIDR(value) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value);
        },

        removeFromCart(itemId) {
            console.log('Removing item from cart with ID:', itemId);
            axios.delete(`/operator/cart/delete/${itemId}`)
                .then(() => {
                    console.log('Item removed successfully');
                    this.fetchCartItems();
                    this.updateTotalPrice();
                    this.updateCartCount();
                })
                .catch((error) => {
                    console.error("Error removing item from cart:", error);
                });
        },

        updateQuantity(item) {
            console.log('Updating quantity for item with ID:', item.id, 'to:', item.qty);
            axios.put(`/operator/cart/update/${item.id}`, { qty: item.qty })
                .then((response) => {
                    console.log('Item quantity updated successfully:', response.data);
                    this.fetchCartItems();
                    this.updateTotalPrice();
                    this.updateCartCount();
                })
                .catch((error) => {
                    console.error("Error updating item quantity:", error);
                });
        },

    },
};
</script>

<style scoped>
    .cart-item {
        position: relative;
        margin-bottom: 10px;
    }

    .cart-item-price-container {
        margin-right: .9rem;
        position: relative;
        display: flex;
        align-items: center;
    }

    .cart-item-remove-btn {
        position: absolute;
        top: -4px;
        right: -20px;
        background-color: transparent;
        border: none;
        color: #ff0000;
        /* font-size: 18px; */
        font-size: 1.2rem;
        border-radius: 50%;
        padding: 5px;
        cursor: pointer;
        z-index: 10;
    }

    .cart-item-remove-btn:hover {
        background-color: rgba(255, 0, 0, 0.1);
        color: #fff;
    }

    .cart-item-price {
        word-wrap: break-word;
        overflow-wrap: break-word;
        max-width: 100%;
    }

    .cart-item-price {
        font-size: 1rem;
    }

    @media (max-width: 767px) {
        .cart-item-price {
            font-size: .9rem;
        }
    }
</style>


