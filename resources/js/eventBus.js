import { reactive } from 'vue';

export const eventBus = reactive({
    cartUpdated: false,
    updateCart: function() {
        this.cartUpdated = !this.cartUpdated;
    }
});
