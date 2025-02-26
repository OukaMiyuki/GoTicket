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
                <a href="javascript:void(0)" class="btn btn-primary btn-cart">
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
</template>
  
<script>
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
            };
        },
        mounted() {
            this.fetchPackets(); 
        },
        methods: {
            fetchPackets(page = 1) {
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
                  })
                  .catch((error) => {
                      console.log(error);
                  });
            },
  
            updateSearchResults() {
                const searchResultsDiv = document.getElementById('search-results');
                if (searchResultsDiv) {
                    searchResultsDiv.textContent = `${this.packets.total} results found`;
                }
            },
            formatIDR(value) {
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value);
            }
        },
    };
</script>  
  
<style scoped>

</style>
  