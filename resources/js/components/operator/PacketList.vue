<template>
    <section id="ecommerce-searchbar" class="ecommerce-searchbar">
        <div class="row mt-1">
            <div class="col-sm-12">
                <div class="input-group input-group-merge">
                    <input 
                        type="text" 
                        class="form-control search-product" 
                        v-model="searchQuery" 
                        @input="fetchPackets" 
                        placeholder="Search Product" 
                    />
                    <div class="input-group-append">
                        <span class="input-group-text"><i data-feather="search" class="text-muted"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </section>
  
    <section id="ecommerce-products" class="grid-view">
        <div v-for="packet in packets.data" :key="packet.id" class="card ecommerce-card">
            <div class="item-img text-center">
                <a :href="`/app-ecommerce-details/${packet.id}`">
                    <img class="img-fluid card-img-top" :src="`/storage/${packet.galleries[0]?.image || 'default.jpg'}`" alt="img-placeholder" />
                </a>
            </div>
            <div class="card-body">
                <div class="item-wrapper">
                    <div class="item-rating">
                        <ul class="unstyled-list list-inline">
                           
                        </ul>
                    </div>
                    <div class="item-cost">
                        <h6 class="item-price">{{ formatIDR(packet.price) }}</h6>
                    </div>
                </div>
                <h6 class="item-name">
                    <a class="text-body" :href="`/app-ecommerce-details/${packet.id}`">{{ packet.packet_name }}</a>
                    <span class="card-text item-company">By <a href="javascript:void(0)" class="company-name">Company</a></span>
                </h6>
                <p class="card-text item-description">{{ packet.packet_detail }}</p>
            </div>
            <div class="item-options text-center">
                <!-- <div class="item-wrapper">
                    <div class="item-cost">
                        <h4 class="item-price">{{ packet.price }} {{ packet.currency }}</h4>
                        <p class="card-text shipping">
                            <span class="badge badge-pill badge-light-success">Free Shipping</span>
                        </p>
                    </div>
                </div> -->
                <!-- <a href="javascript:void(0)" class="btn btn-light btn-wishlist">
                    <i data-feather="heart"></i>
                    <span>Wishlist</span>
                </a> -->
                <a href="javascript:void(0)" class="btn btn-primary btn-cart" @click="addToCart(packet)">
                    <i data-feather="shopping-cart"></i>
                    <span class="add-to-cart">Add to cart</span>
                </a>
            </div>
        </div>
    </section>
  
    <section id="ecommerce-pagination">
        <div class="row">
            <div class="col-sm-12">
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center mt-2">
                        <li class="page-item" :class="{ disabled: !packets.prev_page_url }">
                            <a class="page-link" href="javascript:void(0);" @click="fetchPackets(packets.current_page - 1)">Previous</a>
                        </li>
                        <li class="page-item" v-for="page in pages" :key="page">
                            <a class="page-link" href="javascript:void(0);" @click="fetchPackets(page)">{{ page }}</a>
                        </li>
                        <li class="page-item" :class="{ disabled: !packets.next_page_url }">
                            <a class="page-link" href="javascript:void(0);" @click="fetchPackets(packets.current_page + 1)">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </section>
    <div v-if="loading" class="loading-overlay">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Adding to cart...</span>
        </div>
    </div>
</template>
  
<script>
    import { eventBus } from '../../eventBus';
    export default {
        data() {
            return {
                packets: {
                    data: [],
                    current_page: 1,
                    last_page: 1,
                    next_page_url: null,
                    prev_page_url: null,
                    total: 0,
                },
                searchQuery: '',
                pages: [],
                loading: false,
            };
        },
        mounted() {
            this.fetchPackets(); 
        },
        methods: {
            fetchPackets(page = 1) {
                this.loading = true;

                let loadingToast = toastr.info("Loading data... Please wait.", "⏳ Processing", {
                    closeButton: false,
                    tapToDismiss: false,
                    progressBar: true,
                    positionClass: "toast-top-right",
                    timeOut: 0,
                    extendedTimeOut: 0
                });
                axios
                    .get('/operator/transaction-data', {
                        params: {
                            page: page,
                            search: this.searchQuery,
                        },
                    })
                    .then((response) => {
                        this.packets = response.data;
                        this.pages = Array.from({ length: this.packets.last_page }, (_, i) => i + 1);
                        this.updateSearchResults();
                        this.loading = false;
                        toastr.clear(loadingToast);
                        toastr.success("Data loaded successfully", "✅ Success", {
                            closeButton: true,
                            tapToDismiss: false,
                            progressBar: true,
                            positionClass: "toast-top-right",
                            timeOut: 2000
                        });
                  })
                  .catch((error) => {
                        this.loading = false;
                        console.log(error);
                        toastr.clear(loadingToast);
                        toastr.error('Error loading data. Please try again.', '❌ Error', {
                            closeButton: true,
                            tapToDismiss: false,
                            progressBar: true,
                            positionClass: "toast-top-right",
                            timeOut: 3000
                        });
                  });
            },
  
            updateSearchResults() {
                const searchResultsDiv = document.getElementById('search-results');
                if (searchResultsDiv) {
                    searchResultsDiv.textContent = `${this.packets.total} results found`;
                }
            },

            addToCart(packet) {
                const cartData = {
                    packetId: packet.id,
                    locationId: packet.locationId,
                    qty: 1, 
                    price: packet.price,
                    sub_total: packet.price, 
                };

                this.loading = true;

                let loadingToast = toastr.info("Adding to cart... Please wait.", "⏳ Processing", {
                    closeButton: false,
                    tapToDismiss: false,
                    progressBar: true,
                    positionClass: "toast-top-right",
                    timeOut: 0, // Keep the toastr open
                    extendedTimeOut: 0
                });

                axios.post('/operator/add-to-cart', cartData)
                    .then((response) => {
                        // if (response.data.message === 'This item is already in your cart.') {
                        //     toastr.info(response.data.message); 
                        // } else {
                        //     toastr.success(response.data.message); 
                        // }
                        this.loading = false;
                        toastr.clear(loadingToast);
                        if (response.data.message === 'This item is already in your cart.') {
                            toastr.info(response.data.message);
                        } else {
                            toastr.success(response.data.message)
                            eventBus.cartUpdated = !eventBus.cartUpdated; 
                        }
                        // eventBus.updateCart();
                    })
                    .catch((error) => {
                        console.log('Error adding to cart:', error);
                        this.loading = false;
                        toastr.clear(loadingToast);
                        toastr.error('An error occurred while adding the item to the cart.')
                    });
            },

            formatIDR(value) {
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value);
            }
        },
    };
</script>  
  
<style scoped>
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
</style>
  